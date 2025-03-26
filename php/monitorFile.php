<?php

$IPAddr = $_SERVER["REMOTE_ADDR"];

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

// Main
$URL = "";

if (isset($_GET['url'])) {
    $URL = $_GET['url'];
    debug("URL: $URL");

    $fullURL = getFullUrl($URL);
    debug("Full URL: $fullURL");
    // Validate that the url is in the same domain
    $parsedUrl = parse_url($fullURL);

    $host = $_SERVER['HTTP_HOST'];
    if (!isset($parsedUrl['host']) || $parsedUrl['host'] !== $host) {
        echo "Invalid URL";
        exit();
    }
    $path = $_SERVER['DOCUMENT_ROOT'] . $parsedUrl['path'];
    debug("Path: $path");
} else {
    echo "No URL passed in";
    exit;
} // if

if (!file_exists($path)) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

// Check if the download parameter is set in $_GET
if (isset($_GET['download'])) {
    debug("download parameter set");
    // Set headers for file download.
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $_GET['download'] . '');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    debug("path: {$path}");
    readfile($path);
    exit; // Important: Stop further execution after sending the file
}

$msg = '<html><body>';
$msg .= "<h1>Somebody accessed $URL</h1>";
$msg .= "<p>Full URL: $fullURL</p>";
$msg .= "<p>Here's what I know about them:</p>";

$me = false;
$myip = '75.80.5.95';

foreach ($_SERVER as $key => $value) {
    if (preg_match("/^REMOTE/", $key)) {
        $msg .= "$key: $value<br>";

        if ($key == 'REMOTE_ADDR') {
            // Skip me...
            if ($value == $myip) {
                $me = true;
                break;
            } // if

            exec("whois $value", $output, $result);

            foreach ($output as $line) {
                $msg .= "$line<br>";
            } // foreach
        } // if
    } // if
} // foreach

if (!$me) {
    $histfile = fopen('/web/pm/.history', 'a');
    $date = date(DATE_RFC822);

    if ($download) {
        $access = "downloaded";
    } else {
        $access = "accessed";
    } // if

    fwrite($histfile, "$_SERVER[REMOTE_ADDR] $access $URL $date\n");
    fclose($histfile);

    $msg .= '</body></html>';

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "From: WebMonitor <WebMonitor@DeFaria.com>";

    if ($download) {
        $subject = "Somebody just downloaded $URL";
    } else {
        $subject = "Somebody just accessed $URL";
    } // if

    mail("andrew@defaria.com", $subject, $msg, $headers);
} else {
    $msg .= '</body></html>';

    $headers = "MIME-Version: 1.0\r\n";
    $subject = "";

    mail("andrew@defaria.com", $subject, $msg, $headers);
} // if

// Determine if it's a video or audio file based on extension
$fileExtension = pathinfo($path, PATHINFO_EXTENSION);

if (in_array(strtolower($fileExtension), ['mp4', 'webm', 'ogg', 'mkv'])) {
    header("Location: /php/videoplayback.php?video=$URL");
} elseif (in_array(strtolower($fileExtension), ['m4a', 'mp3', 'wav', 'ogg'])) {
    header("Location: /php/audioplayback.php?audio=$URL");
} else {
    header("Location: $URL");
} // if

exit;

?>