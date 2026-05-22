<?php
require_once 'includes/db.php';
$ministry = db_find_by('ministry_content', 'slug', 'direccion-comunicaciones');
$pageTitle = $ministry['title'] ?? 'Dirección de Comunicaciones';
$pageDesc = $ministry['description'] ?? 'Comunicación institucional y contenido digital';
require_once 'includes/header.php';
?>

<main class="container">
    
    <!-- Sección de Redes Sociales -->
    <?php if (!empty($ministry['social_media']['facebook_url']) || !empty($ministry['social_media']['instagram_url']) || !empty($ministry['social_media']['youtube_url']) || !empty($ministry['social_media']['tiktok_url'])): ?>
    <section class="card comms-social-section">
        <h2>📱 Síguenos en Redes Sociales</h2>
        <div class="social-media-hub">
            <?php if (!empty($ministry['social_media']['facebook_url'])): ?>
                <a href="<?= htmlspecialchars($ministry['social_media']['facebook_url']) ?>" target="_blank" rel="noopener" class="social-link facebook" title="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5c-.563-.074-1.823-.143-3.005-.143-3.476 0-5.995 2.166-5.995 6.134V8z"/></svg>
                    <span>Facebook</span>
                </a>
            <?php endif; ?>
            <?php if (!empty($ministry['social_media']['instagram_url'])): ?>
                <a href="<?= htmlspecialchars($ministry['social_media']['instagram_url']) ?>" target="_blank" rel="noopener" class="social-link instagram" title="Instagram">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.057-1.645.069-4.849.069-3.203 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 100-8 4 4 0 000 8zm4.965-10.322a1.44 1.44 0 110-2.88 1.44 1.44 0 010 2.88z"/></svg>
                    <span>Instagram</span>
                </a>
            <?php endif; ?>
            <?php if (!empty($ministry['social_media']['youtube_url'])): ?>
                <a href="<?= htmlspecialchars($ministry['social_media']['youtube_url']) ?>" target="_blank" rel="noopener" class="social-link youtube" title="YouTube">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    <span>YouTube</span>
                </a>
            <?php endif; ?>
            <?php if (!empty($ministry['social_media']['tiktok_url'])): ?>
                <a href="<?= htmlspecialchars($ministry['social_media']['tiktok_url']) ?>" target="_blank" rel="noopener" class="social-link tiktok" title="TikTok">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.86 2.86 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-.01-.01z"/></svg>
                    <span>TikTok</span>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Sección de Transmisiones en Vivo -->
    <?php if (!empty($ministry['live_streams'])): ?>
    <section class="card comms-livestream-section">
        <h2>📡 Transmisiones en Vivo</h2>
        <div class="livestream-container">
            <?php 
            $active_stream = null;
            $upcoming_streams = [];
            foreach ($ministry['live_streams'] as $stream) {
                if ($stream['is_active']) {
                    $active_stream = $stream;
                } else {
                    $upcoming_streams[] = $stream;
                }
            }
            ?>
            
            <?php if ($active_stream): ?>
                <div class="livestream-live">
                    <div class="livestream-badge">🔴 EN VIVO AHORA</div>
                    <h3><?= htmlspecialchars($active_stream['title']) ?></h3>
                    <p style="font-size: 0.95rem; color: #666; margin-bottom: 12px;">En: <?= htmlspecialchars($active_stream['platform']) ?></p>
                    <a href="<?= htmlspecialchars($active_stream['url']) ?>" target="_blank" rel="noopener" class="btn btn-livestream">Ver Transmisión →</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($upcoming_streams)): ?>
                <div class="livestream-upcoming">
                    <h3>Próximas Transmisiones</h3>
                    <div class="livestream-list">
                        <?php foreach ($upcoming_streams as $stream): ?>
                            <div class="livestream-item">
                                <div class="livestream-info">
                                    <span class="livestream-platform"><?= htmlspecialchars($stream['platform']) ?></span>
                                    <h4><?= htmlspecialchars($stream['title']) ?></h4>
                                </div>
                                <a href="<?= htmlspecialchars($stream['url']) ?>" target="_blank" rel="noopener" class="btn btn-small">Ir →</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Sección de Portafolio -->
    <?php if (!empty($ministry['portfolio_items'])): ?>
    <section class="card comms-portfolio-section">
        <h2>🎨 Nuestro Portafolio</h2>
        <p style="color: #666; margin-bottom: 20px;">Conoce nuestros trabajos, diseños y contenido creado</p>
        
        <?php 
        $featured = array_filter($ministry['portfolio_items'], function($item) { return $item['featured']; });
        $others = array_filter($ministry['portfolio_items'], function($item) { return !$item['featured']; });
        ?>
        
        <?php if (!empty($featured)): ?>
        <div class="portfolio-featured-section">
            <h3 style="margin-bottom: 16px; color: #333;">✨ Destacados</h3>
            <div class="portfolio-grid portfolio-featured">
                <?php foreach ($featured as $item): ?>
                    <div class="portfolio-card portfolio-featured-card">
                        <div class="portfolio-image">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <div class="portfolio-overlay">
                                <h4><?= htmlspecialchars($item['title']) ?></h4>
                                <?php if (!empty($item['category'])): ?><span class="portfolio-category"><?= htmlspecialchars($item['category']) ?></span><?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($item['description'])): ?>
                            <div class="portfolio-info">
                                <p><?= htmlspecialchars($item['description']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($others)): ?>
        <div class="portfolio-other-section" style="<?= !empty($featured) ? 'margin-top: 30px;' : '' ?>">
            <?php if (!empty($featured)): ?><h3 style="margin-bottom: 16px; color: #333;">Más Trabajos</h3><?php endif; ?>
            <div class="portfolio-grid portfolio-other">
                <?php foreach ($others as $item): ?>
                    <div class="portfolio-card">
                        <div class="portfolio-image">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <div class="portfolio-overlay">
                                <h4><?= htmlspecialchars($item['title']) ?></h4>
                                <?php if (!empty($item['category'])): ?><span class="portfolio-category"><?= htmlspecialchars($item['category']) ?></span><?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($item['description'])): ?>
                            <div class="portfolio-info">
                                <p><?= htmlspecialchars($item['description']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>
    
    <section class="card"><h2>Líderes</h2><p><?= nl2br(htmlspecialchars($ministry['leaders'] ?: 'Información de líderes próximamente.')) ?></p></section>
    <?php if (!empty($ministry['photos'])): ?>
    <section class="card"><h2>Fotos</h2><div class="event-grid"><?php foreach (array_filter(array_map('trim', explode("\n", $ministry['photos']))) as $photo): ?><img src="<?= htmlspecialchars($photo) ?>" alt="Foto" class="actividades"><?php endforeach; ?></div></section>
    <?php endif; ?>
    <?php if (!empty($ministry['videos'])): ?>
    <section class="card"><h2>Videos</h2><?php foreach (array_filter(array_map('trim', explode("\n", $ministry['videos']))) as $video): ?><?php if (preg_match('/v=([a-zA-Z0-9_-]+)/', $video, $m)): ?><div style="margin-bottom:16px;"><iframe width="100%" height="315" src="https://www.youtube.com/embed/<?= htmlspecialchars($m[1]) ?>" frameborder="0" allowfullscreen style="border-radius:10px;"></iframe></div><?php endif; ?><?php endforeach; ?></section>
    <?php endif; ?>
    <section class="card"><h2>Actividades</h2><p><?= nl2br(htmlspecialchars($ministry['activities'] ?: 'Información de actividades próximamente.')) ?></p></section>
</main>

<?php require_once 'includes/footer.php'; ?>
