<?php
// Health check for Render. Returns OK if DB reachable.
header('Content-Type: text/plain');
try {
    require_once __DIR__ . '/../src/database.php';
    db()->query('SELECT 1');
    echo 'OK';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'DB_ERROR';
}
