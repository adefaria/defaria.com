<?php

const audioImg = "/icons/sound2.gif";
const videoImg = "/icons/movie.gif";
const binaryImg = '/icons/binary.gif';
const imageImg = '/icons/image2.gif';
const dirImg = '/icons/dir.gif';
const documentRoot = '/web/';
const realDocumentRoot = '/web';
const tmpDirectory = '/web/tmp';

function debug($message)
{
    echo "<font color='red'>DEBUG:</font> $message</font><br>";
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
<style>
  .button-container {
    display: flex;
    justify-content: center;
    gap: 20px; /* Space between buttons */
    margin-top: 5px;
    width: 100%; /* Make the button container take full width */
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
    width: auto; /* Make the buttons take only the width they need */
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
    white-space: nowrap; /* Prevent text from wrapping */
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

  .button a, .button a:visited {
    text-decoration: none;
    color: inherit;
  }
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
        background-color: #008CBA;
        color: white;
    }
    /* Make the Actions column wider */
    .actions-column {
        width: 300px; /* Increased width to accommodate buttons */
        text-align: center;
    }
    /* Center the Type column */
    .type-column {
        text-align: center;
        width: 50px;
    }
    /* Center the Size column */
    .size-column {
        text-align: center;
        width: 75px;
    }
    /* Center the Expires In column */
    .expires-column {
        text-align: center;
        width: 100px;
    }

    body {
        background-color: white; /* Default light mode background */
        color: black;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .dark-mode body {
        background-color: #121212 !important;
        color: #e0e0e0 !important;
    }
    
    .dark-mode th {
        background-color: #008CBA !important; /* Match Download button blue */
        color: white !important;
        border-color: #555 !important;
    }
    
    .dark-mode td {
        background-color: #1e1e1e !important;
        color: #e0e0e0 !important;
        border-color: #333 !important;
    }
    
    /* Ensure links are readable on the dark td background */
    .dark-mode td a {
        color: #64b5f6 !important; /* Lighter blue for visibility on dark */
    }
    
    .dark-mode td a:visited {
        color: #ce93d8 !important; /* Lighter purple for visited on dark */
    }
    
    /* Fix button text color in dark mode (override generic link color) */
    .dark-mode .button a, .dark-mode .button a:visited {
        color: white !important;
        text-decoration: none !important;
    }
    /* Default: Invert icons for visibility (White on Dark) */
    .dark-mode img {
        filter: invert(1);
    }

    /* Exception: Directory icon looks good as is (Original colors) */
    .dark-mode img[src*="dir.gif"] {
        filter: none;
    }

    /* Gold/Yellow for MP3 (Audio) and Binary */
    .dark-mode img[src*="sound2.gif"],
    .dark-mode img[src*="binary.gif"] {
        /* Approximate Gold Color (#FFD700) from Black */
        filter: invert(75%) sepia(70%) saturate(900%) hue-rotate(360deg) brightness(103%) contrast(105%);
    }
    </style>
    <script>
    function updateTheme() {
        const isStandalone = window === window.top;
        let isLight = true; // Default to light

        if (!isStandalone) {
            try {
                // Try to get theme from parent
                const parentTheme = window.parent.document.documentElement.getAttribute('data-theme');
                isLight = parentTheme === 'light';
                // If parent has no theme yet, it might be loading. Fallback to light or dark? 
                // User said "When in the website and in light mode it should be white". 
                // "When in the website... dark mode... Should be black".
                if (!parentTheme) isLight = true; // Default light if undetermined
            } catch (e) {
                // Cross-origin or other error: fallback to system preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    isLight = false;
                }
            }
        } else {
             // Standalone: Check system preference
             if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                 isLight = false;
             } else {
                 isLight = true;
             }
        }

        if (isLight) {
            document.documentElement.classList.remove('dark-mode');
        } else {
            document.documentElement.classList.add('dark-mode');
        }
    }

    // Redirect logic (Bonus)
    if (window === window.top) {
        // Redirect to main site with this page as content
        // This puts the standalone page into the iframe wrapper
        const currentUrl = window.location.pathname + window.location.search;
        window.location.href = '/?url=' + encodeURIComponent(currentUrl);
    }

    // Initial check
    document.addEventListener('DOMContentLoaded', updateTheme);

    // Watch for changes in parent attribute
    if (window !== window.top) {
        try {
            const observer = new MutationObserver(updateTheme);
            observer.observe(window.parent.document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
        } catch (e) {
            console.log('Cannot observe parent theme due to security/structure.');
        }
    }

    function downloadFile(url, filename) {
        const filenameWithSpaces = filename.replace(/\+/g, ' ');
        const decodedFilename = decodeURIComponent(filenameWithSpaces);
        // JS logging removed as it relies on dead port 3000. Logging now handled server-side in monitorFile.php.

        // Create a temporary link and trigger a click to start the download
        const link = document.createElement('a');
        link.href = '/php/monitorFile.php?download="' + decodedFilename + '"&u=' + encodeURIComponent(url);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

</script>
</head>
EOF;

    $directory = rtrim($directory, '/');

    // Start the HTML output
    echo "<h2>Directory: $directoryName</h2>";
    echo "<table border='1'>";

    // Conditionally add the "Expires In" column header
    if ($directory === tmpDirectory) {
        echo "<thead><tr><th>Name</th><th class='type-column'>Type</th><th class='size-column'>Size</th><th class='expires-column'>Expires In</th><th class='actions-column'>Actions</th></tr></thead>";
    } else {
        echo "<thead><tr><th>Name</th><th class='type-column'>Type</th><th class='size-column'>Size</th><th class='actions-column'>Actions</th></tr></thead>";
    }
    echo "<tbody>";

    if ($directory == realpath(documentRoot)) {
        echo "Can't view root directory";
        exit;
    }

    // Handle parent directory link if not to the root.
    if ($directory != realpath(documentRoot)) {
        $parentDir = dirname($directory);

        if (!empty(substr($parentDir, strlen(realDocumentRoot)))) {
            echo "<tr>";
            echo "<td><a href='?dir=" . urlencode(substr($parentDir, strlen(realDocumentRoot))) . "'>..</a></td>";
            echo "<td class='type-column'><img src=\"" . dirImg . "\"></td>";
            echo "<td class='size-column'></td>";
            // Conditionally add an empty cell for "Expires In"
            if ($directory === tmpDirectory) {
                echo "<td class='expires-column'></td>";
            } // IF
            echo "<td class='actions-column'></td>";
            echo "</tr>";
        } // if
    } // if

    // Scan the directory.
    $items = scandir($directory);

    // Iterate through the items.
    foreach ($items as $item) {
        // Skip hidden files/directories if not showing them.
        if (!$showHidden && substr($item, 0, 1) === '.') {
            continue;
        }

        // Skip the index.php file.
        if ($item == 'index.php' || $item == 'playback.log') {
            continue;
        }

        // Construct the full path and URL.
        $fullPath = $directory . '/' . $item;

        // Get file/directory information.
        $isDir = is_dir($fullPath);
        $size = $isDir ? '' : formatSize(filesize($fullPath));
        $expiresIn = $isDir ? '' : timeUntilExpiration($fullPath);

        $itemPath = substr($fullPath, strlen(realDocumentRoot));
        $dirPath = substr($fullPath, strlen(realDocumentRoot));

        $openLink = "/php/monitorFile.php?u=" . urlencode($itemPath);

        echo "<tr>";
        echo "<td>";
        if ($isDir) {
            echo '<a href=/php/downloaddir.php?dir=' . urlencode($dirPath) . ">$item</a>";
        } else {
            echo "<a href='" . $openLink . "'>" . $item . "</a>";
        }
        echo "</td>";

        // Display the type.
        echo "<td class='type-column'><img src=\"" . determineType($fullPath) . "\"></td>";

        // Display the size.
        echo "<td class='size-column'>$size</td>";

        // Conditionally display the expiration time
        if ($directory === tmpDirectory) {
            echo "<td class='expires-column'>$expiresIn</td>";
        }

        // Display the actions.
        echo "<td class='actions-column'>";
        if (!$isDir) {
            echo "<div class=\"button-container\">";

            echo '<button class="button open-button">';
            echo "<a href=\"$openLink\">";
            echo '<span class="icon open-icon"><font size=+2>&#128451;</font></span> Open';
            echo "</a>";
            echo "</button>";

            echo '<button class="button download-button">';
            echo "<a href=\"#\" onclick=\"downloadFile('" . $_SERVER['REQUEST_URI'] . '/'
                . str_replace("'", "\\'", $item) . "', '" . urlencode($item) . "', '"
                . str_replace("'", "\\'", $item) . "'); return false;\">";
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

/**
 * Calculates the time until a file expires, assuming a 7-day expiration from the last access.
 *
 * @param string $filePath The path to the file.
 * @return string A string representing the time until expiration (e.g., "5 days", "1 hour", "Expired").
 */
function timeUntilExpiration(string $filePath): string
{
    // Check if the file exists
    if (!file_exists($filePath)) {
        return "File not found";
    }

    // Get the last access time of the file.
    $lastAccessTime = fileatime($filePath);

    // If fileatime fails, use filemtime as a fallback
    if ($lastAccessTime === false) {
        $lastAccessTime = filemtime($filePath);
    }

    if ($lastAccessTime === false) {
        return "Error getting access time";
    }

    // Calculate the expiration time (7 days from last access).
    $expirationTime = $lastAccessTime + (7 * 24 * 60 * 60); // 7 days in seconds

    // Get the current time.
    $currentTime = time();

    // Calculate the time difference.
    $timeDifference = $expirationTime - $currentTime;

    // Handle expired files.
    if ($timeDifference <= 0) {
        return "Expired";
    }

    // Format the time difference into a human-readable string.
    $days = floor($timeDifference / (60 * 60 * 24));
    $hours = floor(($timeDifference % (60 * 60 * 24)) / (60 * 60));
    $minutes = floor(($timeDifference % (60 * 60)) / 60);

    $formattedTime = "Expires in ";
    if ($days > 0) {
        $formattedTime .= $days . " day" . ($days > 1 ? "s" : "");
    } elseif ($hours > 0) {
        $formattedTime .= $hours . " hour" . ($hours > 1 ? "s" : "");
    } elseif ($minutes > 0) {
        $formattedTime .= $minutes . " minute" . ($minutes > 1 ? "s" : "");
    } else {
        $formattedTime .= "less than a minute";
    }

    return $formattedTime;
}

// Main
if (isset($_GET['dir'])) {
    $directory = $_GET['dir'];
} else {
    $directory = documentRoot;

    $parsedUrl = parse_url($_SERVER['REQUEST_URI']);

    // Check if parsing was successful and if a path exists
    if ($parsedUrl !== false && isset($parsedUrl['path'])) {
        $directory .= substr($parsedUrl['path'], 1);
    }
} // if

// Decode the directory path to handle spaces and other special characters
$directory = urldecode($directory);

if (strpos($directory, documentRoot) !== 0) {
    $directory = '/web' . $directory;
}

if ($directory === realpath(documentRoot)) {
    echo "Unable to browse the root directory";
    exit;
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
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
$baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];
if (isset($directory)) {
    $baseUrl .= '/' . ltrim($directory, '/');
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>
        <?php
        // Strip realDocumentRoot if present
        if (strpos($directory, realDocumentRoot) === 0) {
            echo "Directory: " . substr($directory, strlen(realDocumentRoot));
        } elseif (strpos($directory, '/web//') === 0) {
            echo "Directory: " . substr($directory, strlen('/web//'));
        } else if ($directory === '/web//tmp/' || $directory === '/web/tmp/') {
            echo "Root Directory";
        } else {
            echo "Directory: {$directory}";
        }
        ?>
    </title>
    <meta charset="utf-8">
</head>

<body>

    <?php
    generateDirectoryListing($directory, $baseUrl);
    ?>

</body>

</html>