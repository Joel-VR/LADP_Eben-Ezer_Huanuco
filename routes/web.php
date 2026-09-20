<?php
/**
 * Public route map: clean URL slug -> view file + defaults.
 * Router: public/index.php
 */
return [
    '' => ['view' => 'pages/home.php', 'title' => 'Iglesia Eben-Ezer', 'desc' => 'Iglesia de avivamiento y evangelismo'],
    'home' => ['view' => 'pages/home.php', 'title' => 'Iglesia Eben-Ezer', 'desc' => 'Iglesia de avivamiento y evangelismo'],
    'history' => ['view' => 'pages/history.php', 'title' => 'Historia', 'desc' => 'Conoce nuestros inicios y trayectoria'],
    'mission-vision' => ['view' => 'pages/mission-vision.php', 'title' => 'Misión y Visión', 'desc' => 'Nuestros principios y objetivos como iglesia'],
    'ministries' => ['view' => 'pages/ministries.php', 'title' => 'Ministerios y Direcciones', 'desc' => 'Conoce nuestras áreas de trabajo y servicio ministerial'],
    'directions' => ['view' => 'pages/directions.php', 'title' => 'Direcciones', 'desc' => 'Direcciones y áreas de coordinación'],
    'events' => ['view' => 'pages/events.php', 'title' => 'Eventos', 'desc' => 'Actividades, reuniones y agenda de la iglesia'],
    'contact' => ['view' => 'pages/contact.php', 'title' => 'Contacto', 'desc' => 'Estamos aquí para ayudarte y acompañarte'],
    'decision' => ['view' => 'pages/decision.php', 'title' => 'Quiero Entregarme a Jesús', 'desc' => 'Da el paso más importante de tu vida'],
    'daily-reading' => ['view' => 'pages/daily-reading.php', 'title' => 'Lectura diaria', 'desc' => 'Lectura diaria de la Palabra'],
    // Ministries (English file names, Spanish slugs kept as alias below)
    'ministry-youth' => ['view' => 'pages/ministry-youth.php', 'slug' => 'ministerio-jovenes'],
    'ministry-kids' => ['view' => 'pages/ministry-kids.php', 'slug' => 'ministerio-ninos'],
    'ministry-family' => ['view' => 'pages/ministry-family.php', 'slug' => 'ministerio-familia'],
    'ministry-evangelism' => ['view' => 'pages/ministry-evangelism.php', 'slug' => 'ministerio-evangelismo'],
    'direction-missions' => ['view' => 'pages/direction-missions.php', 'slug' => 'direccion-misiones'],
    'direction-communications' => ['view' => 'pages/direction-communications.php', 'slug' => 'direccion-comunicaciones'],
    // Spanish aliases (old slugs) -> same views
    'ministerio-jovenes' => ['view' => 'pages/ministry-youth.php', 'slug' => 'ministerio-jovenes'],
    'ministerio-ninos' => ['view' => 'pages/ministry-kids.php', 'slug' => 'ministerio-ninos'],
    'ministerio-familia' => ['view' => 'pages/ministry-family.php', 'slug' => 'ministerio-familia'],
    'ministerio-evangelismo' => ['view' => 'pages/ministry-evangelism.php', 'slug' => 'ministerio-evangelismo'],
    'direccion-misiones' => ['view' => 'pages/direction-missions.php', 'slug' => 'direccion-misiones'],
    'direccion-comunicaciones' => ['view' => 'pages/direction-communications.php', 'slug' => 'direccion-comunicaciones'],
    'historia' => ['view' => 'pages/history.php', 'title' => 'Historia', 'desc' => 'Conoce nuestros inicios y trayectoria'],
    'mision-vision' => ['view' => 'pages/mission-vision.php', 'title' => 'Misión y Visión', 'desc' => 'Nuestros principios y objetivos como iglesia'],
    'ministerios' => ['view' => 'pages/ministries.php', 'title' => 'Ministerios y Direcciones', 'desc' => 'Conoce nuestras áreas de trabajo y servicio ministerial'],
    'direcciones' => ['view' => 'pages/directions.php', 'title' => 'Direcciones', 'desc' => 'Direcciones y áreas de coordinación'],
    'eventos' => ['view' => 'pages/events.php', 'title' => 'Eventos', 'desc' => 'Actividades, reuniones y agenda de la iglesia'],
    'contacto' => ['view' => 'pages/contact.php', 'title' => 'Contacto', 'desc' => 'Estamos aquí para ayudarte y acompañarte'],
    'decision-entrega' => ['view' => 'pages/decision.php', 'title' => 'Quiero Entregarme a Jesús', 'desc' => 'Da el paso más importante de tu vida'],
    'lectura-diaria' => ['view' => 'pages/daily-reading.php', 'title' => 'Lectura diaria', 'desc' => 'Lectura diaria de la Palabra'],
];
