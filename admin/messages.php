<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/database.php';

$flash = '';
$flashType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'mark_read') {
        $id = (int) ($_POST['id'] ?? 0);
        $msg = db_find('contact_messages', $id);
        if ($msg) db_update('contact_messages', $id, ['read' => 1]);
        $flash = 'Mensaje marcado como leído.';
        $flashType = 'success';
    }
    if ($action === 'delete') {
        db_delete('contact_messages', (int) ($_POST['id'] ?? 0));
        $flash = 'Mensaje eliminado.';
        $flashType = 'success';
    }
    if ($action === 'mark_all_read') {
        $messages = db_all('contact_messages');
        foreach ($messages as $msg) {
            db_update('contact_messages', $msg['id'], ['read' => 1]);
        }
        $flash = 'Todos los mensajes marcados como leídos.';
        $flashType = 'success';
    }
}

$messages = db_all('contact_messages');
usort($messages, function($a, $b) { return strcmp($b['created_at'], $a['created_at']); });
require __DIR__ . '/../views/admin/messages.php';
