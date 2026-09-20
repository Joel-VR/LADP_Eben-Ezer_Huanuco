<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin — Mensajes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>

<div class="admin-header">
    <h1>📬 Mensajes de Contacto</h1>
    <a href="logout.php">Cerrar Sesión</a>
</div>

<nav class="admin-nav">
    <a href="index.php">Dashboard</a>
    <a href="events.php">Eventos</a>
    <a href="ministries.php">Ministerios</a>
    <a href="messages.php" class="active">Mensajes</a>
    <a href="settings.php">Configuración</a>
    <a href="../public/index.php" target="_blank">Ver Sitio →</a>
</nav>

<div class="admin-container">

    <?php if ($flash): ?>
        <div class="flash flash-<?= $flashType ?>"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <h2 style="margin:0;">Todos los Mensajes</h2>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="mark_all_read">
                <button type="submit" class="btn btn-primary btn-sm">Marcar todos como leídos</button>
            </form>
        </div>

        <table>
            <thead><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Mensaje</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                <tr class="<?= ($msg['read'] ?? 0) ? '' : 'unread' ?>">
                    <td><?= htmlspecialchars($msg['name']) ?></td>
                    <td><?= htmlspecialchars($msg['email'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($msg['phone'] ?: '—') ?></td>
                    <td><?= htmlspecialchars(substr($msg['message'], 0, 50)) ?><?= strlen($msg['message']) > 50 ? '...' : '' ?></td>
                    <td><?= $msg['created_at'] ?></td>
                    <td><?= ($msg['read'] ?? 0) ? '<span class="badge badge-success">Leído</span>' : '<span class="badge badge-warning">Nuevo</span>' ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="mark_read">
                            <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                            <button type="submit" class="btn btn-primary btn-sm" <?= ($msg['read'] ?? 0) ? 'disabled' : '' ?>>✓</button>
                        </form>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este mensaje?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">✕</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
