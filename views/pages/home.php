<?php
$pageTitle = 'Iglesia Eben-Ezer';
$pageDesc = 'Iglesia de avivamiento y evangelismo';
$currentPage = 'home';
$includeContact = true;

require_once dirname(__DIR__, 2) . '/src/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    db_insert('contact_messages', [
        'name' => trim($_POST['name'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
        'read' => 0,
    ]);
    $success = '¡Mensaje enviado! Nos pondremos en contacto contigo pronto.';
}

$events = array_filter(db_all('events'), function ($e) {
    return $e['active'] == 1;
});
usort($events, function ($a, $b) {
    return strcmp($a['date'], $b['date']);
});
$events = array_slice($events, 0, 3);

$ministries = db_all('ministry_content');
usort($ministries, function ($a, $b) {
    return strcmp($a['title'], $b['title']);
});
$ministries = array_slice($ministries, 0, 6);

$settings = [];
foreach (db_all('settings') as $s) $settings[$s['key']] = $s['value'];

$churchName = $settings['church_name'] ?? 'Iglesia Eben-Ezer';
$churchHours = $settings['church_hours'] ?? '';
$churchAddress = $settings['church_address'] ?? '';
$churchPhone = $settings['church_phone'] ?? '';
$churchEmail = $settings['church_email'] ?? '';
$facebookUrl = $settings['facebook_url'] ?? '#';
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($churchAddress ?: $churchName);

require_once dirname(__DIR__) . '/partials/header.php';
?>

