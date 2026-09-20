<?php
/**
 * Front controller — public entry. DocumentRoot must point here.
 * Maps clean URLs to views/pages via routes/web.php.
 * Handles contact + decision POSTs (same logic as legacy pages).
 */
// php -S dev: serve static files directly (Apache .htaccess does this in prod)
if (PHP_SAPI === 'cli-server') {
    $pubPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $file = __DIR__ . $pubPath;
    if ($pubPath !== '/' && is_file($file)) return false;
}
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/database.php';

$routes = require __DIR__ . '/../routes/web.php';

// Resolve slug: /history, /?page=history, /history.php (legacy)
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$slug = trim($uri, '/');
if (isset($_GET['page']) && $_GET['page'] !== '') $slug = trim($_GET['page'], '/');
// Strip .php legacy suffix
if (str_ends_with($slug, '.php')) $slug = substr($slug, 0, -4);
// Legacy index
if ($slug === 'index' || $slug === 'index.php') $slug = '';
// public/ prefix when served with DocumentRoot=root (dev)
if (str_starts_with($slug, 'public/')) $slug = substr($slug, strlen('public/'));
if ($slug === 'public' || $slug === 'public/index') $slug = '';

// Health is separate file, but alias here too
if ($slug === 'health') {
    header('Content-Type: text/plain');
    try {
        db()->query('SELECT 1');
        echo 'OK';
    } catch (Throwable $e) {
        http_response_code(500);
        echo 'DB_ERROR';
    }
    exit;
}

if (!array_key_exists($slug, $routes)) {
    http_response_code(404);
    $pageTitle = 'No encontrado';
    $pageDesc = 'Página no encontrada';
    $currentPage = '404';
    require __DIR__ . '/../views/partials/header.php';
    echo '<main class="container"><section class="card"><h2>404 — Página no encontrada</h2><p><a href="' . url('') . '">Volver al inicio</a></p></section></main>';
    require __DIR__ . '/../views/partials/footer.php';
    exit;
}

$route = $routes[$slug];
$pageTitle = $route['title'] ?? 'Iglesia Eben-Ezer';
$pageDesc = $route['desc'] ?? 'Iglesia de avivamiento y evangelismo';
$currentPage = $slug === '' ? 'home' : $slug;
if (!empty($route['slug'])) {
    // Ministry detail views expect $ministrySlug
    $ministrySlug = $route['slug'];
}

$viewFile = __DIR__ . '/../views/' . $route['view'];
if (!file_exists($viewFile)) {
    http_response_code(500);
    echo 'View missing: ' . htmlspecialchars($route['view']);
    exit;
}

require $viewFile;
