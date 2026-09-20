<?php
/**
 * Admin session guard with secure cookie params.
 */
require_once __DIR__ . '/config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Preserve clean-URL or legacy context: login lives at /admin/login.php (legacy shim)
    // and /admin/login (router). Redirect relative to current dir.
    $login = (strpos($_SERVER['REQUEST_URI'] ?? '', '/admin') === 0) ? 'login.php' : 'admin/login.php';
    if (file_exists(__DIR__ . '/../admin/login.php')) {
        header('Location: ' . $login);
    } else {
        header('Location: /admin/login');
    }
    exit;
}