<div class="home-page">

    <!-- 1. Hero full-bleed -->
    <section class="home-section home-hero">
        <img src="<?= url('assets/images/actividades.jpg') ?>" class="home-hero-media" alt="Actividad principal de la iglesia">
        <div class="home-hero-content">
            <p class="home-hero-badge">Bienvenidos a casa</p>
            <h2><?= htmlspecialchars($churchName) ?></h2>
            <p>Una comunidad de fe, esperanza y amor. Te invitamos a ser parte de nuestra familia.</p>
            <ul class="home-hero-meta">
                <?php if ($churchHours): ?>
                    <li>🕒 <?= htmlspecialchars($churchHours) ?></li>
                <?php endif; ?>
                <?php if ($churchAddress): ?>
                    <li>📍 <?= htmlspecialchars($churchAddress) ?></li>
                <?php endif; ?>
            </ul>
            <div class="home-actions">
                <a class="btn-primary" href="<?= url('contact') ?>">Planifica tu visita</a>
                <a class="btn-secondary" href="<?= url('decision') ?>">Quiero entregarme a Jesús</a>
            </div>
        </div>
    </section>

    <!-- 2. Service strip: horarios y ubicación -->
    <section class="home-section home-section--alt">
        <div class="home-inner">
            <p class="home-kicker">Visítanos</p>
            <h2 class="home-title">Horarios y <span class="text-accent">ubicación</span></h2>
            <p class="home-lead">Acompáñanos en nuestros cultos. Toda la familia es bienvenida.</p>
            <div class="home-service-grid">
                <div class="home-service-card">
                    <h3>🕒 Horario</h3>
                    <p><?= htmlspecialchars($churchHours ?: 'Consulta nuestros horarios') ?></p>
                </div>
                <div class="home-service-card">
                    <h3>📍 Dirección</h3>
                    <p><?= htmlspecialchars($churchAddress ?: 'Huánuco — Perú') ?></p>
                </div>
                <div class="home-service-card">
                    <h3>📞 Contacto</h3>
                    <p><?= htmlspecialchars($churchPhone ?: $churchEmail) ?></p>
                </div>
            </div>
            <div class="home-actions">
                <a class="btn-primary" href="<?= htmlspecialchars($mapsUrl) ?>" target="_blank" rel="noopener">Cómo llegar</a>
                <a class="btn-outline" href="<?= url('events') ?>">Ver eventos</a>
            </div>
        </div>
    </section>

    <!-- 3. Events preview -->
    <section class="home-section">
        <div class="home-inner">
            <p class="home-kicker">Agenda</p>
            <h2 class="home-title">Próximos <span class="text-accent">eventos</span></h2>
            <p class="home-lead">Actividades, reuniones y celebraciones de nuestra congregación.</p>
            <?php if (empty($events)): ?>
                <p class="home-empty">Próximamente anunciaremos nuevas actividades.</p>
            <?php else: ?>
                <div class="home-grid">
                    <?php foreach ($events as $ev): ?>
                        <article class="ministry-card">
                            <img src="<?= htmlspecialchars(url($ev['image'] ?: 'assets/images/actividades.jpg')) ?>" alt="<?= htmlspecialchars($ev['title']) ?>">
                            <div class="ministry-card-body">
                                <p class="ministry-card-kicker">Evento</p>
                                <h3><?= htmlspecialchars($ev['title']) ?></h3>
                                <p><?= htmlspecialchars($ev['description'] ?: 'Próximamente más detalles.') ?></p>
                                <?php if ($ev['date']): ?>
                                    <p><strong>📅 <?= date('d/m/Y', strtotime($ev['date'])) ?></strong></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="home-actions">
                <a class="btn-outline" href="<?= url('events') ?>">Ver todos los eventos</a>
            </div>
        </div>
    </section>

    <!-- 4. Ministries preview -->
    <section class="home-section home-section--alt">
        <div class="home-inner">
            <p class="home-kicker">Sirve con nosotros</p>
            <h2 class="home-title">Nuestros <span class="text-accent">ministerios</span></h2>
            <p class="home-lead">Encuentra tu lugar para crecer y servir en la obra de Dios.</p>
            <div class="home-grid">
                <?php foreach ($ministries as $m): ?>
                    <article class="ministry-card">
                        <img src="<?= url('assets/images/actividades.jpg') ?>" alt="<?= htmlspecialchars($m['title']) ?>">
                        <div class="ministry-card-body">
                            <p class="ministry-card-kicker"><?= strpos($m['slug'], 'direccion') !== false ? 'Dirección' : 'Ministerio' ?></p>
                            <h3><?= htmlspecialchars($m['title']) ?></h3>
                            <p><?= htmlspecialchars($m['description'] ?: 'Más información próximamente.') ?></p>
                            <a class="card-link" href="<?= url($m['slug']) ?>">Conocer más →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="home-actions">
                <a class="btn-outline" href="<?= url('ministries') ?>">Conocer todos los ministerios</a>
            </div>
        </div>
    </section>

    <!-- 5. Verse band -->
    <section class="home-section home-verse">
        <div class="home-inner">
            <blockquote>
                "Porque de tal manera amó Dios al mundo, que ha dado a su Hijo unigénito, para que todo aquel que en él cree, no se pierda, mas tenga vida eterna."
                <cite>— Juan 3:16</cite>
            </blockquote>
        </div>
    </section>

    <!-- 6. Decision CTA -->
    <section class="home-section home-decision">
        <div class="home-inner">
            <p class="home-kicker">Da el paso de fe</p>
            <h2 class="home-title">¿Quieres entregar tu vida a Jesús?</h2>
            <p class="home-lead">Si sientes en tu corazón que es momento de dar este paso, queremos acompañarte. Completa el formulario y nos pondremos en contacto contigo.</p>
            <div class="home-actions">
                <a class="btn-primary" href="<?= url('decision') ?>">Quiero entregarme a Jesús</a>
            </div>
        </div>
    </section>

    <!-- 7. Donation band -->
    <section class="home-section home-section--alt">
        <div class="home-inner">
            <p class="home-kicker">Generosidad</p>
            <h2 class="home-title">Donaciones</h2>
            <p class="home-lead">Tu generosidad nos ayuda a seguir extendiendo el evangelio.</p>
            <div class="home-donate-box">
                <div>
                    <h3>❤️ Apoya la obra con Yape</h3>
                    <p>"Dios ama al dador alegre." — 2 Corintios 9:7</p>
                </div>
                <button class="btn-donate" onclick="document.getElementById('donationModal').classList.add('is-visible')">Donar por Yape</button>
            </div>
        </div>
    </section>

    <!-- 8. Contact: same block as /contact -->
    <section class="home-section">
        <div class="home-inner">
            <?php if (!empty($success)): ?>
                <div class="flash flash-success" style="padding:16px; border-radius:8px; background:#e8f8ef; color:#27ae60; border:1px solid #27ae60; margin-bottom:18px;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            <?php require dirname(__DIR__) . '/partials/contact-block.php'; ?>
        </div>
    </section>

</div>

<div class="modal-overlay" id="donationModal" onclick="if(event.target===this)this.classList.remove('is-visible')">
    <div class="modal-box modal-donation">
        <button class="modal-close" onclick="this.closest('.modal-overlay').classList.remove('is-visible')">&times;</button>
        <h3>Donaciones</h3>
        <p class="donation-subtitle">Tu generosidad nos ayuda a seguir adelante</p>
        <img src="<?= url('assets/images/actividades.jpg') ?>" alt="QR Yape" class="qr-yape">
        <div class="yape-info">
            <p class="yape-number">📱 Yape: <strong><?= htmlspecialchars($churchPhone ?: '+51 913 629 693') ?></strong></p>
            <p class="yape-thanks">¡Gracias por tu contribución! Dios bendiga tu generosidad.</p>
            <p class="yape-verse"><em>"Cada uno dé como propuso en su corazón: no con tristeza, ni por necesidad, porque Dios ama al dador alegre."</em></p>
            <p class="yape-verse-ref">— 2 Corintios 9:7</p>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>
