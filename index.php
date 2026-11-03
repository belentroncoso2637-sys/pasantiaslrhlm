<?php
require_once 'conexion.php';

// Si ya está logueado, ir al dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave   = $_POST['password'] ?? '';

    if ($usuario === '' || $clave === '') {
        $error = 'Completá usuario y contraseña.';
    } else {
        $stmt = $pdo->prepare('SELECT id_usuario, nombre, usuario, password_hash, rol FROM usuarios WHERE usuario = ?');
        $stmt->execute([$usuario]);
        $row = $stmt->fetch();

        // Aceptamos texto plano o hash (para la migración)
        if ($row && ($clave === $row['password_hash'] || password_verify($clave, $row['password_hash']))) {
            $_SESSION['usuario_id']     = $row['id_usuario'];
            $_SESSION['usuario_nombre'] = $row['nombre'];
            $_SESSION['usuario_login']  = $row['usuario'];
            $_SESSION['usuario_rol']    = $row['rol'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Iniciar sesión · Soporte técnico IPET 363</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body class="login-page">
  <div class="login-box">
    <div class="logo-lg">
      <img src="assets/img/logo.png" alt="Logo">
    </div>
    <h1>Soporte técnico</h1>
    <p>IPET N.º 363 · Ingresá con tu cuenta</p>

    <?php if ($error): ?>
      <div class="err"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="f">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required autofocus
               value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>">
      </div>
      <div class="f">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-pr" style="width:100%; justify-content:center; margin-top:8px;">
        Entrar
      </button>
    </form>
    <p style="margin-top:18px; font-size:12px; color:var(--mu); text-align:center;">
      Usuario de prueba: <b>tapia</b> / 123456
    </p>
  </div>
</body>
</html>
