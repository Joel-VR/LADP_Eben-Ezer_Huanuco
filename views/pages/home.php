<?php
$pageTitle = 'Iglesia Eben-Ezer';
$pageDesc = 'Iglesia de avivamiento y evangelismo';
$currentPage = 'home';
$includeContact = true;

require_once dirname(__DIR__, 2) . '/src/database.php';

$events = array_filter(db_all('events'), function($e) { return $e['active'] == 1; });
usort($events, function($a, $b) { return strcmp($a['date'], $b['date']); });
$events = array_slice($events, 0, 4);

$settings = [];
foreach (db_all('settings') as $s) $settings[$s['key']] = $s['value'];

require_once dirname(__DIR__) . '/partials/header.php';
?>

<main class="container">
    <section class="hero-banner card">
        <img src="assets/images/actividades.jpg" class="hero-image" alt="Actividad principal de la iglesia">
        <div class="hero-overlay">
            <h2>Bienvenidos a <?= htmlspecialchars($settings['church_name'] ?? 'Iglesia Eben-Ezer') ?></h2>
            <p>Una comunidad de fe, esperanza y amor. Te invitamos a ser parte de nuestra familia.</p>
            <a class="btn-primary" href="/events">Ver próximos eventos</a>
        </div>
    </section>

    <section class="card">
        <h2>Eventos Destacados</h2>
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

<section class="container contact-section">
    <div class="contact-content">
        <div class="contact-form-container">
            <div class="contact-header">
                <img src="assets/images/LOGOS EBEN CON BORDE.png" class="contact-logo" alt="Logo Iglesia Eben-Ezer">
                <div>
                    <h2>QUEREMOS CONOCERTE</h2>
                    <p>¡Estamos aquí para ayudarte! Si tienes preguntas, comentarios o necesitas asistencia, no dudes en ponerte en contacto con nosotros.</p>
                </div>
            </div>
            <form class="contact-form" method="POST" action="/contact">
                <input type="text" name="name" placeholder="Nombre Completo" required>
                <input type="tel" name="phone" placeholder="Teléfono">
                <input type="email" name="email" placeholder="Email">
                <textarea name="message" placeholder="Mensaje" required></textarea>
                <button type="submit" name="submit_contact">ENVIAR MENSAJE</button>
            </form>
        </div>
        <div class="contact-info-container">
            <h3>Info</h3>
            <ul>
                <li>✉ <?= htmlspecialchars($settings['church_email'] ?? 'sedenacional@ladp.org.pe') ?></li>
                <li>📞 <?= htmlspecialchars($settings['church_phone'] ?? '+51 913 629 693 | (01) 4236207') ?></li>
                <li>📍 <?= htmlspecialchars($settings['church_address'] ?? 'Av. Colombia 325, Pueblo Libre') ?></li>
                <li>🕒 <?= htmlspecialchars($settings['church_hours'] ?? 'Cierra a las 6 p.m.') ?></li>
            </ul>
            <div class="social-links">
                <a href="<?= htmlspecialchars($settings['facebook_url'] ?? '#') ?>" aria-label="Facebook" target="_blank">f</a>
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <button class="btn-donate" onclick="document.getElementById('donationModal').classList.add('is-visible')">❤️ Donaciones</button>
            </div>
        </div>
    </div>
</section>

<div class="modal-overlay" id="donationModal" onclick="if(event.target===this)this.classList.remove('is-visible')">
    <div class="modal-box modal-donation">
        <button class="modal-close" onclick="this.closest('.modal-overlay').classList.remove('is-visible')">&times;</button>
        <h3>Donaciones</h3>
        <p class="donation-subtitle">Tu generosidad nos ayuda a seguir adelante</p>
        <img src="assets/images/actividades.jpg" alt="QR Yape" class="qr-yape">
        <div class="yape-info">
            <p class="yape-number">📱 Yape: <strong><?= htmlspecialchars($settings['church_phone'] ?? '+51 913 629 693') ?></strong></p>
            <p class="yape-thanks">¡Gracias por tu contribución! Dios bendiga tu generosidad.</p>
            <p class="yape-verse"><em>"Cada uno dé como propuso en su corazón: no con tristeza, ni por necesidad, porque Dios ama al dador alegre."</em></p>
            <p class="yape-verse-ref">— 2 Corintios 9:7</p>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>
