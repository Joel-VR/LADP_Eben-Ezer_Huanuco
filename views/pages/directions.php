<?php
$pageTitle = 'Direcciones';
$pageDesc = 'Direcciones y áreas de coordinación';

require_once dirname(__DIR__, 2) . '/src/database.php';
$directions = array_filter(db_all('ministry_content'), function($m) { return strpos($m['slug'], 'direccion') !== false; });
usort($directions, function($a, $b) { return strcmp($a['title'], $b['title']); });

require_once dirname(__DIR__) . '/partials/header.php';
?>

<main class="container">
    <section class="card">
        <h2>Direcciones</h2>
        <p>Áreas de coordinación y gestión de nuestra iglesia.</p>
    </section>

    <section class="ministry-list">
        <?php foreach ($directions as $d): ?>
            <a class="ministry-link" href="<?= htmlspecialchars($d['slug']) ?>">
                <article class="card ministry-row">
                    <img src="assets/images/actividades.jpg" alt="<?= htmlspecialchars($d['title']) ?>" class="ministry-thumb">
                    <div class="ministry-content">
                        <h3><?= htmlspecialchars($d['title']) ?></h3>
                        <p><?= htmlspecialchars($d['description'] ?: 'Más información próximamente.') ?></p>
                    </div>
                </article>
            </a>
        <?php endforeach; ?>
    </section>
</main>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>
