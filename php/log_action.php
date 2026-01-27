<?php
// log_action.php
// Receives JSON POST data from playback pages to log events
header('Content-Type: application/json');

$logFile = '/web/pm/playback.log';
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data) {
    $ip = $data['IPAddr'] ?? $_SERVER['REMOTE_ADDR'];
    $msg = $data['msg'] ?? '';

    if ($msg) {
        $timestamp = date('H:i:s');
        $entry = "$timestamp [$ip] $msg\n";

        // Atomic append
        if (file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX) !== false) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Write failed']);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'No message to log']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
}
?>