#!/usr/bin/env php
<?php
/**
 * manage_users.php
 * Command-line utility to manage authorized users on defaria.com
 *
 * Usage:
 *   php manage_users.php list
 *   php manage_users.php add <username> <password> [displayName]
 *   php manage_users.php passwd <username> <newPassword>
 *   php manage_users.php delete <username>
 */

require_once __DIR__ . '/../php/auth.php';

$cmd = $argv[1] ?? 'help';

switch ($cmd) {
    case 'list':
        $users = SiteAuth::getUsers();
        echo "Registered Authorized Users (" . count($users) . "):\n";
        echo str_repeat('-', 60) . "\n";
        printf("%-15s | %-20s | %s\n", "Username", "Display Name", "Last Updated");
        echo str_repeat('-', 60) . "\n";
        foreach ($users as $u) {
            printf("%-15s | %-20s | %s\n", $u['username'], $u['display_name'] ?? '', $u['updated_at'] ?? 'N/A');
        }
        echo str_repeat('-', 60) . "\n";
        break;

    case 'add':
        if ($argc < 4) {
            echo "Usage: php manage_users.php add <username> <password> [displayName]\n";
            exit(1);
        }
        $user = $argv[2];
        $pass = $argv[3];
        $name = $argv[4] ?? ucfirst($user);
        SiteAuth::saveUser($user, $pass, $name);
        echo "✓ User '$user' ($name) successfully saved with Argon2id hash.\n";
        break;

    case 'passwd':
        if ($argc < 4) {
            echo "Usage: php manage_users.php passwd <username> <newPassword>\n";
            exit(1);
        }
        $user = strtolower($argv[2]);
        $pass = $argv[3];
        $users = SiteAuth::getUsers();
        if (!isset($users[$user])) {
            echo "Error: User '$user' not found.\n";
            exit(1);
        }
        $name = $users[$user]['display_name'] ?? ucfirst($user);
        SiteAuth::saveUser($user, $pass, $name);
        echo "✓ Password updated for user '$user'.\n";
        break;

    case 'delete':
        if ($argc < 3) {
            echo "Usage: php manage_users.php delete <username>\n";
            exit(1);
        }
        $user = $argv[2];
        if (SiteAuth::deleteUser($user)) {
            echo "✓ User '$user' deleted.\n";
        } else {
            echo "User '$user' not found.\n";
        }
        break;

    case 'help':
    default:
        echo "defaria.com User Management CLI\n";
        echo "Commands:\n";
        echo "  list                                  List all authorized accounts\n";
        echo "  add <username> <password> [name]      Add/overwrite user account\n";
        echo "  passwd <username> <newPassword>       Change user password\n";
        echo "  delete <username>                     Remove user account\n";
        break;
}
