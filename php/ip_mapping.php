<?php
// Define the path to the IP mapping file
$ipMappingFile = realpath(__DIR__ . '/../pm/ips');

/**
 * Resolves the external IP address for defaria.synology.me.
 *
 * @return string The resolved IP address or empty string on failure.
 */
if (!function_exists('getHomeIp')) {
    function getHomeIp(): string
    {
        $host = 'defaria.synology.me';
        $ip = gethostbyname($host);

        if ($ip !== $host && $ip !== '127.0.0.1' && $ip !== '127.0.1.1') {
            return $ip;
        }

        $aRecords = @dns_get_record($host, DNS_A);
        if (!empty($aRecords) && isset($aRecords[0]['ip'])) {
            return $aRecords[0]['ip'];
        }

        return '';
    }
}

/**
 * Checks if the current HTTP request comes from a link-preview or social crawler bot.
 *
 * @return bool True if the User-Agent matches known link preview bots.
 */
if (!function_exists('isPreviewBot')) {
    function isPreviewBot(): bool
    {
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if ($ua === '') {
            return false;
        }
        $bots = [
            'facebookexternalhit',
            'Facebot',
            'Twitterbot',
            'Slackbot',
            'WhatsApp',
            'Discordbot',
            'TelegramBot',
            'LinkedInBot',
            'Applebot',
            'Pinterest',
            'SkypeUriPreview',
        ];
        foreach ($bots as $bot) {
            if (stripos($ua, $bot) !== false) {
                return true;
            }
        }
        return false;
    }
}

/**
 * Loads the IP mapping from the specified file.
 *
 * @param string $filePath The path to the IP mapping file.
 * @return array An associative array where keys are IP addresses and values are their corresponding text descriptions.
 */
function loadIpMapping(string $filePath): array
{
    $ipMapping = [];

    if (file_exists($filePath) && is_readable($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignore comment lines
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Split the line into IP and text
            $parts = preg_split('/\s+/', $line, 2);

            if (count($parts) === 2) {
                $ipMapping[$parts[0]] = $parts[1];
            }
        }
    }

    $homeIp = getHomeIp();
    if ($homeIp && !isset($ipMapping[$homeIp])) {
        $ipMapping[$homeIp] = 'Andrew DeFaria (Home LAN)';
    }

    return $ipMapping;
}

/**
 * Replaces the IP address with its corresponding text from the mapping, if available.
 *
 * @param string $ip The IP address to look up.
 * @param array $ipMapping The IP mapping array.
 * @return string The text description if found, otherwise the original IP address.
 */
function replaceIpWithText(string $ip, array $ipMapping): string
{
    return $ipMapping[$ip] ?? $ip;
}
?>