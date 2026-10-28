<?php
require_once __DIR__ . '/php/auth.php';

SiteAuth::clearAuthCookie();

$redirect = $_GET['redirect'] ?? '/';
$redirectPath = parse_url($redirect, PHP_URL_PATH) ?: '/';

header('Location: ' . $redirectPath);
exit;
