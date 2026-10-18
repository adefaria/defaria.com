<?php
// Use __DIR__ and realpath() to construct the absolute path
require_once realpath(__DIR__ . '/ip_mapping.php');

$IPAddr = $_SERVER["REMOTE_ADDR"];
$download = isset($_GET['download']) ? $_GET['download'] : null;

// Define the filesystem path to the web server's document root.
$fsWebRoot = '/web'; // Set to your actual web root path

if (!$fsWebRoot) {
    // Fallback or error if $_SERVER['DOCUMENT_ROOT'] is not set or invalid
    die("Error: Could not determine web root filesystem path.");
}

function debug($message)
{
    echo "<font color='red'>$message</font><br>";
}

/**
 * Gets the HTTP address (URL) of the document root.
 *
 * @return string The HTTP address of the document root (e.g., "https://www.example.com/").
 */
function getDocumentRootHttpAddress(): string
{
    // Determine the protocol (http or https).
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';

    // Get the host (domain name or IP address).
    $host = $_SERVER['HTTP_HOST'] ?? null;

    if (!$host) {
        // Handle the case where the host is not available.
        // This might happen in CLI scripts or unusual server configurations.
        return "Unable to determine document root HTTP address.";
    }

    // Construct the document root HTTP address.
    $documentRootHttpAddress = $protocol . '://' . $host . '/';

    return $documentRootHttpAddress;
}

/**
 * Generates a full URL from a relative URL and the current request context.
 *
 * @param string $relativeUrl The relative URL (e.g., "path/to/resource", "../another/resource", "resource.php?param=value").
 * @return string The full URL, or null if the full URL could not be determined.
 */
function getFullUrl(string $relativeUrl): ?string
{
    // Check if the input is already a full URL.
    if (preg_match('/^(http|https):\/\//', $relativeUrl)) {
        return $relativeUrl; // It's already a full URL.
    }

    // Get the current request's protocol, host, and directory.
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? null;
    $requestUri = $_SERVER['REQUEST_URI'] ?? null;

    if (!$host || !$requestUri) {
        return null; // Unable to determine base URL components.
    }

    // Determine the base directory of the current request.
    $pathParts = pathinfo($requestUri);
    $basePath = $pathParts['dirname'];

    // Handle root path
    if ($basePath == "\\") {
        $basePath = "";
    }

    // Build the base URL.
    $baseUrl = $protocol . '://' . $host . $basePath;

    // Handle relative URL parts (e.g., "..", ".").
    $relativeParts = explode('/', $relativeUrl);
    $baseParts = explode('/', $baseUrl);
    $resultParts = $baseParts;

    // Check if the relative URL starts with a slash
    if (strpos($relativeUrl, '/') == 0) {
        $absUrl = substr($relativeUrl, 1);
        return getDocumentRootHttpAddress() . substr($relativeUrl, 1);
    } else {
        // If it doesn't start with a slash, append it to the referrer URL
        // Get the referrer URL
        return $_SERVER['HTTP_REFERER'] . $relativeUrl;
    } // if
}

// --- Main ---
$URL = "";
$path = null; // Initialize $path to null

$myip = getHomeIp();

// Load the IP mapping
$ipMapping = loadIpMapping($ipMappingFile);

$displayValue = replaceIpWithText($IPAddr, $ipMapping);

if (isset($_GET['u'])) {
    $URL = $_GET['u'];

    // The 'u' parameter is expected to be the web-relative path starting with '/'
    if (empty($URL) || $URL[0] !== '/') {
        header("HTTP/1.0 400 Bad Request");
        echo "Invalid URL format.";
        exit;
    }

    // Construct the potential filesystem path by prepending the document root
    $potentialPath = $fsWebRoot . $URL;

    // Resolve the real path and perform security check
    $realPath = $potentialPath;

    // Security check: Ensure the resolved path exists, is a file, and is within the document root
    if ($realPath === false || !is_file($realPath) || strpos($realPath, $fsWebRoot) !== 0) {
        header("HTTP/1.0 404 Not Found");
        echo "File not found or access denied.";
        exit;
    }

    // Use the validated and resolved path
    $path = $realPath;

    // Now, $path holds the correct absolute filesystem path (e.g., /opt/defaria.com/web/tmp/Something.mp3)
    // And $URL holds the correct web-relative path (e.g., /web/tmp/Something.mp3)
    // The rest of the script can proceed using $path for file operations and $URL for display/logging.

} else {
    echo "No URL passed in";
    exit;
} // if

