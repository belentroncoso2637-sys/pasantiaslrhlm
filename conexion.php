<?php
/**
 * Conexión a la base de datos - Sistema de Soporte Técnico IPET 363
 */
$host = 'localhost';
$dbname = 'soporte_tecnico_colegio';
$username = 'root';
$password = '';          // Cambiar si tenés contraseña en phpMyAdmin
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Error de conexión a la base de datos: ' . $e->getMessage());
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si hay un usuario logueado. Si no, redirige al login.
 */
function requerirLogin() {
    if (empty($_SESSION['usuario_id'])) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Devuelve los datos del usuario actual o null.
 */
function usuarioActual() {
    return [
        'id'     => $_SESSION['usuario_id'] ?? null,
        'nombre' => $_SESSION['usuario_nombre'] ?? '',
        'usuario'=> $_SESSION['usuario_login'] ?? '',
        'rol'    => $_SESSION['usuario_rol'] ?? '',
    ];
}
