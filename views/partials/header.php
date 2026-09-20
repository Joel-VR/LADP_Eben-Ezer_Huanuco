<?php
/**
 * Shared header and navigation.
 * Usage: set $pageTitle, $pageDesc, $currentPage before including.
 * New location: views/partials/header.php
 */
if (!isset($pageTitle)) $pageTitle = 'Iglesia Eben-Ezer';
if (!isset($pageDesc)) $pageDesc = 'Iglesia de avivamiento y evangelismo';
if (!isset($currentPage)) $currentPage = basename($_SERVER['PHP_SELF'] ?? 'home');
if (!function_exists('url')) {
    function url(string $p): string { return '/' . ltrim($p, '/'); }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
    <link rel="stylesheet" href="<?= url('styles/base.css') ?>">
    <link rel="stylesheet" href="<?= url('styles/header-nav.css') ?>">
    <link rel="stylesheet" href="<?= url('styles/components.css') ?>">
    <?php if (!empty($includeContact)): ?>
    <link rel="stylesheet" href="<?= url('styles/contact.css') ?>">
    <?php endif; ?>
    <?php if (($currentPage === 'direction-communications') || ($currentPage === 'direccion-comunicaciones.php')): ?>
    <link rel="stylesheet" href="<?= url('styles/communications.css') ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= url('styles/responsive.css') ?>">
</head>
<body>

<header class="site-header">
    <div class="brand-wrap">
        <a href="<?= url('') ?>"><img src="<?= url('assets/images/LOGOS EBEN CON BORDE.png') ?>" class="logo" alt="Logo Iglesia Eben-Ezer"></a>
        <div>
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
            <p><?= htmlspecialchars($pageDesc) ?></p>
        </div>
    </div>
</header>

<nav class="main-nav">
    <div class="dropdown">
        <button class="dropbtn" type="button">Quiénes somos</button>
        <div class="dropdown-menu">
            <a href="<?= url('history') ?>">Historia</a>
            <a href="<?= url('mission-vision') ?>">Misión y Visión</a>
            <a href="<?= url('contact') ?>">Contacto</a>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn" type="button">Ministerios y direcciones</button>
        <div class="dropdown-menu">
            <a href="<?= url('ministries') ?>">Ministerios</a>
            <a href="<?= url('directions') ?>">Direcciones</a>
        </div>
    </div>
    <a href="<?= url('decision') ?>">Quiero entregarme a Jesús</a>
    <div class="dropdown">
        <button class="dropbtn" type="button">Videos</button>
        <div class="dropdown-menu">
            <a href="<?= url('events') ?>">Eventos</a>
            <a href="<?= url('daily-reading') ?>">Lectura diaria</a>
        </div>
    </div>
    <a href="<?= url('contact') ?>">Contacto</a>
</nav>
