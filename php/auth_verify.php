<?php
/**
 * Fast auth verification endpoint for Nginx auth_request module.
 * Returns 200 OK if allowed, 401 Unauthorized if denied.
 */

// Disable all error output that could corrupt HTTP response codes
ini_set('display_errors', '0');
error_reporting(0);

require_once __DIR__ . '/auth.php';

// Retrieve the original requested URI
$targetUri = $_SERVER['HTTP_X_ORIGINAL_URI'] 
    ?? $_SERVER['X_ORIGINAL_URI'] 
    ?? $_SERVER['REQUEST_URI'] 
    ?? '/';

// Retrieve cookie value
$cookieValue = $_COOKIE[SiteAuth::COOKIE_NAME] ?? null;

// Fallback manual cookie parser if $_COOKIE is empty in fastcgi subrequest
if (!$cookieValue && !empty($_SERVER['HTTP_COOKIE'])) {
    if (preg_match('/(?:^|;\s*)' . preg_quote(SiteAuth::COOKIE_NAME, '/') . '=([^;]+)/', $_SERVER['HTTP_COOKIE'], $matches)) {
        $cookieValue = urldecode($matches[1]);
    }
}

// Perform verification
if (SiteAuth::verifyAccess($targetUri, $cookieValue)) {
    http_response_code(200);
    header('X-Auth-Status: Authorized');
    exit;
} else {
    http_response_code(401);
    header('X-Auth-Status: Denied');
    exit;
}
