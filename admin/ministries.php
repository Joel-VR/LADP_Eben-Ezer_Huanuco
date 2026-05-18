<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

$flash = '';
$flashType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $slug = trim($_POST['slug'] ?? '');
    $title = trim($_POST['title'] ?? '');
    if (empty($title)) {
        $flash = 'El título es obligatorio.';
        $flashType = 'error';
    } else {
        db_update('ministry_content', db_find_by('ministry_content', 'slug', $slug)['id'], [
            'title' => $title,
            'description' => trim($_POST['description'] ?? ''),
            'leaders' => trim($_POST['leaders'] ?? ''),
            'activities' => trim($_POST['activities'] ?? ''),
            'videos' => trim($_POST['videos'] ?? ''),
            'photos' => trim($_POST['photos'] ?? ''),
        ]);
        $flash = 'Ministerio actualizado exitosamente.';
        $flashType = 'success';
    }
}

$editSlug = $_GET['edit'] ?? 'ministerio-jovenes';
$ministry = db_find_by('ministry_content', 'slug', $editSlug);
$allMinistries = db_all('ministry_content');
usort($allMinistries, function($a, $b) { return strcmp($a['title'], $b['title']); });
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin — Ministerios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-header">
    <h1>🏛 Gestión de Ministerios</h1>
    <a href="logout.php">Cerrar Sesión</a>
</div>

<nav class="admin-nav">
    <a href="index.php">Dashboard</a>
    <a href="events.php">Eventos</a>
    <a href="ministries.php" class="active">Ministerios</a>
    <a href="messages.php">Mensajes</a>
    <a href="settings.php">Configuración</a>
    <a href="../index.php" target="_blank">Ver Sitio →</a>
</nav>

<div class="admin-container">

    <?php if ($flash): ?>
        <div class="flash flash-<?= $flashType ?>"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns: 250px 1fr; gap: 20px;">
        <div class="card" style="height:fit-content;">
            <h2>Ministerios</h2>
            <div style="display:flex; flex-direction:column; gap:4px;">
                <?php foreach ($allMinistries as $m): ?>
                    <a href="ministries.php?edit=<?= $m['slug'] ?>"
                       class="btn <?= $m['slug'] === $editSlug ? 'btn-primary' : '' ?>"
                       style="text-align:left; font-size:0.85rem;">
                        <?= htmlspecialchars($m['title']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <h2>Editar: <?= htmlspecialchars($ministry['title'] ?? '') ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="slug" value="<?= htmlspecialchars($ministry['slug'] ?? '') ?>">

                <label for="title">Título</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($ministry['title'] ?? '') ?>" required>

                <label for="description">Descripción</label>
                <textarea id="description" name="description"><?= htmlspecialchars($ministry['description'] ?? '') ?></textarea>

                <label for="leaders">Líderes</label>
                <textarea id="leaders" name="leaders" placeholder="Nombres de los líderes, uno por línea"><?= htmlspecialchars($ministry['leaders'] ?? '') ?></textarea>

                <label for="activities">Actividades</label>
                <textarea id="activities" name="activities" placeholder="Descripción de actividades"><?= htmlspecialchars($ministry['activities'] ?? '') ?></textarea>

                <label for="videos">Videos (URLs de YouTube, uno por línea)</label>
                <textarea id="videos" name="videos" placeholder="https://youtube.com/watch?v=..."><?= htmlspecialchars($ministry['videos'] ?? '') ?></textarea>

                <label for="photos">Fotos (rutas, una por línea)</label>
                <textarea id="photos" name="photos" placeholder="img/ministerios/jovenes/foto1.jpg"><?= htmlspecialchars($ministry['photos'] ?? '') ?></textarea>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

</div>

</body>
</html>
