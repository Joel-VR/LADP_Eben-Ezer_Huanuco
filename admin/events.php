<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/database.php';

$flash = '';
$flashType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        if (empty($title)) {
            $flash = 'El título es obligatorio.';
            $flashType = 'error';
        } else {
            db_insert('events', [
                'title' => $title,
                'description' => trim($_POST['description'] ?? ''),
                'date' => trim($_POST['date'] ?? ''),
                'image' => trim($_POST['image'] ?? 'assets/images/actividades.jpg'),
                'active' => isset($_POST['active']) ? 1 : 0,
            ]);
            $flash = 'Evento creado exitosamente.';
            $flashType = 'success';
        }
    }

    if ($action === 'edit') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        if (empty($title)) {
            $flash = 'El título es obligatorio.';
            $flashType = 'error';
        } else {
            db_update('events', $id, [
                'title' => $title,
                'description' => trim($_POST['description'] ?? ''),
                'date' => trim($_POST['date'] ?? ''),
                'image' => trim($_POST['image'] ?? 'assets/images/actividades.jpg'),
                'active' => isset($_POST['active']) ? 1 : 0,
            ]);
            $flash = 'Evento actualizado exitosamente.';
            $flashType = 'success';
        }
    }

    if ($action === 'delete') {
        db_delete('events', (int) ($_POST['id'] ?? 0));
        $flash = 'Evento eliminado.';
        $flashType = 'success';
    }
}

$events = db_all('events');
usort($events, function($a, $b) { return strcmp($a['date'] ?? '', $b['date'] ?? ''); });

$editEvent = null;
if (isset($_GET['edit'])) {
    $editEvent = db_find('events', (int) $_GET['edit']);
}
require __DIR__ . '/../views/admin/events.php';
