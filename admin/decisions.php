<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin — Decisiones por Cristo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-header">
    <h1>❤️ Decisiones por Cristo</h1>
    <a href="logout.php">Cerrar Sesión</a>
</div>

<nav class="admin-nav">
    <a href="index.php">Dashboard</a>
    <a href="events.php">Eventos</a>
    <a href="ministries.php">Ministerios</a>
    <a href="decisions.php" class="active">Decisiones</a>
    <a href="messages.php">Mensajes</a>
    <a href="settings.php">Configuración</a>
    <a href="../index.php" target="_blank">Ver Sitio →</a>
</nav>

<div class="admin-container">

    <?php if ($flash): ?>
        <div class="flash flash-<?= $flashType ?>"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Personas que tomaron una decisión (<?= count($decisions) ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Teléfono</th>
                    <th>Ubicación</th>
                    <th>Email</th>
                    <th>Decisión</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($decisions as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['nombres'] . ' ' . $d['apellidos']) ?></td>
                    <td><?= htmlspecialchars($d['edad']) ?></td>
                    <td><?= htmlspecialchars($d['telefono']) ?></td>
                    <td><?= htmlspecialchars($d['distrito'] . ', ' . $d['departamento']) ?></td>
                    <td><?= htmlspecialchars($d['email'] ?: '—') ?></td>
                    <td>
                        <?php if (!empty($d['entrego']) && !empty($d['reconcilio'])): ?>
                            <span class="badge badge-success">Entrega + Reconciliación</span>
                        <?php elseif (!empty($d['entrego'])): ?>
                            <span class="badge badge-success">Entrega a Cristo</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Reconciliación</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $d['created_at'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este registro?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $d['id'] ?>">
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
