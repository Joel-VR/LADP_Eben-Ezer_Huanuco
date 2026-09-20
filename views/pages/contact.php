<?php
$pageTitle = 'Contacto';
$pageDesc = 'Estamos aquí para ayudarte y acompañarte';
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

$settings = [];
foreach (db_all('settings') as $s) $settings[$s['key']] = $s['value'];

require_once dirname(__DIR__) . '/partials/header.php';
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
            <img src="assets/images/Logo_de_Facebook.png" class="facebook" alt="Facebook Iglesia Eben-Ezer">
        </a>
    </section>

    <?php require dirname(__DIR__) . '/partials/contact-block.php'; ?>
</main>

<?php require_once dirname(__DIR__) . '/partials/footer.php'; ?>