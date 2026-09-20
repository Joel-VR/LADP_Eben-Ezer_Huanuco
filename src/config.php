<?php
/**
 * App configuration — paths, env, URLs.
 * English folder layout:
 *   public/  -> web entry (DocumentRoot)
 *   views/   -> templates (pages, partials, admin)
 *   styles/  -> CSS
 *   routes/  -> route maps
 *   assets/  -> images + static data
 *   src/     -> PHP source (config, database, auth, helpers)
 *   db/      -> SQL schema
 *   scripts/ -> CLI tools (migrate, seed)
 */

$ROOT = dirname(__DIR__);

define('APP_ROOT', $ROOT);
define('VIEW_PATH', $ROOT . '/views');
define('SRC_PATH', $ROOT . '/src');
define('ASSET_IMAGE_PATH', $ROOT . '/assets/images');
define('UBIGEO_PATH', $ROOT . '/assets/data/ubigeo.json');

// Base URL (empty = relative). Set BASE_URL env on Render if served from subpath.
$baseUrl = getenv('BASE_URL');
if ($baseUrl === false) $baseUrl = '';
define('BASE_URL', rtrim($baseUrl, '/'));

/**
 * url($path) — build absolute-from-root URL, e.g. url('styles/base.css') => '/styles/base.css'
 * When served from public/ as DocumentRoot, /styles must be reachable.
 * See Dockerfile: it symlinks/copies ../styles and ../assets into public/.
 */
function url(string $path): string {
    $path = ltrim($path, '/');
    return BASE_URL . '/' . $path;
}

// Timezone for Peru church
date_default_timezone_set('America/Lima');
