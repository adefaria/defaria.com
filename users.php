<?php
/**
 * users.php
 * Web administration interface for managing authorized website users.
 * Restricted exclusively to administrator 'andrew'.
 */

require_once __DIR__ . '/php/auth.php';

$currentUser = SiteAuth::getCurrentUser();

// If not logged in, redirect to login
if (!$currentUser) {
    header('Location: /login.php?redirect=/users.php');
    exit;
}

// Check admin authorization
if (!SiteAuth::isAdmin($currentUser)) {
    http_response_code(403);
    $page_title = "Access Denied - Admin Users";
    include 'includes/frame_header.php';
    ?>
    <main class="container" style="max-width: 600px; margin: 60px auto; text-align: center; padding: 20px;">
        <div style="background: rgba(234, 67, 53, 0.1); border: 1px solid var(--google-red); border-radius: 8px; padding: 30px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--google-red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 15px;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
            </svg>
            <h1 style="color: var(--google-red); margin-top: 0;">Access Denied</h1>
            <p style="font-size: 1.1rem; color: var(--on-surface);">You are logged in as <strong><?php echo htmlspecialchars($currentUser['username']); ?></strong>.</p>
            <p style="color: var(--on-surface-variant);">Only the primary administrator (<strong>andrew</strong>) is authorized to manage user accounts.</p>
            <p style="margin-top: 25px;">
                <a href="/misc.php" class="btn" style="display: inline-block; padding: 8px 18px; background: var(--tab-active-bg); color: var(--text-color); border-radius: 6px; text-decoration: none;">Return to Misc</a>
            </p>
        </div>
    </main>
    <?php
    include 'includes/footer.php';
    exit;
}

// Handle Form Submissions (Add, Update, Delete)
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $new_user = strtolower(trim($_POST['username'] ?? ''));
        $new_pass = $_POST['password'] ?? '';
        $new_name = trim($_POST['display_name'] ?? '');

        if (empty($new_user)) {
            $error_msg = 'Username cannot be empty.';
        } elseif (!preg_match('/^[a-z0-9_.-]{2,32}$/', $new_user)) {
            $error_msg = 'Username must be 2-32 characters (letters, numbers, dot, dash, underscore).';
        } elseif (empty($new_pass)) {
            $error_msg = 'Password is required when creating a new user.';
        } else {
            $users = SiteAuth::getUsers();
            if (isset($users[$new_user])) {
                $error_msg = "User '{$new_user}' already exists.";
            } else {
                SiteAuth::saveUser($new_user, $new_pass, $new_name ?: ucfirst($new_user));
                $success_msg = "User '{$new_user}' added successfully.";
            }
        }
    } elseif ($action === 'edit') {
        $target_user = strtolower(trim($_POST['username'] ?? ''));
        $new_name = trim($_POST['display_name'] ?? '');
        $new_pass = $_POST['password'] ?? '';

        $users = SiteAuth::getUsers();
        if (!isset($users[$target_user])) {
            $error_msg = "User '{$target_user}' does not exist.";
        } else {
            SiteAuth::saveUser($target_user, !empty($new_pass) ? $new_pass : null, $new_name);
            $success_msg = "User '{$target_user}' updated successfully.";
        }
    } elseif ($action === 'delete') {
        $del_user = strtolower(trim($_POST['username'] ?? ''));

        if ($del_user === 'andrew') {
            $error_msg = "The primary administrator account 'andrew' cannot be deleted.";
        } else {
            if (SiteAuth::deleteUser($del_user)) {
                $success_msg = "User '{$del_user}' deleted.";
            } else {
                $error_msg = "User '{$del_user}' not found.";
            }
        }
    }
}

$users = SiteAuth::getUsers();
ksort($users);

$page_title = "Admin Users";
include 'includes/frame_header.php';
?>

