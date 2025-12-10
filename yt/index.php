<?php
// Handle POST request for downloading
$error = null;
$generatedCommand = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_url'])) {
    set_time_limit(0); // Prevent timeout for large downloads
    $videoUrl = $_POST['video_url'];
    $downloadType = $_POST['download_type'];

    // Absolute path to cookies
    $originalCookiesFile = __DIR__ . '/youtube.cookies';

    // Temp directory and unique ID for this download
    $tempDir = sys_get_temp_dir();
    $uniqId = uniqid('dl_', true);

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
    exec($command . ' 2>&1', $output, $returnVar);

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

        <div id="processing">
            <div class="spinner"></div> Processing... Please wait.
        </div>

        <?php if (!empty($generatedCommand)): ?>
            <div class="success">
                <p><strong>Command Generated:</strong></p>
                <textarea readonly class="command-output"><?= htmlspecialchars($generatedCommand) ?></textarea>
            </div>
        <?php endif; ?>

        <form action="index.php" method="post" id="download-form">
            <input type="text" name="video_url" placeholder="Enter YouTube Video Link" required>
            <div class="buttons">
                <button type="submit" name="download_type" value="video" id="download-video">Download Video</button>
                <button type="submit" name="download_type" value="audio" id="download-audio">Download Audio</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('download-form').addEventListener('submit', function () {
            var token = new Date().getTime();
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'download_token';
            input.value = token;
            this.appendChild(input);

            document.getElementById('processing').style.display = 'block';

            var pollTimer = window.setInterval(function () {
                if (document.cookie.indexOf('download_token=' + token) !== -1) {
                    window.clearInterval(pollTimer);
                    document.getElementById('processing').style.display = 'none';
                    document.cookie = 'download_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                }
            }, 500);
        });
    </script>

</body>

</html>