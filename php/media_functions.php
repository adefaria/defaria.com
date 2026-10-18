<?php
/**
 * Media helper functions for video, audio, and image playback.
 */

function getSupportedAudioExtensions(): array
{
    return ['mp3', 'm4a', 'wav', 'ogg', 'flac', 'aac'];
}

function getSupportedVideoExtensions(): array
{
    return ['mp4', 'webm', 'ogv', 'mkv', 'mov'];
}

function getSupportedImageExtensions(): array
{
    return ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
}

/**
 * Get array of supported media extensions.
 */
function getSupportedMediaExtensions(): array
{
    return array_merge(
        getSupportedVideoExtensions(),
        getSupportedAudioExtensions(),
        getSupportedImageExtensions()
    );
}

/**
 * Get MIME type for audio file.
 */
function getAudioMimeType(string $url): string
{
    $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'mp3':
            return 'audio/mpeg';
        case 'flac':
            return 'audio/flac';
        case 'm4a':
            return 'audio/mp4';
        case 'wav':
            return 'audio/wav';
        case 'ogg':
            return 'audio/ogg';
        case 'aac':
            return 'audio/aac';
        default:
            return 'audio/' . $ext;
    }
}

/**
 * Get display title without file extension.
 */
function getMediaTitle(string $url): string
{
    return pathinfo(basename($url), PATHINFO_FILENAME);
}

/**
 * Strip media extension if the file has a known media extension.
 */
function stripMediaExtension(string $filename): string
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mediaExtensions = [
        'mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a',
        'mp4', 'avi', 'mkv', 'mov', 'webm', 'flv', 'wmv', 'ogv',
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp',
    ];

    if (in_array($extension, $mediaExtensions, true)) {
        return pathinfo($filename, PATHINFO_FILENAME);
    }

    return $filename;
}


/**
 * Get description HTML content if <mediafile>.html exists in the same directory.
 *
 * @param string $currentUrl Web-relative path (e.g., "/Media/Music/song1.mp3")
 * @return string HTML content or empty string if not found.
 */
function getMediaDescription(string $currentUrl): string
{
    $fsWebRoot = '/web';
    if (empty($currentUrl) || $currentUrl[0] !== '/') {
        return '';
    }

    $realPath = realpath($fsWebRoot . $currentUrl);
    if (!$realPath || !is_file($realPath)) {
        return '';
    }

    $dir = dirname($realPath);
    $filenameStem = pathinfo($realPath, PATHINFO_FILENAME);
    $htmlFile = $dir . '/' . $filenameStem . '.html';

    if (is_file($htmlFile) && is_readable($htmlFile)) {
        return file_get_contents($htmlFile) ?: '';
    }

    return '';
}

/**
 * Checks if a file in directory is an HTML description file for a media file in the same directory.
 *
 * @param string $directory Absolute filesystem path to directory
 * @param string $filename File name in directory
 * @return bool True if <mediafile>.html exists for a media file in the same directory
 */
function isMediaDescriptionFile(string $directory, string $filename): bool
{
    if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'html') {
        return false;
    }

    $stem = pathinfo($filename, PATHINFO_FILENAME);
    $supported = getSupportedMediaExtensions();

    foreach ($supported as $ext) {
        $candidate = $directory . '/' . $stem . '.' . $ext;
        if (is_file($candidate)) {
            return true;
        }
    }

    return false;
}

/**
 * Returns previous and next media monitorFile URLs in the same directory.
 *
 * @param string $currentUrl Web-relative path
 * @return array Array with 'prev' and 'next' keys containing monitorFile URLs or empty strings.
 */
function getMediaNavigation(string $currentUrl): array
{
    $fsWebRoot = '/web';
    $result = ['prev' => '', 'next' => ''];

    if (empty($currentUrl) || $currentUrl[0] !== '/') {
        return $result;
    }

    $realPath = realpath($fsWebRoot . $currentUrl);
    if (!$realPath || !is_file($realPath)) {
        return $result;
    }

    $dir = dirname($realPath);
    $webDir = dirname($currentUrl);
    if ($webDir === '\\' || $webDir === '.' || $webDir === '/') {
        $webDir = '';
    }

    $supported = getSupportedMediaExtensions();

    $files = @scandir($dir);
    if (!$files) {
        return $result;
    }

    $mediaFiles = [];
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $fullPath = $dir . '/' . $file;
        if (is_file($fullPath)) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $supported, true)) {
                $mediaFiles[] = $file;
            }
        }
    }

    natcasesort($mediaFiles);
    $mediaFiles = array_values($mediaFiles);

    $currentFileName = basename($realPath);
    $currentIndex = array_search($currentFileName, $mediaFiles, true);

    if ($currentIndex !== false) {
        if (isset($mediaFiles[$currentIndex - 1])) {
            $prevFile = $mediaFiles[$currentIndex - 1];
            $prevWebPath = ($webDir === '' ? '' : $webDir) . '/' . $prevFile;
            $result['prev'] = "/php/monitorFile.php?u=" . urlencode($prevWebPath);
        }
        if (isset($mediaFiles[$currentIndex + 1])) {
            $nextFile = $mediaFiles[$currentIndex + 1];
            $nextWebPath = ($webDir === '' ? '' : $webDir) . '/' . $nextFile;
            $result['next'] = "/php/monitorFile.php?u=" . urlencode($nextWebPath);
        }
    }

    return $result;
}

/**
 * Backward compatible function for next URL only.
 */
function getNextMediaUrl(string $currentUrl): string
{
    $nav = getMediaNavigation($currentUrl);
    return $nav['next'];
}