<style>
    .admin-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid var(--outline-variant);
        padding-bottom: 15px;
    }

    .admin-title {
        color: var(--google-red);
        margin: 0;
        font-size: 2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-box {
        background: var(--surface-color);
        border: 1px solid var(--outline-variant);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .alert {
        padding: 12px 18px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background: rgba(52, 168, 83, 0.15);
        color: #2e7d32;
        border: 1px solid rgba(52, 168, 83, 0.3);
    }

    :root[data-theme="dark"] .alert-success {
        color: #81c784;
    }

    .alert-danger {
        background: rgba(234, 67, 53, 0.15);
        color: #c62828;
        border: 1px solid rgba(234, 67, 53, 0.3);
    }

    :root[data-theme="dark"] .alert-danger {
        color: #e57373;
    }

    table.user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table.user-table th, table.user-table td {
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid var(--outline-variant);
    }

    table.user-table th {
        font-weight: 600;
        color: var(--on-surface-variant);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-admin {
        background: rgba(234, 67, 53, 0.15);
        color: var(--google-red);
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 6px;
    }

    .badge-user {
        background: rgba(66, 133, 244, 0.15);
        color: var(--google-blue);
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 6px;
    }

    .form-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--on-surface-variant);
    }

    .form-control {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid var(--outline-variant);
        border-radius: 6px;
        background: var(--surface-color);
        color: var(--on-surface);
        box-sizing: border-box;
        font-family: inherit;
        font-size: 0.95rem;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--google-blue);
        box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2);
    }

    .btn {
        padding: 9px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background-color 0.2s, opacity 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .btn-primary {
        background: var(--google-blue);
        color: white;
    }

    .btn-action {
        padding: 5px 10px;
        font-size: 0.85rem;
    }

    .btn-edit {
        background: rgba(66, 133, 244, 0.15);
        color: var(--google-blue);
    }

    .btn-delete {
        background: rgba(234, 67, 53, 0.15);
        color: var(--google-red);
    }

    /* Modal styling for edit user */
    .modal-backdrop {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-backdrop.show {
        display: flex;
    }

    .modal-box {
        background: var(--surface-color);
        border: 1px solid var(--outline-variant);
        border-radius: 8px;
        padding: 24px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
</style>

<main class="container admin-container">
    <div class="admin-header">
        <h1 class="admin-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            User Administration
        </h1>
        <div style="font-size: 0.9rem; color: var(--on-surface-variant);">
            Admin: <strong><?php echo htmlspecialchars($currentUser['username']); ?></strong>
        </div>
    </div>

    <?php if ($success_msg): ?>
        <div class="alert alert-success">✓ <?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger">⚠ <?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <!-- Authorized Users Table -->
    <div class="card-box">
        <h2 style="font-size: 1.25rem; margin-top: 0; margin-bottom: 15px; color: var(--on-surface);">
            Authorized Accounts (<?php echo count($users); ?>)
        </h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Display Name</th>
                    <th>Role</th>
                    <th>Last Updated</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <?php
                        $uname = htmlspecialchars($u['username']);
                        $dname = htmlspecialchars($u['display_name'] ?? '');
                        $is_admin = ($u['username'] === 'andrew');
                        $updated = !empty($u['updated_at']) ? date("M j, Y g:i a", strtotime($u['updated_at'])) : 'Unknown';
                    ?>
                    <tr>
                        <td><strong><?php echo $uname; ?></strong></td>
                        <td><?php echo $dname; ?></td>
                        <td>
                            <?php if ($is_admin): ?>
                                <span class="badge-admin">Administrator</span>
                            <?php else: ?>
                                <span class="badge-user">User</span>
                            <?php endif; ?>
                        </td>
                        <td style="color: var(--on-surface-variant); font-size: 0.9rem;"><?php echo $updated; ?></td>
                        <td style="text-align: right; white-space: nowrap;">
                            <button type="button" class="btn btn-action btn-edit"
                                    onclick="openEditModal('<?php echo addslashes($u['username']); ?>', '<?php echo addslashes($u['display_name'] ?? ''); ?>')">
                                Edit
                            </button>
                            <?php if (!$is_admin): ?>
                                <form method="post" action="/users.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete user \'<?php echo addslashes($u['username']); ?>\'?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="username" value="<?php echo $uname; ?>">
                                    <button type="submit" class="btn btn-action btn-delete">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add New User Form -->
    <div class="card-box">
        <h2 style="font-size: 1.25rem; margin-top: 0; margin-bottom: 15px; color: var(--on-surface);">
            Add New User
        </h2>
        <form method="post" action="/users.php" autocomplete="off">
            <input type="hidden" name="action" value="add">
            <div class="form-row">
                <div class="form-group">
                    <label for="new_username">Username *</label>
                    <input type="text" id="new_username" name="username" class="form-control" placeholder="e.g. jdoe" required pattern="[a-zA-Z0-9_.-]{2,32}">
                </div>
                <div class="form-group">
                    <label for="new_display_name">Display Name</label>
                    <input type="text" id="new_display_name" name="display_name" class="form-control" placeholder="e.g. John Doe">
                </div>
                <div class="form-group">
                    <label for="new_password">Password *</label>
                    <input type="password" id="new_password" name="password" class="form-control" placeholder="Strong password" required minlength="4">
                </div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add User
                </button>
            </div>
        </form>
    </div>
</main>

<!-- Edit User Modal -->
<div id="editModal" class="modal-backdrop">
    <div class="modal-box">
        <h3 style="margin-top: 0; margin-bottom: 15px; color: var(--on-surface);">Edit User: <span id="modalUsernameTitle" style="color: var(--google-blue);"></span></h3>
        <form method="post" action="/users.php" autocomplete="off">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" id="edit_username" name="username" value="">
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="edit_display_name">Display Name</label>
                <input type="text" id="edit_display_name" name="display_name" class="form-control" placeholder="Full Name">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="edit_password">New Password <span style="font-size: 0.8rem; font-weight: normal; color: var(--on-surface-variant);">(leave blank to keep current password)</span></label>
                <input type="password" id="edit_password" name="password" class="form-control" placeholder="Enter new password">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn" style="background: var(--outline-variant); color: var(--on-surface);" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(username, displayName) {
        document.getElementById('edit_username').value = username;
        document.getElementById('modalUsernameTitle').textContent = username;
        document.getElementById('edit_display_name').value = displayName;
        document.getElementById('edit_password').value = '';
        document.getElementById('editModal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    // Close modal on click outside box
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
