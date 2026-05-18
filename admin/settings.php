<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin — Configuración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<div class="admin-header">
    <h1>⚙️ Configuración</h1>
    <a href="logout.php">Cerrar Sesión</a>
</div>

<nav class="admin-nav">
    <a href="index.php">Dashboard</a>
    <a href="events.php">Eventos</a>
    <a href="ministries.php">Ministerios</a>
    <a href="messages.php">Mensajes</a>
    <a href="settings.php" class="active">Configuración</a>
    <a href="../index.php" target="_blank">Ver Sitio →</a>
</nav>

<div class="admin-container">

    <?php if ($flash): ?>
        <div class="flash flash-<?= $flashType ?>"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Información de la Iglesia</h2>
        <form method="POST">
            <input type="hidden" name="action" value="update_settings">

            <label for="church_name">Nombre de la Iglesia</label>
            <input type="text" id="church_name" name="church_name" value="<?= htmlspecialchars($settings['church_name'] ?? '') ?>">

            <label for="church_email">Correo Electrónico</label>
            <input type="email" id="church_email" name="church_email" value="<?= htmlspecialchars($settings['church_email'] ?? '') ?>">

            <label for="church_phone">Teléfono</label>
            <input type="text" id="church_phone" name="church_phone" value="<?= htmlspecialchars($settings['church_phone'] ?? '') ?>">

            <label for="church_address">Dirección</label>
            <input type="text" id="church_address" name="church_address" value="<?= htmlspecialchars($settings['church_address'] ?? '') ?>">

            <label for="church_hours">Horario</label>
            <input type="text" id="church_hours" name="church_hours" value="<?= htmlspecialchars($settings['church_hours'] ?? '') ?>">

            <label for="facebook_url">URL de Facebook</label>
            <input type="text" id="facebook_url" name="facebook_url" value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>">

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Configuración</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Cambiar Contraseña</h2>
        <form method="POST">
            <input type="hidden" name="action" value="change_password">

            <label for="current_password">Contraseña Actual</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">Nueva Contraseña</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirmar Nueva Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
            </div>
        </form>
    </div>

</div>

</body>
</html>
