
<?php
require_once 'conexion.php';

// Si ya inició sesión, ir al dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$mensaje = '';

if (isset($_GET['registro']) && $_GET['registro'] === 'ok') {
    $mensaje = '¡Cuenta creada correctamente! Ya podés iniciar sesión.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo = trim($_POST['usuario'] ?? '');
    $clave = $_POST['password'] ?? '';

    if ($correo === '' || $clave === '') {
        $error = 'Completá el correo y la contraseña.';
    } else {

        // Buscar usuario por correo
        $stmt = $pdo->prepare("
            SELECT id_usuario, nombre, correo, contrasena, rol
            FROM Usuarios
            WHERE correo = ?
        ");

        $stmt->execute([$correo]);
        $row = $stmt->fetch();

        // Verificar contraseña (hash o texto plano)
        if ($row && (
            password_verify($clave, $row['contrasena'])
            || $clave === $row['contrasena']
        )) {

            // Crear sesión
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $row['id_usuario'];
            $_SESSION['usuario_nombre'] = $row['nombre'];
            $_SESSION['usuario_login'] = $row['correo'];
            $_SESSION['usuario_rol'] = $row['rol'];

            // Ir al panel principal
            header('Location: dashboard.php');
            exit;

        } else {
            $error = 'Correo o contraseña incorrectos.';
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

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

<link rel="stylesheet" href="assets/css/estilos.css">

</head>

<body class="login-page">

<div class="login-box">

    <div class="logo-lg">
        <img src="assets/img/logo.png" alt="Logo">
    </div>

    <h1>Soporte técnico</h1>

    <p>IPET N.º 363 · Ingresá con tu cuenta</p>

    <?php if ($mensaje): ?>
        <div class="ok">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="err">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">

        <div class="f">
            <label for="usuario">Correo electrónico</label>

            <input
                type="email"
                id="usuario"
                name="usuario"
                required
                autofocus
                value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
            >
        </div>

        <div class="f">
            <label for="password">Contraseña</label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <button
            type="submit"
            class="btn btn-pr"
            style="width:100%; justify-content:center; margin-top:8px;"
        >
            Entrar
        </button>

    </form>

    <p style="margin-top:18px; font-size:13px; text-align:center;">
        ¿Todavía no tenés cuenta?
        <a href="registro.php">Crear una cuenta</a>
    </p>

</div>

</body>
</html>