function log_playback($msg, $ip)
{
    $logFile = '/web/pm/playback.log';
    $timestamp = date('H:i:s');
    $entry = "$timestamp [$ip] $msg\n";
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

$isPreview = isPreviewBot();

// Check if the download parameter is set in $_GET
if (isset($download)) {
    $filename = urldecode($_GET['download']);
    $trimmedFilename = substr($filename, 1, -1);

    if (!$isPreview) {
        // Log the download action to playback.log
        $logMessage = "Downloaded " . $trimmedFilename;
        log_playback($logMessage, $displayValue);
    }

    // Set headers for file download.
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . trim($trimmedFilename) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit; // Important: Stop further execution after sending the file
}

// Require media helper functions
require_once realpath(__DIR__ . '/media_functions.php');

// Determine file type based on extension and send redirect immediately
// so slow whois/mail lookups do not stall playback or expire iOS user gestures.
$fileExtension = strtolower(pathinfo($URL, PATHINFO_EXTENSION));
$isVideoFile = in_array($fileExtension, ['mp4', 'webm', 'ogv', 'mkv', 'mov'], true);
$isAudioFile = in_array($fileExtension, ['m4a', 'mp3', 'wav', 'ogg', 'flac', 'aac'], true);
$isImageFile = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'], true);

if (isset($_GET['bg'])) {
    http_response_code(204);
} elseif ($isVideoFile) {
    header("Location: /php/videoplayback.php?video=" . urlencode($URL));
} elseif ($isAudioFile) {
    header("Location: /php/audioplayback.php?audio=" . urlencode($URL));
} elseif ($isImageFile) {
    header("Location: /php/imageview.php?image=" . urlencode($URL));
} else {
    header("Location: " . $URL);
}

if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

$msg = '<html><body>';

$fullURL = getFullUrl($URL);

if ($displayValue == $IPAddr) { // Corrected variable casing displayValue vs displayvalue
    $msg .= "<h1>Somebody accessed $URL</h1>";
} else {
    $msg .= "<h1>$displayValue accessed $URL</h1>";
}

$msg .= "<p>Full URL: $fullURL</p>";
$msg .= "<p>Here's what I know about them:</p>";

$isHomeLan = (
    ($myip && $IPAddr === $myip) ||
    $IPAddr === '127.0.0.1' ||
    $IPAddr === '::1' ||
    str_starts_with($IPAddr, '192.168.') ||
    str_starts_with($IPAddr, '10.') ||
    (isset($ipMapping[$IPAddr]) && (str_contains($ipMapping[$IPAddr], 'Home LAN') || str_contains($ipMapping[$IPAddr], 'Andrew DeFaria'))) ||
    str_contains($displayValue, 'Home LAN') ||
    str_contains($displayValue, 'Andrew DeFaria')
);

$me = $isHomeLan;

if (!$me && !$isPreview) {
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $msg .= "HTTP_REFERER: " . htmlspecialchars($_SERVER['HTTP_REFERER']) . "<br>";
    } else {
        $msg .= "HTTP_REFERER: URL Typed<br>";
    }

    foreach ($_SERVER as $key => $value) {
        if (preg_match("/^REMOTE/", $key) || preg_match("/^HTTP_USER_AGENT/", $key)) {
            $msg .= "$key: $value<br>";

            if ($key == 'REMOTE_ADDR') {
                exec("whois $value", $output, $result);

                foreach ($output as $line) {
                    $msg .= "$line<br>";
                } // foreach
            } // if
        } // if
    } // foreach

    $histfile = fopen('/web/pm/.history', 'a');
    $date = date(DATE_RFC822);

    // Only log non-audio/video accesses here; audio/video files log "Started <file>" when playback actually begins
    if (!$isAudioFile && !$isVideoFile) {
        if ($download) {
            $verb = "Downloading";
        } elseif ($isImageFile) {
            $verb = "Viewing";
        } else {
            $verb = "Opening";
        }
        $logMessage = "$verb " . basename($URL);
        log_playback($logMessage, $displayValue);
    }

    // Logging to history file
    $accessVerb = $download ? 'downloaded' : 'accessed';
    fwrite($histfile, "$_SERVER[REMOTE_ADDR] $accessVerb $URL $date\n");
    fclose($histfile);

    $msg .= '</body></html>';

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "From: WebMonitor <WebMonitor@DeFaria.com>";

    if ($displayValue == $IPAddr) {
        $subject = "Somebody just ";
    } else {
        $subject = "$displayValue just ";
    } // if

    if ($download) {
        $subject .= "downloaded $URL";
    } else {
        $subject .= "accessed $URL";
    } // if

    // Replace IP address in the email subject and body with text from the mapping
    $displayIP = replaceIpWithText($IPAddr, $ipMapping);
    $subject = str_replace($IPAddr, $displayIP, $subject);
    $msg = str_replace("REMOTE_ADDR: $_SERVER[REMOTE_ADDR]", "REMOTE_ADDR: $displayIP", $msg);

    mail("andrew@defaria.com", $subject, $msg, $headers);
} // if

exit;
?>
