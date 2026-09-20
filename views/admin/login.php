<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin — Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/base.css">
    <link rel="stylesheet" href="../styles/components.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: var(--bg); }
        .login-box { max-width: 400px; width: 100%; }
        .login-box h2 { color: var(--primary); text-align: center; margin-bottom: 24px; }
        .login-box form { display: flex; flex-direction: column; gap: 16px; }
        .login-box input { padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 1rem; }
        .login-box button { padding: 12px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; }
        .login-box button:hover { background: var(--primary-dark); }
        .error { color: #c0392b; text-align: center; font-size: 0.9rem; }
        .back-link { text-align: center; margin-top: 16px; }
        .back-link a { color: var(--primary); text-decoration: none; }
    </style>
</head>
<body>
    <div class="card login-box">
        <h2>Admin — Iglesia Eben-Ezer</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required autofocus>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <div class="back-link">
            <a href="../public/">← Volver al sitio</a>
        </div>
    </div>
</body>
</html>
