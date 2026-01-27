<?php
ini_set('display_errors', 0); // Don't display errors to the output stream (SSE)
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php_logstream_errors.log'); // Choose a writable path
error_reporting(E_ALL);

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no'); // For Nginx proxy

// Ensure output is sent immediately without buffering
if (ob_get_level() > 0) {
    for ($i = 0; $i < ob_get_level(); $i++) {
        ob_end_flush();
    }
}
ob_implicit_flush(1);

if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', 1);
}
ini_set('zlib.output_compression', 0);


// Shutdown handler to debug exits
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error) {
        debug_log("Script shutting down with error: " . print_r($error, true));
    } else {
        debug_log("Script shutting down cleanly (or connection closed).");
    }
});

set_time_limit(0); // Allow script to run indefinitely

$logFile = '/web/pm/playback.log'; // Ensure this path is correct
$currentLineCount = 0;

$debugLogFile = '/tmp/logstream_debug.log';
function debug_log($msg)
{
    global $debugLogFile;
    file_put_contents($debugLogFile, date('Y-m-d H:i:s') . " [PID " . getmypid() . "] " . $msg . "\n", FILE_APPEND);
}

debug_log("Script started. LogFile: $logFile");

function send_sse_message($data, $event_name = 'logupdate')
{
    debug_log("SSE Message ($event_name): " . json_encode($data));
    echo "event: " . $event_name . "\n";
    echo "data: " . json_encode($data) . "\n\n";
    // ob_flush(); // Not needed with ob_implicit_flush(1)
    flush();
}

function send_initial_log_state($filePath, &$lineCount)
{
    if (file_exists($filePath) && is_readable($filePath)) {
        $content = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($content !== false) {
            send_sse_message(['type' => 'full_log', 'lines' => $content]);
            $lineCount = count($content);
            return;
        }
    }
    send_sse_message(['type' => 'full_log', 'lines' => [], 'message' => 'Log file not found or not readable at startup.']);
    $lineCount = 0;
}

function check_and_send_log_changes($filePath, &$lineCount)
{
    // debug_log("Checking changes for $filePath");
    clearstatcache(true, $filePath); // Essential for getting fresh file info

    if (!file_exists($filePath) || !is_readable($filePath)) {
        debug_log("File not found or not readable. Path: $filePath");
        if ($lineCount > 0 || file_exists($filePath)) { // Only send if there was a change (e.g. file deleted)
            send_sse_message(['type' => 'log_cleared_or_missing', 'message' => "Log file '$filePath' disappeared or became unreadable."]);
        }
        $lineCount = 0;
        return false; // Indicate failure to process, maybe stop watching
    }

    $all_lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($all_lines === false) {
        debug_log("file() returned false for $filePath");
        send_sse_message(['type' => 'error', 'message' => "Failed to read log file: $filePath"]);
        return true; // Continue watching, might be a temporary issue
    }

    $new_total_lines = count($all_lines);
    // debug_log("Read $new_total_lines lines. Old count: $lineCount");

    if ($new_total_lines < $lineCount) { // Log was truncated
        send_sse_message(['type' => 'truncated', 'lines' => $all_lines]);
    } elseif ($new_total_lines > $lineCount) { // New lines available
        $new_lines = array_slice($all_lines, $lineCount);
        send_sse_message(['type' => 'new_lines', 'lines' => $new_lines]);
    }
    // If $new_total_lines == $lineCount, no change in line count.

    $lineCount = $new_total_lines;
    return true;
}

if (!extension_loaded('inotify')) {
    send_sse_message(['type' => 'error', 'message' => 'inotify extension not loaded on server. Real-time updates unavailable.']);
    exit;
}

$inotify = inotify_init();
if (!$inotify) {
    send_sse_message(['type' => 'error', 'message' => 'Failed to initialize inotify.']);
    exit;
}
stream_set_blocking($inotify, 0); // Ensure non-blocking mode for select/read interaction

// Attempt to add watch. If log file doesn't exist, inotify_add_watch will fail.
// We'll try to create it if it's missing, then watch.
if (!file_exists($logFile)) {
    if (@touch($logFile) === false) {
        send_sse_message(['type' => 'error', 'message' => "Log file '$logFile' does not exist and could not be created."]);
        fclose($inotify);
        exit;
    }
    chmod($logFile, 0664); // Ensure web server can write if it creates it, and read
}
clearstatcache(true, $logFile);

