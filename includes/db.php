<?php
/**
 * Simple JSON-based data layer.
 * No PHP extensions required. Data stored in data/*.json files.
 */

$dataDir = __DIR__ . '/../data';
if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);

function db_read($table) {
    global $dataDir;
    $file = $dataDir . '/' . $table . '.json';
    if (!file_exists($file)) return [];
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

function db_write($table, $data) {
    global $dataDir;
    $file = $dataDir . '/' . $table . '.json';
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

function db_insert($table, $row) {
    $data = db_read($table);
    $row['id'] = count($data) > 0 ? max(array_column($data, 'id')) + 1 : 1;
    $row['created_at'] = date('Y-m-d H:i:s');
    $row['updated_at'] = date('Y-m-d H:i:s');
    $data[] = $row;
    db_write($table, $data);
    return $row['id'];
}

function db_update($table, $id, $updates) {
    $data = db_read($table);
    foreach ($data as &$row) {
        if ($row['id'] == $id) {
            foreach ($updates as $k => $v) $row[$k] = $v;
            $row['updated_at'] = date('Y-m-d H:i:s');
            break;
        }
    }
    db_write($table, $data);
}

function db_delete($table, $id) {
    $data = db_read($table);
    $data = array_filter($data, function($row) use ($id) { return $row['id'] != $id; });
    $data = array_values($data);
    db_write($table, $data);
}

function db_find($table, $id) {
    $data = db_read($table);
    foreach ($data as $row) {
        if ($row['id'] == $id) return $row;
    }
    return null;
}

function db_find_by($table, $key, $value) {
    $data = db_read($table);
    foreach ($data as $row) {
        if (isset($row[$key]) && $row[$key] == $value) return $row;
    }
    return null;
}

function db_all($table) {
    return db_read($table);
}

function db_where($table, $key, $value) {
    $data = db_read($table);
    return array_values(array_filter($data, function($row) use ($key, $value) {
        return isset($row[$key]) && $row[$key] == $value;
    }));
}

/**
 * Initialize default data if files don't exist.
 */
function db_init() {
    global $dataDir;

    // Admin users
    if (!file_exists($dataDir . '/admin_users.json')) {
        db_write('admin_users', [[
            'id' => 1,
            'username' => 'admin',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]]);
    }

    // Settings
    if (!file_exists($dataDir . '/settings.json')) {
        db_write('settings', [
            ['id' => 1, 'key' => 'church_name', 'value' => 'Iglesia Eben-Ezer', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'key' => 'church_email', 'value' => 'sedenacional@ladp.org.pe', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'key' => 'church_phone', 'value' => '+51 913 629 693 | (01) 4236207', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'key' => 'church_address', 'value' => 'Av. Colombia 325, Pueblo Libre', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'key' => 'church_hours', 'value' => 'Cierra a las 6 p.m.', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 6, 'key' => 'facebook_url', 'value' => 'https://www.facebook.com/profile.php?id=100067152944125&locale=es_LA', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);
    }

    // Ministry content
    if (!file_exists($dataDir . '/ministry_content.json')) {
        $ministries = [
            ['slug' => 'ministerio-jovenes', 'title' => 'Ministerio de Jóvenes', 'description' => 'Formación, servicio y acompañamiento espiritual', 'leaders' => '', 'activities' => '', 'videos' => '', 'photos' => ''],
            ['slug' => 'ministerio-ninos', 'title' => 'Ministerio de Niños', 'description' => 'Enseñanza bíblica y acompañamiento para la niñez', 'leaders' => '', 'activities' => '', 'videos' => '', 'photos' => ''],
            ['slug' => 'ministerio-familia', 'title' => 'Ministerio de Familia', 'description' => 'Fortalecimiento de hogares y relaciones saludables', 'leaders' => '', 'activities' => '', 'videos' => '', 'photos' => ''],
            ['slug' => 'ministerio-evangelismo', 'title' => 'Ministerio de Evangelismo', 'description' => 'Alcance y proclamación del evangelio', 'leaders' => '', 'activities' => '', 'videos' => '', 'photos' => ''],
            ['slug' => 'direccion-misiones', 'title' => 'Dirección de Misiones', 'description' => 'Expansión, anexos y apoyo misionero', 'leaders' => '', 'activities' => '', 'videos' => '', 'photos' => ''],
            ['slug' => 'direccion-comunicaciones', 'title' => 'Dirección de Comunicaciones', 'description' => 'Comunicación institucional y contenido digital', 'leaders' => '', 'activities' => '', 'videos' => '', 'photos' => ''],
        ];
        $data = [];
        foreach ($ministries as $i => $m) {
            $m['id'] = $i + 1;
            $m['created_at'] = date('Y-m-d H:i:s');
            $m['updated_at'] = date('Y-m-d H:i:s');
            $data[] = $m;
        }
        db_write('ministry_content', $data);
    }

    // Events
    if (!file_exists($dataDir . '/events.json')) {
        db_write('events', [
            ['id' => 1, 'title' => 'Aniversario de la Iglesia', 'description' => 'Celebra con nosotros el aniversario de nuestra congregación.', 'date' => '2026-02-20', 'image' => 'img/actividades.jpg', 'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'title' => 'Reunión de Oración', 'description' => 'Momento especial de oración e intercesión.', 'date' => '2026-06-15', 'image' => 'img/actividades.jpg', 'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'title' => 'Campaña Evangelística', 'description' => 'Alcanzando vidas con el evangelio de Cristo.', 'date' => '2026-08-10', 'image' => 'img/actividades.jpg', 'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 4, 'title' => 'Conferencia de Familias', 'description' => 'Fortaleciendo hogares y relaciones.', 'date' => '2026-09-05', 'image' => 'img/actividades.jpg', 'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['id' => 5, 'title' => 'Culto Juvenil Especial', 'description' => 'Noche de alabanza y palabra para jóvenes.', 'date' => '2026-07-20', 'image' => 'img/actividades.jpg', 'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);
    }

    // Empty tables
    if (!file_exists($dataDir . '/contact_messages.json')) {
        db_write('contact_messages', []);
    }

    // Decisiones
    if (!file_exists($dataDir . '/decisiones.json')) {
        db_write('decisiones', []);
    }
}

db_init();