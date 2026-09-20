<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/database.php';

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
require __DIR__ . '/../views/admin/ministries.php';
