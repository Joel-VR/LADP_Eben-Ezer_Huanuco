<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

$events = db_all('events');
$messages = db_all('contact_messages');
$ministries = db_all('ministry_content');

$eventCount = count($events);
$messageCount = count(array_filter($messages, function($m) { return !isset($m['read']) || $m['read'] == 0; }));
$ministryCount = count($ministries);

$unreadMessages = array_filter($messages, function($m) { return !isset($m['read']) || $m['read'] == 0; });
usort($unreadMessages, function($a, $b) { return strcmp($b['created_at'], $a['created_at']); });
$unreadMessages = array_slice($unreadMessages, 0, 5);

$recentEvents = $events;
usort($recentEvents, function($a, $b) { return strcmp($b['created_at'], $a['created_at']); });
$recentEvents = array_slice($recentEvents, 0, 5);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin — Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-header">
    <h1>🏛 Panel de Administración</h1>
    <a href="logout.php">Cerrar Sesión</a>
</div>

<nav class="admin-nav">
    <a href="index.php" class="active">Dashboard</a>
    <a href="events.php">Eventos</a>
    <a href="ministries.php">Ministerios</a>
    <a href="messages.php">Mensajes</a>
    <a href="settings.php">Configuración</a>
    <a href="../index.php" target="_blank">Ver Sitio →</a>
</nav>

<div class="admin-container">

    <div class="stats-grid">
        <div class="stat-card">
            <div class="number"><?= $eventCount ?></div>
            <div class="label">Eventos</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $ministryCount ?></div>
            <div class="label">Ministerios</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $messageCount ?></div>
            <div class="label">Mensajes sin leer</div>
        </div>
    </div>

    <?php if ($messageCount > 0): ?>
    <div class="card">
        <h2>📬 Mensajes Recientes sin Leer</h2>
        <table>
            <thead><tr><th>Nombre</th><th>Mensaje</th><th>Fecha</th></tr></thead>
            <tbody>
                <?php foreach ($unreadMessages as $msg): ?>
                <tr class="unread">
                    <td><?= htmlspecialchars($msg['name']) ?></td>
                    <td><?= htmlspecialchars(substr($msg['message'], 0, 60)) ?><?= strlen($msg['message']) > 60 ? '...' : '' ?></td>
                    <td><?= $msg['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin-top:12px;"><a href="messages.php" class="btn btn-primary btn-sm">Ver todos los mensajes</a></p>
    </div>
    <?php endif; ?>

    <div class="card">
        <h2>📅 Eventos Recientes</h2>
        <table>
            <thead><tr><th>Título</th><th>Fecha</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach ($recentEvents as $ev): ?>
                <tr>
                    <td><?= htmlspecialchars($ev['title']) ?></td>
                    <td><?= htmlspecialchars($ev['date'] ?: 'Sin fecha') ?></td>
                    <td><?= $ev['active'] ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin-top:12px;"><a href="events.php" class="btn btn-primary btn-sm">Gestionar eventos</a></p>
    </div>

</div>

</body>
</html>
