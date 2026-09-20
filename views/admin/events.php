<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin — Eventos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/admin.css">
</head>
<body>

<div class="admin-header">
    <h1>📅 Gestión de Eventos</h1>
    <a href="logout.php">Cerrar Sesión</a>
</div>

<nav class="admin-nav">
    <a href="index.php">Dashboard</a>
    <a href="events.php" class="active">Eventos</a>
    <a href="ministries.php">Ministerios</a>
    <a href="decisions.php">Decisiones</a><a href="messages.php">Mensajes</a>
    <a href="settings.php">Configuración</a>
    <a href="../public/index.php" target="_blank">Ver Sitio →</a>
</nav>

<div class="admin-container">

    <?php if ($flash): ?>
        <div class="flash flash-<?= $flashType ?>"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="card">
        <h2><?= $editEvent ? 'Editar Evento' : 'Agregar Evento' ?></h2>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editEvent ? 'edit' : 'add' ?>">
            <?php if ($editEvent): ?>
                <input type="hidden" name="id" value="<?= $editEvent['id'] ?>">
            <?php endif; ?>

            <label for="title">Título</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($editEvent['title'] ?? '') ?>" required>

            <label for="description">Descripción</label>
            <textarea id="description" name="description"><?= htmlspecialchars($editEvent['description'] ?? '') ?></textarea>

            <div class="form-row">
                <div>
                    <label for="date">Fecha</label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($editEvent['date'] ?? '') ?>">
                </div>
                <div>
                    <label for="image">Ruta de imagen</label>
                    <input type="text" id="image" name="image" value="<?= htmlspecialchars($editEvent['image'] ?? 'assets/images/actividades.jpg') ?>">
                </div>
            </div>

            <label>
                <input type="checkbox" name="active" <?= ($editEvent['active'] ?? 1) ? 'checked' : '' ?>>
                Evento activo
            </label>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $editEvent ? 'Actualizar' : 'Crear' ?></button>
                <?php if ($editEvent): ?>
                    <a href="events.php" class="btn">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Todos los Eventos</h2>
        <table>
            <thead><tr><th>Título</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($events as $ev): ?>
                <tr>
                    <td><?= htmlspecialchars($ev['title']) ?></td>
                    <td><?= htmlspecialchars($ev['date'] ?: '—') ?></td>
                    <td><?= $ev['active'] ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>' ?></td>
                    <td>
                        <a href="events.php?edit=<?= $ev['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este evento?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $ev['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
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
