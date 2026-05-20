<?php
$pageTitle = 'Lectura diaria';
include __DIR__ . '/includes/header.php';

$playlistId = 'PLYN0pjnHerR9fXFQ7gcBa3ds77QptmiBv';
$feedUrl = "https://www.youtube.com/feeds/videos.xml?playlist_id={$playlistId}";
$videos = [];
$xml = @simplexml_load_file($feedUrl);
if ($xml !== false) {
    foreach ($xml->entry as $entry) {
        $yt = $entry->children('http://www.youtube.com/xml/schemas/2015');
        $videoId = (string) $yt->videoId;
        $title = (string) $entry->title;
        $published = (string) $entry->published;
        $thumb = "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";
        $videos[] = ['id' => $videoId, 'title' => $title, 'published' => $published, 'thumb' => $thumb];
    }
    usort($videos, function ($a, $b) {
        return strtotime($b['published']) - strtotime($a['published']);
    });
}
?>

<main class="container" style="max-width:1100px;margin:22px auto;padding:0 16px;">
    <h2>Lectura diaria</h2>

    <?php if (empty($videos)): ?>
        <p>No se pudieron obtener los videos. Verifica el `playlistId` en este archivo.</p>
    <?php else: ?>
        <div class="reading-grid" style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start;">
            <div>
                <div class="reading-player" style="background:#000;border-radius:8px;overflow:hidden;">
                    <iframe id="mainPlayer" width="100%" height="480" src="https://www.youtube.com/embed/<?= htmlspecialchars($videos[0]['id']) ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <h3 id="currentTitle" style="margin:10px 0 0;"><?= htmlspecialchars($videos[0]['title']) ?></h3>
            </div>

            <aside>
                <h4 style="margin-top:0;">Últimas lecturas</h4>
                <div class="reading-list" style="display:flex;flex-direction:column;gap:10px;">
                    <?php foreach ($videos as $v): ?>
                        <button class="reading-item" data-video="<?= htmlspecialchars($v['id']) ?>" style="display:flex;gap:10px;align-items:center;border:0;background:transparent;text-align:left;cursor:pointer;padding:6px;border-radius:6px;">
                            <img src="<?= htmlspecialchars($v['thumb']) ?>" alt="" style="width:110px;height:62px;object-fit:cover;border-radius:6px;">
                            <div style="flex:1;">
                                <div style="font-weight:700;font-size:0.95rem;"><?= htmlspecialchars($v['title']) ?></div>
                                <div style="color:#666;font-size:0.85rem;"><?= date('d/m/Y', strtotime($v['published'])) ?></div>
                            </div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </aside>
        </div>

        <script>
            document.querySelectorAll('.reading-item').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var id = this.getAttribute('data-video');
                    var iframe = document.getElementById('mainPlayer');
                    iframe.src = 'https://www.youtube.com/embed/' + id + '?rel=0&autoplay=1';
                    var title = this.querySelector('div > div').innerText;
                    document.getElementById('currentTitle').innerText = title;
                });
            });
        </script>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/includes/footer.php';
