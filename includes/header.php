<?php
/**
 * Shared header and navigation.
 * Usage: set $pageTitle and $pageDesc before including.
 */

if (!isset($pageTitle)) $pageTitle = 'Iglesia Eben-Ezer';
if (!isset($pageDesc)) $pageDesc = 'Iglesia de avivamiento y evangelismo';
if (!isset($currentPage)) $currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/header-nav.css">
    <link rel="stylesheet" href="css/components.css">
    <?php if (!empty($includeContact)): ?>
    <link rel="stylesheet" href="css/contact.css">
    <?php endif; ?>
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>

<header class="site-header">
    <div class="brand-wrap">
        <a href="index.php"><img src="img/LOGOS EBEN CON BORDE.png" class="logo" alt="Logo Iglesia Eben-Ezer"></a>
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
            <a href="historia.php">Historia</a>
            <a href="mision-vision.php">Misión y Visión</a>
            <a href="contacto.php">Contacto</a>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn" type="button">Ministerios y direcciones</button>
        <div class="dropdown-menu">
            <a href="ministerios.php">Ministerios</a>
            <a href="direcciones.php">Direcciones</a>
        </div>
    </div>
    <a href="decision.php">Quiero entregarme a Jesús</a>
    <a href="eventos.php">Eventos</a>
    <a href="contacto.php">Contacto</a>
</nav>
