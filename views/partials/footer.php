<?php
/**
 * Shared footer. Location: views/partials/footer.php
 * Institutional dark footer (LADP-aligned) + floating WhatsApp button.
 */
$footerFacebook = '#';
if (isset($settings['facebook_url'])) $footerFacebook = $settings['facebook_url'];
$waNumber = '51913629693';
$waText = urlencode('Hola Iglesia Eben-Ezer, ¿pueden ayudarme?');
$waLink = 'https://wa.me/' . $waNumber . '?text=' . $waText;
?>
<footer class="site-footer">
    <div class="site-footer-inner">
        <div>
            <h3>Iglesia Eben-Ezer</h3>
            <p>Una comunidad de avivamiento y evangelismo en Huánuco — Perú.</p>
            <div class="site-footer-social">
                <a href="<?= htmlspecialchars($footerFacebook) ?>" aria-label="Facebook" target="_blank" rel="noopener">f</a>
                <a href="<?= htmlspecialchars($waLink) ?>" aria-label="WhatsApp" target="_blank" rel="noopener">w</a>
            </div>
        </div>
        <div>
            <h3>Contacto</h3>
            <ul>
                <li>✉ sedenacional@ladp.org.pe</li>
                <li>📞 +51 913 629 693</li>
                <li>📍 Av. Colombia 325, Pueblo Libre</li>
            </ul>
        </div>
        <div>
            <h3>Enlaces</h3>
            <ul>
                <li><a href="<?= url('history') ?>">Historia</a></li>
                <li><a href="<?= url('ministries') ?>">Ministerios</a></li>
                <li><a href="<?= url('events') ?>">Eventos</a></li>
                <li><a href="<?= url('decision') ?>">Quiero entregarme a Jesús</a></li>
            </ul>
        </div>
    </div>
    <div class="site-footer-bar">
        &copy; <?= date('Y') ?> Iglesia Eben-Ezer · Huánuco — Perú · Todos los derechos reservados
    </div>
</footer>

<a class="whatsapp-float" href="<?= htmlspecialchars($waLink) ?>" aria-label="Escríbenos por WhatsApp" target="_blank" rel="noopener">
    <svg viewBox="0 0 32 32"><path d="M16 3C9.4 3 4 8.4 4 15c0 2.4.7 4.6 1.9 6.5L4 29l7.7-1.8c1.8 1 3.9 1.5 4.3 1.5 6.6 0 12-5.4 12-12S22.6 3 16 3zm0 21.8c-1.2 0-2.4-.3-3.4-.9l-.2-.1-4.6 1.1 1.1-4.4-.2-.2c-.7-1.1-1.1-2.3-1.1-3.6C7.6 10.4 10.4 7.6 16 7.6S24.4 10.4 24.4 16 22 24.8 16 24.8zm4.6-6.9c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.6-.3-1.2-.5-1.7-1-.5-.4-.8-.9-1-1.2-.1-.2 0-.4.1-.5l.5-.6c.1-.2.2-.3.1-.5-.1-.2-.6-1.5-.9-2-.2-.5-.4-.5-.6-.5h-.5c-.2 0-.5.2-.7.3-.9.9-1.1 2.2-.2 3.9 1 1.9 2.7 3.7 4.7 4.7 1.7.9 2.6.9 3.5.8.6-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2 0-.1-.2-.1-.5-.2z"/></svg>
</a>

</body>
</html>
