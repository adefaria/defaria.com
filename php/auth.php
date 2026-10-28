<?php
/**
 * Authentication and Access Control Engine for defaria.com
 * Handles user credentials, HMAC-signed session cookies, and allowlist routing.
 */

class SiteAuth {
    private static $configFile = '/opt/defaria.com/etc/auth_config.json';
    private static $usersFile = '/opt/defaria.com/etc/auth_users.json';
    private static $allowlistFile = '/opt/defaria.com/etc/public_routes.json';
    
    const COOKIE_NAME = 'defaria_auth';
    const SESSION_DURATION = 2592000; // 30 days in seconds

    /**
     * Get or initialize configuration containing the HMAC secret.
     */
    public static function getConfig() {
        if (!file_exists(self::$configFile)) {
            $defaultConfig = [
                'hmac_secret' => bin2hex(random_bytes(32)),
                'cookie_name' => self::COOKIE_NAME,
                'session_duration' => self::SESSION_DURATION,
                'created_at' => date('c')
            ];
            @mkdir(dirname(self::$configFile), 0775, true);
            file_put_contents(self::$configFile, json_encode($defaultConfig, JSON_PRETTY_PRINT));
            @chmod(self::$configFile, 0660);
            return $defaultConfig;
        }

        $content = file_get_contents(self::$configFile);
        return json_decode($content, true) ?: [];
    }

    /**
     * Load user store.
     */
    public static function getUsers() {
        if (!file_exists(self::$usersFile)) {
            return [];
        }
        $content = file_get_contents(self::$usersFile);
        return json_decode($content, true) ?: [];
    }

    /**
     * Add or update a user account.
     */
    public static function saveUser($username, $password, $displayName = '') {
        $users = self::getUsers();
        $username = strtolower(trim($username));
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        
        $users[$username] = [
            'username' => $username,
            'display_name' => $displayName ?: ucfirst($username),
            'password_hash' => $hash,
            'updated_at' => date('c')
        ];

        @mkdir(dirname(self::$usersFile), 0775, true);
        file_put_contents(self::$usersFile, json_encode($users, JSON_PRETTY_PRINT));
        @chmod(self::$usersFile, 0660);
        return true;
    }

    /**
     * Delete a user account.
     */
    public static function deleteUser($username) {
        $users = self::getUsers();
        $username = strtolower(trim($username));
        if (isset($users[$username])) {
            unset($users[$username]);
            file_put_contents(self::$usersFile, json_encode($users, JSON_PRETTY_PRINT));
            return true;
        }
        return false;
    }

    /**
     * Validate user credentials.
     */
    public static function authenticate($username, $password) {
        $users = self::getUsers();
        $username = strtolower(trim($username));
        if (!isset($users[$username])) {
            return false;
        }

        if (password_verify($password, $users[$username]['password_hash'])) {
            return $users[$username];
        }
        return false;
    }

    /**
     * Issue an HMAC-signed authentication cookie.
     */
    public static function setAuthCookie($username, $remember = true) {
        $config = self::getConfig();
        $secret = $config['hmac_secret'] ?? 'fallback_defaria_secret';
        $duration = $remember ? ($config['session_duration'] ?? self::SESSION_DURATION) : 0;
        $expiry = $remember ? (time() + $duration) : 0;
        
        // Payload: username:expiry:random_salt
        $salt = bin2hex(random_bytes(8));
        $expiryStr = (string)$expiry;
        $payload = base64_encode(json_encode([
            'u' => $username,
            'exp' => $expiry,
            'salt' => $salt
        ]));

        $signature = hash_hmac('sha256', $payload, $secret);
        $cookieValue = $payload . '.' . $signature;

        $cookieOptions = [
            'expires' => $expiry,
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ];

        setcookie(self::COOKIE_NAME, $cookieValue, $cookieOptions);
        $_COOKIE[self::COOKIE_NAME] = $cookieValue;
        return true;
    }

