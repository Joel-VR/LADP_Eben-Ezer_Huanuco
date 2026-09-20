<?php
/**
 * Shared helpers: escaping, redirects, CSRF.
 */

function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

function csrf_token(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $sent = $_POST['csrf_token'] ?? '';
    $stored = $_SESSION['csrf_token'] ?? '';
    if (!$sent || !$stored) return false;
    return hash_equals($stored, $sent);
}

/** JSON columns stored as TEXT/JSONB — decode to PHP array safely. */
function json_col($value) {
    if (is_array($value)) return $value;
    if ($value === null || $value === '') return null;
    $decoded = json_decode((string)$value, true);
    return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $value;
}
