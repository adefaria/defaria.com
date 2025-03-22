<?php

const audioImg = "/icons/sound2.gif";
const videoImg = "/icons/movie.gif";
const binaryImg = '/icons/binary.gif';
const imageImg = '/icons/image2.gif';
const dirImg = '/icons/dir.gif';
const documentRoot = '/web/';

function debug($message)
{
    echo "<font color='red'>$message</font><br>";
}

function determineType(string $filename): ?string
{
    if (is_dir($filename)) {
        return dirImg;
    }

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a'];
    $videoExtensions = ['mp4', 'avi', 'mkv', 'mov', 'webm', 'flv', 'wmv'];
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

    if (in_array($extension, $audioExtensions)) {
        return audioImg;
    }

    if (in_array($extension, $videoExtensions)) {
        return videoImg;
    }

    if (in_array($extension, $imageExtensions)) {
        return imageImg;
    }

    return binaryImg;
}

/**
 * Generates a directory listing with "Open" and "Download" buttons for files.
 *
 * @param string $directory The directory to list.
 * @param string $baseUrl The base URL for constructing links.
 * @param bool $showHidden Whether to show hidden files/directories.
 * @return void
 */
function generateDirectoryListing(string $directory, string $baseUrl, bool $showHidden = false): void
{
    // Check if the directory exists and is readable.
    if (!is_dir($directory) || !is_readable($directory)) {
        echo "<p>Error: Directory '$directory' does not exist or is not readable.</p>";
        return;
    }

    // Get the directory name for display.
    $directoryName = basename($directory);

    echo <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unicode Example</title>
<style>
  .button-container {
    display: flex;
    justify-content: center;
    gap: 20px; /* Space between buttons */
    margin-top: 5px;
  }

  .button {
    padding: 5px 10px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px; /* Space between icon and text */
  }

  .open-button {
    background-color: #4CAF50; /* Green */
    color: white;
  }

  .download-button {
    background-color: #008CBA; /* Blue */
    color: white;
  }

  .button:hover {
    transform: scale(1.05); /* Slight enlargement on hover */
  }

  .open-button:hover {
    background-color: #45a049;
  }

  .download-button:hover {
    background-color: #0077b3;
  }
   
  .icon {
    font-size: 10px;
  }

  a {
    text-decoration: none;
  }

  .button a {
    text-decoration: none;
    color: inherit;
  }
    </style>
    <script>
function logmsg(msg) {
    const fileType = 'Download';
    const xhr = new XMLHttpRequest();
    const IPAddr = '{$_SERVER["REMOTE_ADDR"]}';
    const data = {
        IPAddr: IPAddr,
        fileType: fileType,
        file: "", // This will be updated in the downloadFile function
        msg: msg,
    };

    xhr.open('POST', 'https://defaria.com:3000/log-playback', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(data));
}

function downloadFile(url, filename) {
    logmsg('File: ' + filename);

    // Create a temporary link and trigger a click to start the download
    const link = document.createElement('a');
    link.href = '/php/monitorFile.php?download=1&url=' + encodeURIComponent(url);
    link.download = filename; // Suggest a filename
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
</head>    
EOF;

    // Start the HTML output.
    echo "<h2>Directory: $directoryName</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>Name</th><th>Type</th><th>Size</th><th>Last Modified</th><th>Actions</th></tr></thead>";
    echo "<tbody>";

    if ($directory == documentRoot) {
        echo "Can't view root directory";
        exit;
    }

    // Handle parent directory link if not at the root.
    if ($directory != documentRoot) {
        $parentDir = dirname($directory);
        $parentUrl = dirname($baseUrl);
        echo "<tr>";
        echo "<td><a href='?dir=" . urlencode(substr($parentDir, strlen(documentRoot))) . "'>..</a></td>";
        echo "<td style=\"text-align: center;\"><img src=\"" . dirImg . "\"></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "<td></td>";
        echo "</tr>";
    }

    // Scan the directory.
    $items = scandir($directory);

    // Iterate through the items.
    foreach ($items as $item) {
        // Skip hidden files/directories if not showing them.
        if (!$showHidden && substr($item, 0, 1) === '.') {
            continue;
        }

        // Construct the full path and URL.
        $fullPath = $directory . '/' . $item;

        // Get file/directory information.
        $isDir = is_dir($fullPath);
        $size = $isDir ? '' : formatSize(filesize($fullPath));
        $lastModified = date('Y-m-d H:i:s', filemtime($fullPath));

        // Determine the links
        $openLink = "/php/monitorFile.php?url=" . urlencode(substr($fullPath, strlen(documentRoot)));
        $downloadLink = "/php/monitorFile.php?download=1&url=" . urlencode(substr($fullPath, strlen(documentRoot)));

        // Start the row.
        echo "<tr>";

        // Display the name with a link if it's a directory.
        echo "<td>";
        if ($isDir) {
            echo "<a href='?dir=" . urlencode(substr($fullPath, strlen(documentRoot))) . "'>$item</a>";
        } else {
            echo "<a href='" . $openLink . "'>" . $item . "</a>";
        }
        echo "</td>";

        // Display the type.
        echo "<td  style=\"text-align: center;\"><img src=\"" . determineType($fullPath) . "\"></td>";

        // Display the size.
        echo "<td>$size</td>";

        // Display the last modified date.
        echo "<td>$lastModified</td>";

        // Display the actions.
        echo "<td>";
        if (!$isDir) {
            echo "<div class=\"button-container\">";

            echo '<button class="button open-button">';
            echo "<a href=\"$openLink\">";
            echo '<span class="icon open-icon"><font size=+2>&#128451;</font></span> Open';
            echo "</a>";
            echo "</button>";

            echo '<button class="button download-button">';
            echo "<a href=\"#\" onclick=\"downloadFile('" . substr($fullPath, strlen(documentRoot)) . "', '$item'); return false;\">";
            echo '<span class="icon download-icon"><font size=+2>&#x1F4E5;</font></span> Download';
            echo "</a>";
            echo "</button>";

            echo "</div>";
        }
        echo "</td>";

        // End the row.
        echo "</tr>";
    }

    // End the table.
    echo "</tbody>";
    echo "</table>";
}

/**
 * Formats a file size into a human-readable string.
 *
 * @param int $bytes The file size in bytes.
 * @param int $precision The number of decimal places.
 * @return string The formatted file size.
 */
function formatSize(int $bytes, int $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Set the default directory to the document root.
$directory = documentRoot;

// Get the requested directory from the query string.
if (!isset($_GET['dir']) || empty($_GET['dir'])) {
    $_GET['dir'] = '/tmp';
}

if ($_GET['dir'] == '/') {
    echo "Unable to browse the root directory";
    exit;
}

if ($_GET['dir'] == '/' || $_GET['dir'] == substr(documentRoot, 1)) {
    echo "Unable to browse the root directory";
    exit;
}

if (isset($_GET['dir'])) {
    $requestedDir = documentRoot . '/' . ltrim($_GET['dir'], '/');
    if (strpos($requestedDir, documentRoot) === 0) {
        $directory = $requestedDir;
    } else {
        echo "<p>Error: Invalid directory requested.</p>";
        exit;
    }
}

// Handle file downloads.
if (isset($_GET['download'])) {
    $fileToDownload = documentRoot . '/' . ltrim($_GET['download'], '/');
    // Sanitize the file path to prevent directory traversal.
    $realFileToDownload = realpath($fileToDownload);
    if (strpos($realFileToDownload, documentRoot) === 0 && is_file($realFileToDownload)) {
        // Set headers for file download.
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($realFileToDownload) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($realFileToDownload));
        readfile($realFileToDownload);
        exit;
    } else {
        echo "<p>Error: Invalid file requested for download.</p>";
        exit;
    }
}

// Construct the base URL.
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
$baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];
if (isset($_GET['dir'])) {
    $baseUrl .= '/' . ltrim($_GET['dir'], '/');
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Directory</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>

    <?php
    // Generate the directory listing.
    generateDirectoryListing($directory, $baseUrl);
    ?>

</body>

</html>