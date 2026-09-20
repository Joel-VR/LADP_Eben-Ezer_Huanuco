<?php
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/database.php';

$flash = '';
$flashType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_settings') {
        $settings = [
            'church_name' => trim($_POST['church_name'] ?? ''),
            'church_email' => trim($_POST['church_email'] ?? ''),
            'church_phone' => trim($_POST['church_phone'] ?? ''),
            'church_address' => trim($_POST['church_address'] ?? ''),
            'church_hours' => trim($_POST['church_hours'] ?? ''),
            'facebook_url' => trim($_POST['facebook_url'] ?? ''),
        ];
        $allSettings = db_all('settings');
        foreach ($allSettings as &$s) {
            if (isset($settings[$s['key']])) {
                $s['value'] = $settings[$s['key']];
                $s['updated_at'] = date('Y-m-d H:i:s');
            }
        }
        db_write('settings', $allSettings);
        $flash = 'Configuración actualizada exitosamente.';
        $flashType = 'success';
    }

    if ($action === 'change_password') {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $user = db_find_by('admin_users', 'username', $_SESSION['admin_username']);

        if (!$user || !password_verify($current, $user['password_hash'])) {
            $flash = 'La contraseña actual es incorrecta.';
            $flashType = 'error';
        } elseif (strlen($new) < 6) {
            $flash = 'La nueva contraseña debe tener al menos 6 caracteres.';
            $flashType = 'error';
        } elseif ($new !== $confirm) {
            $flash = 'Las contraseñas nuevas no coinciden.';
            $flashType = 'error';
        } else {
            $users = db_all('admin_users');
            foreach ($users as &$u) {
                if ($u['username'] === $_SESSION['admin_username']) {
                    $u['password_hash'] = password_hash($new, PASSWORD_DEFAULT);
                    $u['updated_at'] = date('Y-m-d H:i:s');
                    break;
                }
            }
            db_write('admin_users', $users);
            $flash = 'Contraseña cambiada exitosamente.';
            $flashType = 'success';
        }
    }
}

$settings = [];
foreach (db_all('settings') as $s) $settings[$s['key']] = $s['value'];
require __DIR__ . '/../views/admin/settings.php';
