<?php
/**
 * getLogData.php
 *
 * This script fetches the last N lines from a log file and returns them as a JSON response.
 *
 * Parameters:
 *   - lines: The number of lines to retrieve (default: 10).
 *
 * Returns:
 *   - JSON object with the following structure:
 *     {
 *       "lines": [
 *         "line 1",
 *         "line 2",
 *         ...
 *       ],
 *       "error": null
 *     }
 *   - Or, if an error occurs:
 *     {
 *       "lines": null,
 *       "error": "Error message"
 *     }
 */

// Set the log file path.
$logFile = '/web/pm/playback.log';

// Set the default number of lines to retrieve.
$defaultLines = 10;

// Get the number of lines to retrieve from the query string.
$numLines = isset($_GET['lines']) ? intval($_GET['lines']) : $defaultLines;

// Validate the number of lines.
if ($numLines <= 0) {
    $numLines = $defaultLines;
}

// Check if the log file exists and is readable.
if (!file_exists($logFile) || !is_readable($logFile)) {
    // Return an error response.
    header('Content-Type: application/json');
    echo json_encode([
        'lines' => null,
        'error' => "Log file '$logFile' does not exist or is not readable.",
    ]);
    exit;
}

// Read the log file into an array of lines.
$lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Check if file() returned false (error reading the file).
if ($lines === false) {
    header('Content-Type: application/json');
    echo json_encode([
        'lines' => null,
        'error' => "Error reading log file '$logFile'.",
    ]);
    exit;
}

// Get the last N lines.
$lastLines = array_slice($lines, -$numLines);

// Return the lines as a JSON response.
header('Content-Type: application/json');
echo json_encode([
    'lines' => $lastLines,
    'error' => null,
]);

?>