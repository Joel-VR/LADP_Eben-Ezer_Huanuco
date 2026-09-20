<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/database.php';

$flash = '';
$flashType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        db_delete('decisiones', (int) ($_POST['id'] ?? 0));
        $flash = 'Registro eliminado.';
        $flashType = 'success';
    }
}

$decisions = db_all('decisiones');
usort($decisions, function($a, $b) { return strcmp($b['created_at'], $a['created_at']); });
require __DIR__ . '/../views/admin/decisions.php';
