<?php
header('Content-Type: application/json');

// This path MUST match the log file path used in logviewer.php and clearLog.php
$logFile = '/web/pm/playback.log';

$response = [];

if (file_exists($logFile) && is_readable($logFile)) {
    // Read the log file into an array of lines.
    // FILE_IGNORE_NEW_LINES: Do not add newline at the end of each array element
    // FILE_SKIP_EMPTY_LINES: Skip empty lines
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lines === false) {
        $response['error'] = "Failed to read log file: $logFile";
    } else {
        $response['lines'] = $lines;
    }
} else {
    $response['error'] = "Log file '$logFile' does not exist or is not readable.";
}

echo json_encode($response);
?>