$watch_descriptor = @inotify_add_watch($inotify, $logFile, IN_MODIFY | IN_CLOSE_WRITE | IN_ATTRIB | IN_DELETE_SELF | IN_MOVE_SELF | IN_CREATE);

if ($watch_descriptor === false) {
    send_sse_message(['type' => 'error', 'message' => "Failed to watch log file '$logFile'. Check permissions or if path is correct."]);
    fclose($inotify);
    exit;
}

// Send the initial state of the log file
send_initial_log_state($logFile, $currentLineCount);

$last_ping_time = time();

while (true) {
    if (connection_aborted()) {
        debug_log("connection_aborted() returned true.");
        break; // Client disconnected
    }

    // Send a keep-alive comment every 20 seconds if no other data
    // Send a keep-alive comment every 5 seconds if no other data
    if (time() - $last_ping_time > 5) {
        echo ": keepalive " . time() . "\n\n"; // SSE comment with timestamp to ensure change
        flush();
        $last_ping_time = time();
    }

    // Use stream_select to wait for events or timeout
    // This avoids busy waiting loop with usleep and allows instant response to events
    $read = [$inotify];
    $write = null;
    $except = null;
    $timeout = 10; // Wait up to 1 seconds (let loop handle heartbeat freq)

    // debug_log("Entering stream_select...");
    $result = stream_select($read, $write, $except, 1);
    // debug_log("stream_select returned: " . var_export($result, true));

    if ($result === false) {
        debug_log("stream_select failed/false.");
        break; // Exit loop on error
    }

    if ($result > 0) {
        $events = inotify_read($inotify); // reliable read since select said yes

        if ($events) {
            $last_ping_time = time(); // Reset ping timer on activity
            $fileChanged = false;
            foreach ($events as $event) {
                if ($event['mask'] & (IN_MODIFY | IN_CLOSE_WRITE | IN_ATTRIB)) {
                    $fileChanged = true;
                }
                if ($event['mask'] & (IN_DELETE_SELF | IN_MOVE_SELF)) {
                    // Log rotated or deleted. Don't exit. Wait for it to reappear.
                    debug_log("Log file moved or deleted. Attempting recovery...");
                    inotify_rm_watch($inotify, $watch_descriptor);

                    // Blocking loop (with small sleeps) to wait for file recreation
                    // But we must also keep sending heartbeats so client doesn't time out
                    $recreateAttempts = 0;
                    while (!file_exists($logFile)) {
                        if (connection_aborted())
                            break 2; // Break main loop
                        if ($recreateAttempts % 5 == 0) { // Every 1 sec (5 * 200ms)
                            echo ": keepalive " . time() . "\n\n";
                            flush();
                        }
                        usleep(200000); // 200ms
                        $recreateAttempts++;
                        if ($recreateAttempts > 300) { // 60 seconds timeout
                            send_sse_message(['type' => 'error', 'message' => 'Log file lost for too long.']);
                            break 2;
                        }
                    }

                    // File should exist now (or we broke loop)
                    if (file_exists($logFile)) {
                        // Re-add watch
                        clearstatcache(true, $logFile);
                        $watch_descriptor = @inotify_add_watch($inotify, $logFile, IN_MODIFY | IN_CLOSE_WRITE | IN_ATTRIB | IN_DELETE_SELF | IN_MOVE_SELF | IN_CREATE);
                        if ($watch_descriptor === false) {
                            debug_log("Failed to re-watch log file.");
                            // Maybe just sending full log or attempting blindly next loop?
                            // Let's reset line count and treat as new file?
                            $currentLineCount = 0;
                            send_initial_log_state($logFile, $currentLineCount);
                        } else {
                            debug_log("Log file recovered. Re-watched.");
                            $currentLineCount = 0; // Reset line count as it's a new file
                            send_initial_log_state($logFile, $currentLineCount);
                        }
                    }
                    continue; // Continue main loop
                }
            }
            if ($fileChanged) {
                if (!check_and_send_log_changes($logFile, $currentLineCount)) {
                    debug_log("check_and_send_log_changes failed (unreadable?). Not exiting, just retrying next loop.");
                    // Do not exit. Just continue.
                    continue;
                }
            }
        }
    }
    // Loop continues to check connection_aborted and send heartbeats
}

if ($watch_descriptor !== false && $inotify) {
    @inotify_rm_watch($inotify, $watch_descriptor);
}
if ($inotify) {
    @fclose($inotify);
}
?>