    /**
     * Clear the authentication cookie.
     */
    public static function clearAuthCookie() {
        setcookie(self::COOKIE_NAME, '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        unset($_COOKIE[self::COOKIE_NAME]);
    }

    /**
     * Verify an authentication cookie and return user info or false.
     */
    public static function verifyCookie($cookieValue) {
        if (empty($cookieValue) || strpos($cookieValue, '.') === false) {
            return false;
        }

        list($payload, $signature) = explode('.', $cookieValue, 2);
        $config = self::getConfig();
        $secret = $config['hmac_secret'] ?? '';

        $expectedSig = hash_hmac('sha256', $payload, $secret);
        if (!hash_equals($expectedSig, $signature)) {
            return false;
        }

        $data = json_decode(base64_decode($payload), true);
        if (!$data || !isset($data['u'])) {
            return false;
        }

        // Check expiration (0 means session cookie)
        if (!empty($data['exp']) && $data['exp'] < time()) {
            return false;
        }

        $users = self::getUsers();
        $username = strtolower($data['u']);
        if (isset($users[$username])) {
            return $users[$username];
        }

        return false;
    }

    /**
     * Get currently authenticated user, or null.
     */
    public static function getCurrentUser() {
        $cookie = $_COOKIE[self::COOKIE_NAME] ?? null;
        if (!$cookie) {
            return null;
        }
        return self::verifyCookie($cookie) ?: null;
    }

    /**
     * Check if a path is on the public allowlist.
     */
    public static function isPublicPath($uri) {
        // Strip query string and fragments
        $path = parse_url($uri, PHP_URL_PATH);
        if (!$path) {
            $path = '/';
        }

        // Canonical clean path
        $path = '/' . ltrim($path, '/');

        // Check allowlist file
        if (!file_exists(self::$allowlistFile)) {
            // Failsafe: root and login are always public
            return in_array($path, ['/', '/index.php', '/welcome.php', '/login.php']);
        }

        static $allowlist = null;
        if ($allowlist === null) {
            $allowlist = json_decode(file_get_contents(self::$allowlistFile), true) ?: [];
        }

        $lowerPath = strtolower($path);

        // Check explicit private paths first (override)
        if (!empty($allowlist['explicit_private_paths'])) {
            foreach ($allowlist['explicit_private_paths'] as $privatePath) {
                $lowerPrivate = strtolower($privatePath);
                if ($path === $privatePath || $lowerPath === $lowerPrivate ||
                    str_starts_with($path, rtrim($privatePath, '/') . '/') ||
                    str_starts_with($lowerPath, rtrim($lowerPrivate, '/') . '/')) {
                    return false;
                }
            }
        }

        // Check public prefixes (e.g., /css/, /JavaScript/, /Icons/, /auth/, /Media/, /Band/, /tmp/)
        if (!empty($allowlist['public_prefixes'])) {
            foreach ($allowlist['public_prefixes'] as $prefix) {
                if (str_starts_with($path, $prefix) || str_starts_with($lowerPath, strtolower($prefix))) {
                    return true;
                }
            }
        }

        // Check exact public paths
        if (!empty($allowlist['public_exact_paths'])) {
            static $lowerExactMap = null;
            if ($lowerExactMap === null) {
                $lowerExactMap = array_map('strtolower', $allowlist['public_exact_paths']);
            }

            // Check exact, plus trailing slash variation, plus clean URL variations
            if (in_array($path, $allowlist['public_exact_paths'], true) || in_array($lowerPath, $lowerExactMap, true)) {
                return true;
            }
            if (str_ends_with($path, '/') && (in_array(rtrim($path, '/'), $allowlist['public_exact_paths'], true) || in_array(rtrim($lowerPath, '/'), $lowerExactMap, true))) {
                return true;
            }
            if (!str_ends_with($path, '/') && (in_array($path . '/', $allowlist['public_exact_paths'], true) || in_array($lowerPath . '/', $lowerExactMap, true))) {
                return true;
            }
            if (str_ends_with($path, '.php') && (in_array(substr($path, 0, -4), $allowlist['public_exact_paths'], true) || in_array(substr($lowerPath, 0, -4), $lowerExactMap, true))) {
                return true;
            }
            if (!str_contains($path, '.') && (in_array($path . '.php', $allowlist['public_exact_paths'], true) || in_array($lowerPath . '.php', $lowerExactMap, true))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Comprehensive access check for a request.
     * Returns true if access is granted, false if rejected (401).
     */
    public static function verifyAccess($uri, $cookieValue = null) {
        // 1. If public by allowlist, allow access unconditionally
        if (self::isPublicPath($uri)) {
            return true;
        }

        // 2. If private, check valid authentication cookie
        if (!empty($cookieValue) && self::verifyCookie($cookieValue)) {
            return true;
        }

        return false;
    }
}
