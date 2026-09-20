<?php
require_once dirname(__DIR__, 2) . '/src/database.php';
$ministry = db_find_by('ministry_content', 'slug', 'ministerio-jovenes');
$pageTitle = $ministry['title'] ?? 'Ministerio de Jóvenes';
$pageDesc = $ministry['description'] ?? 'Formación, servicio y acompañamiento espiritual';
require_once dirname(__DIR__) . '/partials/header.php';
?>

<main class="container">
    <section class="card">
        <h2>Líderes</h2>
        <p><?= nl2br(htmlspecialchars($ministry['leaders'] ?: 'Información de líderes próximamente.')) ?></p>
    </section>
    <?php if (!empty($ministry['photos'])): ?>
    <section class="card">
        <h2>Fotos</h2>
        <div class="event-grid">
            <?php foreach (array_filter(array_map('trim', explode("\n", $ministry['photos']))) as $photo): ?>
                <img src="<?= htmlspecialchars($photo) ?>" alt="Foto" class="actividades">
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
    <?php if (!empty($ministry['videos'])): ?>
    <section class="card">
        <h2>Videos</h2>
        <?php foreach (array_filter(array_map('trim', explode("\n", $ministry['videos']))) as $video): ?>
            <?php if (preg_match('/v=([a-zA-Z0-9_-]+)/', $video, $m)): ?>
                <div style="margin-bottom:16px;"><iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= htmlspecialchars($m[1]) ?>" frameborder="0" allowfullscreen style="border-radius:10px;"></iframe></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
    <section class="card">
        <h2>Actividades</h2>
        <p><?= nl2br(htmlspecialchars($ministry['activities'] ?: 'Información de actividades próximamente.')) ?></p>
    </section>
</main>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>
