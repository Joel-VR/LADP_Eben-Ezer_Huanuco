<?php
$pageTitle = 'Eventos';
$pageDesc = 'Actividades, reuniones y agenda de la iglesia';

require_once 'includes/db.php';
$events = array_filter(db_all('events'), function($e) { return $e['active'] == 1; });
usort($events, function($a, $b) { return strcmp($a['date'], $b['date']); });

require_once 'includes/header.php';
?>

<main class="container">
    <section class="card">
        <h2>Próximos Eventos</h2>
        <div class="event-grid">
            <?php foreach ($events as $ev): ?>
            <article class="event-card">
                <h3><?= htmlspecialchars($ev['title']) ?></h3>
                <p><?= htmlspecialchars($ev['description'] ?: 'Próximamente más detalles.') ?></p>
                <?php if ($ev['date']): ?>
                    <p><strong>📅 <?= date('d/m/Y', strtotime($ev['date'])) ?></strong></p>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
