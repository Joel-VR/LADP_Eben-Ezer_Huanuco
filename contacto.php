<?php
$pageTitle = 'Contacto';
$pageDesc = 'Estamos aquí para ayudarte y acompañarte';
$includeContact = true;

require_once 'includes/db.php';

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

$settings = [];
foreach (db_all('settings') as $s) $settings[$s['key']] = $s['value'];

require_once 'includes/header.php';
?>

<main class="container">
    <?php if (!empty($success)): ?>
        <div class="flash flash-success" style="padding:16px; border-radius:8px; background:#e8f8ef; color:#27ae60; border:1px solid #27ae60; margin-bottom:18px;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <section class="card">
        <h2>Canales de Contacto</h2>
        <p><strong>Dirección:</strong> <?= htmlspecialchars($settings['church_address'] ?? 'Av. Colombia 325, Pueblo Libre') ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($settings['church_phone'] ?? '+51 913 629 693') ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($settings['church_email'] ?? 'sedenacional@ladp.org.pe') ?></p>
        <p><strong>Horario:</strong> <?= htmlspecialchars($settings['church_hours'] ?? 'Cierra a las 6 p.m.') ?></p>
        <a href="<?= htmlspecialchars($settings['facebook_url'] ?? '#') ?>" target="_blank">
            <img src="img/Logo_de_Facebook.png" class="facebook" alt="Facebook Iglesia Eben-Ezer">
        </a>
    </section>

    <section class="contact-section">
        <div class="contact-content">
            <div class="contact-form-container">
                <div class="contact-header">
                    <img src="img/LOGOS EBEN CON BORDE.png" class="contact-logo" alt="Logo Iglesia Eben-Ezer">
                    <div>
                        <h2>QUEREMOS CONOCERTE</h2>
                        <p>¡Estamos aquí para ayudarte! Si tienes preguntas, comentarios o necesitas asistencia, no dudes en ponerte en contacto con nosotros.</p>
                    </div>
                </div>
                <form class="contact-form" method="POST">
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
                    <li>✉ <?= htmlspecialchars($settings['church_email'] ?? '') ?></li>
                    <li>📞 <?= htmlspecialchars($settings['church_phone'] ?? '') ?></li>
                    <li>📍 <?= htmlspecialchars($settings['church_address'] ?? '') ?></li>
                    <li>🕒 <?= htmlspecialchars($settings['church_hours'] ?? '') ?></li>
                </ul>
                <div class="social-links">
                    <a href="<?= htmlspecialchars($settings['facebook_url'] ?? '#') ?>" aria-label="Facebook" target="_blank">f</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
