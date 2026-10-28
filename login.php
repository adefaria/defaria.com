<?php
$page_title = "Log In";
require_once __DIR__ . '/php/auth.php';

$error = '';
$redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? '/';

// Sanitize redirect path to prevent open redirect vulnerabilities
$redirectPath = parse_url($redirect, PHP_URL_PATH) ?: '/';
$redirectQuery = parse_url($redirect, PHP_URL_QUERY);
$safeRedirect = $redirectPath . ($redirectQuery ? '?' . $redirectQuery : '');
// If redirect points to login or logout, reset to /
if (in_array($redirectPath, ['/login.php', '/login', '/logout.php', '/logout'])) {
    $safeRedirect = '/';
}

$currentUser = SiteAuth::getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';

    if ($action === 'logout') {
        SiteAuth::clearAuthCookie();
        header('Location: /login.php');
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = !empty($_POST['remember']);

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user = SiteAuth::authenticate($username, $password);
        if ($user) {
            SiteAuth::setAuthCookie($username, $remember);
            // If in iframe and redirecting to /, we can notify top or redirect
            header("Location: " . $safeRedirect);
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

include 'includes/frame_header.php';
?>

<main class="container" style="max-width: 480px; margin: 40px auto; padding: 20px;">
    <div class="auth-card">
        <div class="auth-header">
            <div class="lock-icon-circle">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h1 class="auth-title">Authentication Required</h1>
            <p class="auth-subtitle">
                <?php if ($currentUser): ?>
                    You are currently authenticated as <strong><?php echo htmlspecialchars($currentUser['display_name']); ?></strong>.
                <?php else: ?>
                    Please log in to access protected pages on defaria.com.
                <?php endif; ?>
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="auth-alert error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($currentUser): ?>
            <div style="text-align: center; margin-top: 25px;">
                <a href="<?php echo htmlspecialchars($safeRedirect); ?>" class="btn-primary" style="display: block; margin-bottom: 12px; text-decoration: none;">
                    Continue to <?php echo htmlspecialchars($safeRedirect); ?>
                </a>
                <form method="post" action="/login.php">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="btn-secondary" style="width: 100%;">Log Out</button>
                </form>
            </div>
        <?php else: ?>
            <form method="post" action="/login.php" class="auth-form">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($safeRedirect); ?>">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autocomplete="username" autofocus
                           placeholder="Enter your username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           placeholder="Enter your password">
                </div>

                <div class="form-options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1" checked>
                        <span>Remember this device (30 days)</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">
                    Log In
                </button>
            </form>
        <?php endif; ?>
    </div>
</main>

<style>
.auth-card {
    background: var(--surface-color);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    padding: 35px 30px;
}
[data-theme="light"] .auth-card {
    border: 1px solid rgba(0, 0, 0, 0.1);
}
.auth-header {
    text-align: center;
    margin-bottom: 25px;
}
.lock-icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(66, 133, 244, 0.15);
    color: var(--google-blue);
    margin-bottom: 15px;
}
.auth-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: var(--text-color);
}
.auth-subtitle {
    font-size: 0.95rem;
    color: var(--muted-color);
    margin: 0;
}
.auth-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: var(--radius-md);
    margin-bottom: 20px;
    font-size: 0.9rem;
}
.auth-alert.error {
    background: rgba(234, 67, 53, 0.15);
    color: #ff6b6b;
    border: 1px solid rgba(234, 67, 53, 0.3);
}
.auth-form .form-group {
    margin-bottom: 18px;
}
.auth-form label {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 6px;
    color: var(--text-color);
}
.auth-form input[type="text"],
.auth-form input[type="password"] {
    width: 100%;
    box-sizing: border-box;
    padding: 12px 14px;
    border-radius: var(--radius-md);
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: var(--input-bg);
    color: var(--text-color);
    font-size: 1rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
[data-theme="light"] .auth-form input[type="text"],
[data-theme="light"] .auth-form input[type="password"] {
    border: 1px solid rgba(0, 0, 0, 0.15);
}
.auth-form input[type="text"]:focus,
.auth-form input[type="password"]:focus {
    border-color: var(--google-blue);
    box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.25);
}
.form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--muted-color);
    margin-bottom: 20px;
}
.remember-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    user-select: none;
}
.btn-primary {
    background: var(--google-blue);
    color: #ffffff;
    font-size: 1rem;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.1s;
    text-align: center;
}
.btn-primary:hover {
    opacity: 0.9;
}
.btn-primary:active {
    transform: scale(0.98);
}
.btn-secondary {
    background: transparent;
    color: var(--muted-color);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 0.95rem;
    padding: 10px 18px;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: color 0.2s, border-color 0.2s;
}
[data-theme="light"] .btn-secondary {
    border-color: rgba(0, 0, 0, 0.2);
}
.btn-secondary:hover {
    color: var(--text-color);
    border-color: var(--text-color);
}
</style>

<?php include 'includes/footer.php'; ?>
