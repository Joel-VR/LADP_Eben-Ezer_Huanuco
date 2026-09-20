<?php
/**
 * Shared contact block — "QUEREMOS CONOCERTE" form + info panel.
 * Used identically by views/pages/contact.php and views/pages/home.php.
 * Requires: $settings array. Form POSTs to the current URL.
 */
?>
<section class="contact-section">
    <div class="contact-content">
        <div class="contact-form-container">
            <div class="contact-header">
                <img src="<?= url('assets/images/LogoEbenezer.png') ?>" class="contact-logo" alt="Logo Iglesia Eben-Ezer">
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
