<?php
/**
 * One-time migration: data/*.json -> Postgres (Neon) or SQLite fallback.
 * Usage:
 *   DATABASE_URL="postgres://..." php scripts/migrate.php
 *   php scripts/migrate.php            (SQLite fallback in data/app.db)
 */
require_once __DIR__ . '/../src/database.php';

$root = dirname(__DIR__);
$legacyDir = $root . '/data';

$files = [
    'admin_users' => 'admin_users.json',
    'settings' => 'settings.json',
    'ministry_content' => 'ministry_content.json',
    'events' => 'events.json',
    'contact_messages' => 'contact_messages.json',
    'decisiones' => 'decisiones.json',
];

foreach ($files as $table => $file) {
    $path = $legacyDir . '/' . $file;
    if (!file_exists($path)) {
        echo "[skip] {$table}: no {$file}\n";
        continue;
    }
    $rows = json_decode(file_get_contents($path), true) ?: [];
    if (!$rows) {
        echo "[skip] {$table}: empty\n";
        continue;
    }
    $existing = db_all($table);
    // Avoid duplicates: skip if target already has data (except settings/admin seed merge).
    if (count($existing) > 0 && !in_array($table, ['settings', 'admin_users', 'ministry_content'], true)) {
        echo "[skip] {$table}: already has " . count($existing) . " rows\n";
        continue;
    }
    $imported = 0;
    foreach ($rows as $row) {
        try {
            if ($table === 'settings' && isset($row['key']) && db_find_by($table, 'key', $row['key'])) continue;
            if ($table === 'admin_users' && isset($row['username']) && db_find_by($table, 'username', $row['username'])) continue;
            if ($table === 'ministry_content' && isset($row['slug']) && db_find_by($table, 'slug', $row['slug'])) {
                // Update existing seed with full legacy content (photos, social, streams...).
                $ex = db_find_by($table, 'slug', $row['slug']);
                $u = $row; unset($u['id']);
                db_update($table, $ex['id'], $u);
                $imported++;
                continue;
            }
            if ($table === 'events' && isset($row['title'])) {
                $found = false;
                foreach ($existing as $e) {
                    if (($e['title'] ?? '') === $row['title']) { $found = true; break; }
                }
                if ($found) continue;
            }
            unset($row['id']); // let DB assign
            db_insert($table, $row);
            $imported++;
        } catch (Throwable $e) {
            echo "[warn] {$table}: " . $e->getMessage() . "\n";
        }
    }
    echo "[ok] {$table}: imported {$imported} rows\n";
}

echo "Done. Driver=" . db_driver() . "\n";
