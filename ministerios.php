<?php
$pageTitle = 'Ministerios y Direcciones';
$pageDesc = 'Conoce nuestras áreas de trabajo y servicio ministerial';

require_once 'includes/db.php';
$ministries = db_all('ministry_content');
usort($ministries, function($a, $b) { return strcmp($a['title'], $b['title']); });

require_once 'includes/header.php';
?>

<main class="container">
    <section class="card">
        <h2>Nuestros Ministerios</h2>
        <p>Conoce las áreas de servicio de nuestra iglesia. Haz clic en cada ministerio o dirección para ver su información completa: líderes, fotos, videos y actividades.</p>
    </section>

    <section class="ministry-list">
        <?php foreach ($ministries as $m): ?>
            <a class="ministry-link" href="<?= htmlspecialchars($m['slug']) ?>.php">
                <article class="card ministry-row">
                    <img src="img/actividades.jpg" alt="<?= htmlspecialchars($m['title']) ?>" class="ministry-thumb">
                    <div class="ministry-content">
                        <h3><?= htmlspecialchars($m['title']) ?></h3>
                        <p><?= htmlspecialchars($m['description'] ?: 'Más información próximamente.') ?></p>
                    </div>
                </article>
            </a>
        <?php endforeach; ?>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
