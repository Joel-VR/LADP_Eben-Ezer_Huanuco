<?php
/**
 * Database layer — PDO Postgres (Neon) with SQLite fallback for local dev.
 *
 * Same public API as the old JSON layer so callers don't change:
 *   db_all, db_find, db_find_by, db_where, db_insert, db_update, db_delete,
 *   db_read, db_write
 *
 * Env:
 *   DATABASE_URL — Neon pooled URL, e.g. postgres://user:pass@host/db?sslmode=require
 *   DATA_DIR     — local dir for SQLite fallback (default <root>/data)
 *   ADMIN_USER / ADMIN_PASSWORD — seed credentials (default admin / admin123, change in prod)
 */

require_once __DIR__ . '/config.php';

$GLOBALS['__pdo'] = null;
$GLOBALS['__driver'] = null;

function db_driver(): string {
    if (!empty($GLOBALS['__driver'])) return $GLOBALS['__driver'];
    $url = getenv('DATABASE_URL');
    $GLOBALS['__driver'] = ($url && stripos($url, 'postgres') !== false) ? 'pgsql' : 'sqlite';
    return $GLOBALS['__driver'];
}

function db(): PDO {
    if (!empty($GLOBALS['__pdo'])) return $GLOBALS['__pdo'];

    if (db_driver() === 'pgsql') {
        $url = getenv('DATABASE_URL');
        $parts = parse_url($url);
        $host = $parts['host'] ?? 'localhost';
        $port = $parts['port'] ?? 5432;
        $user = isset($parts['user']) ? urldecode($parts['user']) : '';
        $pass = isset($parts['pass']) ? urldecode($parts['pass']) : '';
        $dbname = isset($parts['path']) ? ltrim($parts['path'], '/') : '';
        $query = $parts['query'] ?? '';
        parse_str($query, $q);
        $sslmode = $q['sslmode'] ?? 'require';
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode={$sslmode}";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_PERSISTENT => false,
        ]);
    } else {
        $dir = getenv('DATA_DIR') ?: (APP_ROOT . '/data');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $file = $dir . '/app.db';
        $pdo = new PDO('sqlite:' . $file, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    $GLOBALS['__pdo'] = $pdo;
    return $pdo;
}

/** Whitelist of tables (old JSON names kept for compatibility). */
function db_table(string $table): string {
    $allowed = ['events', 'ministry_content', 'decisiones', 'contact_messages', 'settings', 'admin_users'];
    if (!in_array($table, $allowed, true)) throw new InvalidArgumentException("Unknown table: {$table}");
    return '"' . $table . '"';
}

/** Columns that hold JSON (encoded on write, decoded on read). */
function db_json_columns(string $table): array {
    if ($table === 'ministry_content') return ['social_media', 'live_streams', 'portfolio_items'];
    return [];
}

function db_decode_row(string $table, array $row): array {
    foreach (db_json_columns($table) as $col) {
        if (array_key_exists($col, $row) && is_string($row[$col]) && $row[$col] !== '') {
            $d = json_decode($row[$col], true);
            if (json_last_error() === JSON_ERROR_NONE) $row[$col] = $d;
        }
    }
    return $row;
}

function db_encode_value(string $table, string $col, $value) {
    if (in_array($col, db_json_columns($table), true) && (is_array($value))) {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    if (is_bool($value)) return $value ? 1 : 0;
    return $value;
}

// ---- Reads ----

function db_all(string $table): array {
    return db_read($table);
}

function db_read(string $table): array {
    db_init();
    $t = db_table($table);
    $order = ($table === 'settings') ? 'ORDER BY id ASC' : 'ORDER BY id ASC';
    $rows = db()->query("SELECT * FROM {$t} {$order}")->fetchAll();
    return array_map(fn($r) => db_decode_row($table, $r), $rows);
}

function db_find(string $table, $id) {
    db_init();
    $t = db_table($table);
    $st = db()->prepare("SELECT * FROM {$t} WHERE id = :id LIMIT 1");
    $st->execute([':id' => $id]);
    $row = $st->fetch();
    return $row ? db_decode_row($table, $row) : null;
}

function db_find_by(string $table, string $key, $value) {
    db_init();
    $t = db_table($table);
    if (!preg_match('/^[a-z_]+$/', $key)) throw new InvalidArgumentException("Bad column: {$key}");
    $st = db()->prepare("SELECT * FROM {$t} WHERE \"{$key}\" = :v LIMIT 1");
    $st->execute([':v' => $value]);
    $row = $st->fetch();
    return $row ? db_decode_row($table, $row) : null;
}

function db_where(string $table, string $key, $value): array {
    db_init();
    $t = db_table($table);
    if (!preg_match('/^[a-z_]+$/', $key)) throw new InvalidArgumentException("Bad column: {$key}");
    $st = db()->prepare("SELECT * FROM {$t} WHERE \"{$key}\" = :v ORDER BY id ASC");
    $st->execute([':v' => $value]);
    $rows = $st->fetchAll();
    return array_map(fn($r) => db_decode_row($table, $r), $rows);
}

// ---- Writes ----

function db_insert(string $table, array $row): int {
    db_init();
    $t = db_table($table);
    unset($row['id']);
    $row['created_at'] = date('Y-m-d H:i:s');
    $row['updated_at'] = date('Y-m-d H:i:s');
    $cols = array_keys($row);
    $quoted = array_map(fn($c) => '"' . $c . '"', $cols);
    $placeholders = array_map(fn($c) => ':' . $c, $cols);
    $sql = "INSERT INTO {$t} (" . implode(',', $quoted) . ") VALUES (" . implode(',', $placeholders) . ")";
    $params = [];
    foreach ($row as $k => $v) $params[':' . $k] = db_encode_value($table, $k, $v);
    db()->prepare($sql)->execute($params);
    return (int)db()->lastInsertId();
}

function db_update(string $table, $id, array $updates): void {
    db_init();
    unset($updates['id']);
    $updates['updated_at'] = date('Y-m-d H:i:s');
    $t = db_table($table);
    $sets = [];
    $params = [':id' => $id];
    foreach ($updates as $k => $v) {
        if (!preg_match('/^[a-z_]+$/', $k)) continue;
        $sets[] = "\"{$k}\" = :{$k}";
        $params[':' . $k] = db_encode_value($table, $k, $v);
    }
    if (!$sets) return;
    db()->prepare("UPDATE {$t} SET " . implode(',', $sets) . " WHERE id = :id")->execute($params);
}

function db_delete(string $table, $id): void {
    db_init();
    $t = db_table($table);
    db()->prepare("DELETE FROM {$t} WHERE id = :id")->execute([':id' => $id]);
}

/** Compat: old code called db_write('settings', $allRows) — upsert each row by id/key. */
function db_write(string $table, array $data): void {
    db_init();
    if ($table === 'settings') {
        foreach ($data as $row) {
            if (isset($row['id'])) {
                $id = $row['id'];
                $updates = $row;
                unset($updates['id']);
                db_update($table, $id, $updates);
            } elseif (isset($row['key'])) {
                $existing = db_find_by($table, 'key', $row['key']);
                if ($existing) db_update($table, $existing['id'], ['value' => $row['value'] ?? '']);
                else db_insert($table, ['key' => $row['key'], 'value' => $row['value'] ?? '']);
            }
        }
        return;
    }
    if ($table === 'admin_users') {
        foreach ($data as $row) {
            if (isset($row['username'])) {
                $existing = db_find_by($table, 'username', $row['username']);
                if ($existing) {
                    $u = $row; unset($u['id']);
                    db_update($table, $existing['id'], $u);
                } else {
                    db_insert($table, $row);
                }
            }
        }
        return;
    }
    // Generic fallback (not used by current callers, kept for safety).
    foreach ($data as $row) {
        if (isset($row['id']) && db_find($table, $row['id'])) {
            $u = $row; unset($u['id']);
            db_update($table, $row['id'], $u);
        } else {
            db_insert($table, $row);
        }
    }
}

// ---- Schema + seed ----

function db_init(): void {
    static $done = false;
    if ($done) return;
    $done = true;
    $pdo = db();
    $driver = db_driver();

    $idCol = ($driver === 'pgsql') ? 'SERIAL PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $jsonType = ($driver === 'pgsql') ? 'JSONB' : 'TEXT';
    $tsDefault = ($driver === 'pgsql') ? "TIMESTAMPTZ DEFAULT NOW()" : "DATETIME DEFAULT CURRENT_TIMESTAMP";

    $pdo->exec("CREATE TABLE IF NOT EXISTS \"admin_users\" (id {$idCol}, username TEXT UNIQUE NOT NULL, password_hash TEXT NOT NULL, created_at {$tsDefault}, updated_at {$tsDefault})");
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"settings\" (id {$idCol}, \"key\" TEXT UNIQUE NOT NULL, value TEXT NOT NULL DEFAULT '', created_at {$tsDefault}, updated_at {$tsDefault})");
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"ministry_content\" (id {$idCol}, slug TEXT UNIQUE NOT NULL, title TEXT NOT NULL DEFAULT '', description TEXT NOT NULL DEFAULT '', leaders TEXT NOT NULL DEFAULT '', activities TEXT NOT NULL DEFAULT '', videos TEXT NOT NULL DEFAULT '', photos TEXT NOT NULL DEFAULT '', social_media {$jsonType}, live_streams {$jsonType}, portfolio_items {$jsonType}, created_at {$tsDefault}, updated_at {$tsDefault})");
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"events\" (id {$idCol}, title TEXT NOT NULL DEFAULT '', description TEXT NOT NULL DEFAULT '', \"date\" TEXT NOT NULL DEFAULT '', image TEXT NOT NULL DEFAULT '', active INTEGER NOT NULL DEFAULT 1, created_at {$tsDefault}, updated_at {$tsDefault})");
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"contact_messages\" (id {$idCol}, name TEXT NOT NULL DEFAULT '', phone TEXT NOT NULL DEFAULT '', email TEXT NOT NULL DEFAULT '', message TEXT NOT NULL DEFAULT '', \"read\" INTEGER NOT NULL DEFAULT 0, created_at {$tsDefault}, updated_at {$tsDefault})");
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"decisiones\" (id {$idCol}, nombres TEXT NOT NULL DEFAULT '', apellidos TEXT NOT NULL DEFAULT '', edad TEXT NOT NULL DEFAULT '', telefono TEXT NOT NULL DEFAULT '', direccion TEXT NOT NULL DEFAULT '', departamento TEXT NOT NULL DEFAULT '', provincia TEXT NOT NULL DEFAULT '', distrito TEXT NOT NULL DEFAULT '', email TEXT NOT NULL DEFAULT '', entrego INTEGER NOT NULL DEFAULT 0, reconcilio INTEGER NOT NULL DEFAULT 0, created_at {$tsDefault}, updated_at {$tsDefault})");

    // Seed admin
    $count = (int)$pdo->query('SELECT COUNT(*) AS c FROM "admin_users"')->fetch()['c'];
    if ($count === 0) {
        $user = getenv('ADMIN_USER') ?: 'admin';
        $pass = getenv('ADMIN_PASSWORD') ?: 'admin123';
        $st = $pdo->prepare('INSERT INTO "admin_users" (username, password_hash, created_at, updated_at) VALUES (:u, :h, :c, :c2)');
        $now = date('Y-m-d H:i:s');
        $st->execute([':u' => $user, ':h' => password_hash($pass, PASSWORD_DEFAULT), ':c' => $now, ':c2' => $now]);
    }

    // Seed settings
    $count = (int)$pdo->query('SELECT COUNT(*) AS c FROM "settings"')->fetch()['c'];
    if ($count === 0) {
        $defaults = [
            'church_name' => 'Iglesia Eben-Ezer',
            'church_email' => 'sedenacional@ladp.org.pe',
            'church_phone' => '+51 913 629 693 | (01) 4236207',
            'church_address' => 'Av. Colombia 325, Pueblo Libre',
            'church_hours' => 'Cierra a las 6 p.m.',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=100067152944125&locale=es_LA',
        ];
        $st = $pdo->prepare('INSERT INTO "settings" ("key", value, created_at, updated_at) VALUES (:k, :v, :c, :c2)');
        foreach ($defaults as $k => $v) {
            $now = date('Y-m-d H:i:s');
            $st->execute([':k' => $k, ':v' => $v, ':c' => $now, ':c2' => $now]);
        }
    }

    // Seed ministries
    $count = (int)$pdo->query('SELECT COUNT(*) AS c FROM "ministry_content"')->fetch()['c'];
    if ($count === 0) {
        $ministries = [
            ['ministerio-jovenes', 'Ministerio de Jóvenes', 'Formación, servicio y acompañamiento espiritual'],
            ['ministerio-ninos', 'Ministerio de Niños', 'Enseñanza bíblica y acompañamiento para la niñez'],
            ['ministerio-familia', 'Ministerio de Familia', 'Fortalecimiento de hogares y relaciones saludables'],
            ['ministerio-evangelismo', 'Ministerio de Evangelismo', 'Alcance y proclamación del evangelio'],
            ['direccion-misiones', 'Dirección de Misiones', 'Expansión, anexos y apoyo misionero'],
            ['direccion-comunicaciones', 'Dirección de Comunicaciones', 'Comunicación institucional y contenido digital'],
        ];
        $st = $pdo->prepare('INSERT INTO "ministry_content" (slug, title, description, leaders, activities, videos, photos, created_at, updated_at) VALUES (:s, :t, :d, :l, :a, :v, :p, :c, :c2)');
        foreach ($ministries as $m) {
            $now = date('Y-m-d H:i:s');
            $st->execute([':s' => $m[0], ':t' => $m[1], ':d' => $m[2], ':l' => '', ':a' => '', ':v' => '', ':p' => '', ':c' => $now, ':c2' => $now]);
        }
    }

    // Seed events
    $count = (int)$pdo->query('SELECT COUNT(*) AS c FROM "events"')->fetch()['c'];
    if ($count === 0) {
        $events = [
            ['Aniversario de la Iglesia', 'Celebra con nosotros el aniversario de nuestra congregación.', '2026-02-20'],
            ['Reunión de Oración', 'Momento especial de oración e intercesión.', '2026-06-15'],
            ['Campaña Evangelística', 'Alcanzando vidas con el evangelio de Cristo.', '2026-08-10'],
            ['Conferencia de Familias', 'Fortaleciendo hogares y relaciones.', '2026-09-05'],
            ['Culto Juvenil Especial', 'Noche de alabanza y palabra para jóvenes.', '2026-07-20'],
        ];
        $st = $pdo->prepare('INSERT INTO "events" (title, description, "date", image, active, created_at, updated_at) VALUES (:t, :d, :dt, :i, 1, :c, :c2)');
        foreach ($events as $ev) {
            $now = date('Y-m-d H:i:s');
            $st->execute([':t' => $ev[0], ':d' => $ev[1], ':dt' => $ev[2], ':i' => 'assets/images/actividades.jpg', ':c' => $now, ':c2' => $now]);
        }
    }
}

db_init();
