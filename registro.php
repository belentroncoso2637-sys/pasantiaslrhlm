
<?php
require_once 'conexion.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($nombre === '' || $correo === '' || $contrasena === '') {
        $error = 'Completá todos los campos.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ingresá un correo válido.';
    } elseif (strlen($contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($contrasena !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id_usuario FROM Usuarios WHERE correo = ?'
        );
        $stmt->execute([$correo]);

        if ($stmt->fetch()) {
            $error = 'Ese correo ya está registrado.';
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO Usuarios (nombre, rol, correo, contrasena)
                 VALUES (?, ?, ?, ?)'
            );

            $stmt->execute([
                $nombre,
                'Profesor',
                $correo,
                $hash
            ]);

            header('Location: index.php?registro=ok');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Crear cuenta · Soporte técnico</title>
<link rel="stylesheet" href="assets/css/estilos.css">
</head>

<body class="login-page">
<div class="login-box">

    <div class="logo-lg">
        <img src="assets/img/logo.png" alt="Logo">
    </div>

    <h1>Crear cuenta</h1>
    <p>IPET N.º 363 · Registrate para ingresar</p>

    <?php if ($error): ?>
        <div class="err"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">

        <div class="f">
            <label>Nombre completo</label>
            <input type="text" name="nombre" required
                value="<?= htmlspecialchars($nombre ?? '') ?>">
        </div>

        <div class="f">
            <label>Correo electrónico</label>
            <input type="email" name="correo" required
                value="<?= htmlspecialchars($correo ?? '') ?>">
        </div>

        <div class="f">
            <label>Contraseña</label>
            <input type="password" name="contrasena" required>
        </div>

        <div class="f">
            <label>Confirmar contraseña</label>
            <input type="password" name="confirmar" required>
        </div>

        <button type="submit" class="btn btn-pr"
            style="width:100%;justify-content:center">
            Crear cuenta
        </button>

    </form>

    <p style="margin-top:18px;text-align:center">
        ¿Ya tenés cuenta?
        <a href="index.php">Iniciar sesión</a>
    </p>

</div>
</body>
</html>
