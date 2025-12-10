<?php
include "site-functions.php";

// Handle Stop request
if (isset($_POST['action']) && $_POST['action'] === 'cancel' && isset($_POST['download_id'])) {
    $id = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['download_id']);
    $tempDir = sys_get_temp_dir();
    $pidFile = $tempDir . DIRECTORY_SEPARATOR . $id . '.pid';

    if (file_exists($pidFile)) {
        $pid = trim(file_get_contents($pidFile));
        if (is_numeric($pid)) {
            exec("kill -9 $pid");
        }
        @unlink($pidFile);
    }

    $files = glob($tempDir . DIRECTORY_SEPARATOR . $id . '_*');
    foreach ($files as $file)
        @unlink($file);

    exit('Stopped');
}

// Handle POST request for downloading
$error = null;
$generatedCommand = null;
$searchResults = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_url'])) {
    set_time_limit(0); // Prevent timeout for large downloads
    $videoUrl = trim($_POST['video_url']);

    if (!filter_var($videoUrl, FILTER_VALIDATE_URL)) {
        // Search Logic
        $ytBin = 'yt-dlp';
        $cmd = $ytBin . ' --flat-playlist --dump-single-json --no-warnings ' . escapeshellarg('ytsearch10:' . $videoUrl);
        exec($cmd, $output, $ret);
        if ($ret === 0 && !empty($output)) {
            $data = json_decode(implode("\n", $output), true);
            if (isset($data['entries'])) {
                $searchResults = $data['entries'];
            }
        }
    } else {
        $downloadType = $_POST['download_type'];

        // Absolute path to cookies
        $originalCookiesFile = __DIR__ . '/youtube.cookies';

        // Temp directory and unique ID for this download
        $tempDir = sys_get_temp_dir();
        $uniqId = isset($_POST['download_id']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['download_id']) : uniqid('dl_', true);

        // Copy cookies to temp file to avoid permission errors when yt-dlp tries to update them
        $cookiesFile = $tempDir . DIRECTORY_SEPARATOR . $uniqId . '_cookies.txt';
        copy($originalCookiesFile, $cookiesFile);

        // Extend cookie expiration by 10 years
        $cookieLines = file($cookiesFile);
        foreach ($cookieLines as &$line) {
            if (strpos($line, '#') === 0 || trim($line) === '') {
                continue;
            }
            $parts = explode("\t", trim($line));
            if (isset($parts[4]) && is_numeric($parts[4]) && $parts[4] > 0) {
                $parts[4] += 315360000; // Add 10 years (in seconds)
            }
            $line = implode("\t", $parts) . "\n";
        }
        file_put_contents($cookiesFile, $cookieLines);

        // Output template with unique ID prefix to identify the file later
        $outputTemplate = $tempDir . DIRECTORY_SEPARATOR . $uniqId . '_%(title)s.%(ext)s';

        $ytBin = 'yt-dlp';
        $cmdArgs = [];
        $cmdArgs[] = escapeshellcmd($ytBin);
        $cmdArgs[] = '--cookies ' . escapeshellarg($cookiesFile);
        $cmdArgs[] = '--output ' . escapeshellarg($outputTemplate);
        $cmdArgs[] = '--no-playlist';
        $cmdArgs[] = '--ffmpeg-location /bin';

        if ($downloadType === 'audio') {
            // Convert to MP3 (requires ffmpeg)
            $cmdArgs[] = '-f ' . escapeshellarg('bestaudio/best');
            $cmdArgs[] = '-x';
            $cmdArgs[] = '--audio-format mp3';
            $cmdArgs[] = '--audio-quality 0';
        } else {
            // Video - ensure MP4
            $format = "bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best";
            $cmdArgs[] = '-f ' . escapeshellarg($format);
            $cmdArgs[] = '--merge-output-format mp4';
        }

        $cmdArgs[] = escapeshellarg($videoUrl);
        $command = implode(' ', $cmdArgs);

        // Execute command
        // exec($command . ' 2>&1', $output, $returnVar);

        // Use proc_open to allow interruption
        $logFile = $tempDir . DIRECTORY_SEPARATOR . $uniqId . '.log';
        $pidFile = $tempDir . DIRECTORY_SEPARATOR . $uniqId . '.pid';

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', $logFile, 'w'],
            2 => ['file', $logFile, 'w']
        ];

        $process = proc_open($command, $descriptors, $pipes);

        if (is_resource($process)) {
            $status = proc_get_status($process);
            file_put_contents($pidFile, $status['pid']);

            while (true) {
                $status = proc_get_status($process);
                if (!$status['running'])
                    break;

                if (!file_exists($pidFile)) {
                    // PID file removed by stop action
                    proc_terminate($process);
                    $returnVar = -1; // Cancelled
                    break;
                }
                usleep(200000);
            }

            if (file_exists($pidFile))
                @unlink($pidFile);
            if (!isset($returnVar))
                $returnVar = $status['exitcode'];

            proc_close($process);
            $output = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES) : [];
            @unlink($logFile);
        } else {
            $returnVar = 1;
            $output = ["Failed to start process"];
        }

        // Clean up temp cookies
        if (file_exists($cookiesFile)) {
            unlink($cookiesFile);
        }

        if ($returnVar === 0) {
            // Find the generated file
            $files = glob($tempDir . DIRECTORY_SEPARATOR . $uniqId . '_*');

            if (!empty($files)) {
                $filePath = $files[0];
                if (file_exists($filePath)) {
                    // Determine filename for the user (remove unique ID prefix)
                    $fileName = basename($filePath);
                    $userFileName = substr($fileName, strlen($uniqId) + 1);

                    // Set a cookie to let the client know the download has started (processing is done)
                    if (isset($_POST['download_token'])) {
                        setcookie('download_token', $_POST['download_token'], [
                            'expires' => time() + 60,
                            'path' => '/',
                        ]);
                    }

                    // Send headers to trigger download dialog
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $userFileName . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($filePath));

                    // Clean buffer and stream file
                    if (ob_get_level())
                        ob_end_clean();
                    readfile($filePath);

                    // Delete temp file
                    unlink($filePath);
                    exit;
                }
            }
            $error = "Error: Downloaded file could not be located.";
        } else {
            $error = "Error executing yt-dlp: " . implode("\n", $output);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Downloader (PHP)</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>YouTube Downloader</h1>
        <p>Powered by PHP</p>

        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form action="index.php" method="post" id="download-form">
            <input type="text" name="video_url" placeholder="Enter search string or YouTube Video Link" required>
            <div class="buttons">
                <button type="submit" name="download_type" value="video" id="download-video">Video</button>
                <button type="submit" name="download_type" value="audio" id="download-audio">Audio</button>
            </div>
        </form>

        <div id="processing">
            <div class="spinner"></div> <span id="processing-text">Processing... Please wait.</span><br><br>
            <button type="button" id="stop-button"
                style="background-color: #d9534f; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">Stop
                Download</button>
        </div>

        <?php if (!empty($generatedCommand)): ?>
            <div class="success">
                <p><strong>Command Generated:</strong></p>
                <textarea readonly class="command-output"><?= htmlspecialchars($generatedCommand) ?></textarea>
            </div>
        <?php endif; ?>

        <?php if (!empty($searchResults)): ?>
            <div class="search-results">
                <h3>Search Results</h3>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($searchResults as $result): ?>
                        <li
                            style="background: #f4f4f4; margin: 5px 0; padding: 10px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                            <span
                                style="font-weight: bold; flex: 1; margin-right: 10px; word-wrap: break-word;"><?= htmlspecialchars($result['title']) ?></span>
                            <div class="buttons" style="flex-shrink: 0; margin: 0;">
                                <button type="button" id="download-video"
                                    onclick="downloadFromSearch('https://www.youtube.com/watch?v=<?= $result['id'] ?>', 'video')">Video</button>
                                <button type="button" id="download-audio"
                                    onclick="downloadFromSearch('https://www.youtube.com/watch?v=<?= $result['id'] ?>', 'audio')">Audio</button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php copyright(); ?>
    </div>

    <script>
        document.getElementById('download-form').addEventListener('submit', function () {
            var token = new Date().getTime();
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'download_token';
            input.value = token;
            this.appendChild(input);

            var downloadId = 'dl_' + new Date().getTime() + '_' + Math.floor(Math.random() * 10000);
            var inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'download_id';
            inputId.value = downloadId;
            this.appendChild(inputId);

            document.getElementById('stop-button').setAttribute('data-id', downloadId);

            var videoUrl = this.querySelector('input[name="video_url"]').value.trim();
            var processingText = document.getElementById('processing-text');
            if (/^(http|https):\/\//i.test(videoUrl)) {
                processingText.innerText = 'Processing... Please wait.';
            } else {
                processingText.innerText = 'Searching... Please wait.';
            }

            document.getElementById('processing').style.display = 'block';

            var pollTimer = window.setInterval(function () {
                if (document.cookie.indexOf('download_token=' + token) !== -1) {
                    window.clearInterval(pollTimer);
                    document.getElementById('processing').style.display = 'none';
                    var searchResults = document.querySelector('.search-results');
                    if (searchResults) {
                        searchResults.style.display = 'none';
                    }
                    document.cookie = 'download_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                }
            }, 500);
        });

        document.getElementById('stop-button').addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            if (id && confirm('Stop the download?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'index.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('action=cancel&download_id=' + id);
                document.getElementById('processing').style.display = 'none';
                window.location.reload();
            }
        });

        function downloadFromSearch(url, type) {
            var form = document.getElementById('download-form');
            form.querySelector('input[name="video_url"]').value = url;
            if (type === 'video') {
                document.getElementById('download-video').click();
            } else {
                document.getElementById('download-audio').click();
            }
        }
    </script>

</body>

</html>