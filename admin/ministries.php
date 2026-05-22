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
        $updates = [
            'title' => $title,
            'description' => trim($_POST['description'] ?? ''),
            'leaders' => trim($_POST['leaders'] ?? ''),
            'activities' => trim($_POST['activities'] ?? ''),
            'videos' => trim($_POST['videos'] ?? ''),
            'photos' => trim($_POST['photos'] ?? ''),
        ];
        
        // Para dirección-comunicaciones, agregar campos adicionales
        if ($slug === 'direccion-comunicaciones') {
            $updates['social_media'] = [
                'facebook_url' => trim($_POST['facebook_url'] ?? ''),
                'instagram_url' => trim($_POST['instagram_url'] ?? ''),
                'youtube_url' => trim($_POST['youtube_url'] ?? ''),
                'tiktok_url' => trim($_POST['tiktok_url'] ?? ''),
            ];
            
            // Procesar transmisiones en vivo
            $live_streams = [];
            if (!empty($_POST['live_stream_titles'])) {
                foreach ((array)$_POST['live_stream_titles'] as $i => $title) {
                    if (!empty($title) && !empty($_POST['live_stream_urls'][$i])) {
                        $live_streams[] = [
                            'title' => trim($title),
                            'platform' => trim($_POST['live_stream_platforms'][$i] ?? 'YouTube'),
                            'url' => trim($_POST['live_stream_urls'][$i]),
                            'is_active' => isset($_POST['live_stream_active'][$i]) ? 1 : 0,
                        ];
                    }
                }
            }
            $updates['live_streams'] = $live_streams;
            
            // Procesar portafolio
            $portfolio = [];
            if (!empty($_POST['portfolio_titles'])) {
                foreach ((array)$_POST['portfolio_titles'] as $i => $title) {
                    if (!empty($title) && !empty($_POST['portfolio_images'][$i])) {
                        $portfolio[] = [
                            'title' => trim($title),
                            'description' => trim($_POST['portfolio_descriptions'][$i] ?? ''),
                            'image_url' => trim($_POST['portfolio_images'][$i]),
                            'category' => trim($_POST['portfolio_categories'][$i] ?? ''),
                            'featured' => isset($_POST['portfolio_featured'][$i]) ? 1 : 0,
                        ];
                    }
                }
            }
            $updates['portfolio_items'] = $portfolio;
        }
        
        db_update('ministry_content', db_find_by('ministry_content', 'slug', $slug)['id'], $updates);
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
    <a href="decisions.php">Decisiones</a><a href="messages.php">Mensajes</a>
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

                <?php if ($editSlug === 'direccion-comunicaciones'): ?>
                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
                    <h3 style="margin-top: 20px;">📱 Redes Sociales</h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">Agrega los enlaces a tus redes sociales</p>
                    
                    <label for="facebook_url">Facebook</label>
                    <input type="url" id="facebook_url" name="facebook_url" placeholder="https://facebook.com/..." value="<?= htmlspecialchars($ministry['social_media']['facebook_url'] ?? '') ?>">

                    <label for="instagram_url">Instagram</label>
                    <input type="url" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/..." value="<?= htmlspecialchars($ministry['social_media']['instagram_url'] ?? '') ?>">

                    <label for="youtube_url">YouTube</label>
                    <input type="url" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/..." value="<?= htmlspecialchars($ministry['social_media']['youtube_url'] ?? '') ?>">

                    <label for="tiktok_url">TikTok</label>
                    <input type="url" id="tiktok_url" name="tiktok_url" placeholder="https://tiktok.com/@..." value="<?= htmlspecialchars($ministry['social_media']['tiktok_url'] ?? '') ?>">

                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
                    <h3 style="margin-top: 20px;">📡 Transmisiones en Vivo</h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">Agrega tus canales de transmisión en vivo</p>
                    <div id="live_streams_container">
                        <?php if (!empty($ministry['live_streams'])): ?>
                            <?php foreach ($ministry['live_streams'] as $i => $stream): ?>
                                <div class="live-stream-item" style="border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin-bottom: 12px;">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 10px;">
                                        <input type="text" name="live_stream_titles[]" placeholder="Título" value="<?= htmlspecialchars($stream['title'] ?? '') ?>" style="width:100%;">
                                        <select name="live_stream_platforms[]" style="width:100%;">
                                            <option value="YouTube" <?= ($stream['platform'] ?? '') === 'YouTube' ? 'selected' : '' ?>>YouTube</option>
                                            <option value="Facebook" <?= ($stream['platform'] ?? '') === 'Facebook' ? 'selected' : '' ?>>Facebook Live</option>
                                            <option value="Other" <?= ($stream['platform'] ?? '') === 'Other' ? 'selected' : '' ?>>Otro</option>
                                        </select>
                                        <input type="url" name="live_stream_urls[]" placeholder="URL" value="<?= htmlspecialchars($stream['url'] ?? '') ?>" style="width:100%;">
                                        <button type="button" class="btn" style="padding: 6px 12px; background:#f44336; color:#fff; border:none; border-radius:4px; cursor:pointer;" onclick="this.parentElement.parentElement.remove();">✕</button>
                                    </div>
                                    <label style="display:flex; align-items:center; gap:8px;">
                                        <input type="checkbox" name="live_stream_active[]" <?= ($stream['is_active'] ?? 0) ? 'checked' : '' ?>> <span style="font-size:0.9rem;">En vivo ahora</span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn" style="background:#4CAF50; color:#fff; margin-bottom:20px;" onclick="addLiveStream();">+ Agregar Transmisión</button>

                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
                    <h3 style="margin-top: 20px;">🎨 Portafolio de Contenido</h3>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">Muestra tus trabajos, diseños y contenido creado</p>
                    <div id="portfolio_container">
                        <?php if (!empty($ministry['portfolio_items'])): ?>
                            <?php foreach ($ministry['portfolio_items'] as $i => $item): ?>
                                <div class="portfolio-item" style="border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin-bottom: 12px;">
                                    <div style="display: grid; gap: 10px; margin-bottom: 10px;">
                                        <input type="text" name="portfolio_titles[]" placeholder="Título del trabajo" value="<?= htmlspecialchars($item['title'] ?? '') ?>" style="width:100%;">
                                        <textarea name="portfolio_descriptions[]" placeholder="Descripción breve" style="width:100%; min-height:60px;"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                                        <input type="text" name="portfolio_images[]" placeholder="URL de la imagen (ej: img/portfolio/design1.jpg)" value="<?= htmlspecialchars($item['image_url'] ?? '') ?>" style="width:100%;">
                                        <input type="text" name="portfolio_categories[]" placeholder="Categoría (ej: Diseño Gráfico, Vídeo, etc.)" value="<?= htmlspecialchars($item['category'] ?? '') ?>" style="width:100%;">
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <label style="display:flex; align-items:center; gap:8px;">
                                            <input type="checkbox" name="portfolio_featured[]" <?= ($item['featured'] ?? 0) ? 'checked' : '' ?>> <span style="font-size:0.9rem;">Destacado</span>
                                        </label>
                                        <button type="button" class="btn" style="padding: 6px 12px; background:#f44336; color:#fff; border:none; border-radius:4px; cursor:pointer;" onclick="this.parentElement.parentElement.remove();">✕ Eliminar</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn" style="background:#2196F3; color:#fff; margin-bottom:20px;" onclick="addPortfolio();">+ Agregar Item al Portafolio</button>

                    <script>
                    function addLiveStream() {
                        const container = document.getElementById('live_streams_container');
                        const item = document.createElement('div');
                        item.className = 'live-stream-item';
                        item.style.cssText = 'border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin-bottom: 12px;';
                        item.innerHTML = `
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 10px;">
                                <input type="text" name="live_stream_titles[]" placeholder="Título" style="width:100%;">
                                <select name="live_stream_platforms[]" style="width:100%;">
                                    <option value="YouTube">YouTube</option>
                                    <option value="Facebook">Facebook Live</option>
                                    <option value="Other">Otro</option>
                                </select>
                                <input type="url" name="live_stream_urls[]" placeholder="URL" style="width:100%;">
                                <button type="button" class="btn" style="padding: 6px 12px; background:#f44336; color:#fff; border:none; border-radius:4px; cursor:pointer;" onclick="this.parentElement.parentElement.remove();">✕</button>
                            </div>
                            <label style="display:flex; align-items:center; gap:8px;">
                                <input type="checkbox" name="live_stream_active[]"> <span style="font-size:0.9rem;">En vivo ahora</span>
                            </label>
                        `;
                        container.appendChild(item);
                    }

                    function addPortfolio() {
                        const container = document.getElementById('portfolio_container');
                        const item = document.createElement('div');
                        item.className = 'portfolio-item';
                        item.style.cssText = 'border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin-bottom: 12px;';
                        item.innerHTML = `
                            <div style="display: grid; gap: 10px; margin-bottom: 10px;">
                                <input type="text" name="portfolio_titles[]" placeholder="Título del trabajo" style="width:100%;">
                                <textarea name="portfolio_descriptions[]" placeholder="Descripción breve" style="width:100%; min-height:60px;"></textarea>
                                <input type="text" name="portfolio_images[]" placeholder="URL de la imagen (ej: img/portfolio/design1.jpg)" style="width:100%;">
                                <input type="text" name="portfolio_categories[]" placeholder="Categoría (ej: Diseño Gráfico, Vídeo, etc.)" style="width:100%;">
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <label style="display:flex; align-items:center; gap:8px;">
                                    <input type="checkbox" name="portfolio_featured[]"> <span style="font-size:0.9rem;">Destacado</span>
                                </label>
                                <button type="button" class="btn" style="padding: 6px 12px; background:#f44336; color:#fff; border:none; border-radius:4px; cursor:pointer;" onclick="this.parentElement.parentElement.remove();">✕ Eliminar</button>
                            </div>
                        `;
                        container.appendChild(item);
                    }
                    </script>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

</div>

</body>
</html>
