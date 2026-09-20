<?php
/**
 * Admin route map: slug -> controller in admin/ + view in views/admin/.
 * Controllers stay in admin/ (thin), templates in views/admin/.
 */
return [
    'dashboard' => ['controller' => 'admin/index.php', 'view' => 'admin/dashboard.php', 'title' => 'Admin — Dashboard'],
    'events' => ['controller' => 'admin/events.php', 'view' => 'admin/events.php', 'title' => 'Admin — Eventos'],
    'ministries' => ['controller' => 'admin/ministries.php', 'view' => 'admin/ministries.php', 'title' => 'Admin — Ministerios'],
    'decisions' => ['controller' => 'admin/decisions.php', 'view' => 'admin/decisions.php', 'title' => 'Admin — Decisiones por Cristo'],
    'messages' => ['controller' => 'admin/messages.php', 'view' => 'admin/messages.php', 'title' => 'Admin — Mensajes'],
    'settings' => ['controller' => 'admin/settings.php', 'view' => 'admin/settings.php', 'title' => 'Admin — Configuración'],
    'login' => ['controller' => 'admin/login.php', 'view' => 'admin/login.php', 'title' => 'Admin — Login'],
];
