<?php
require_once __DIR__ . '/../conexion.php';
requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../tickets.php');
    exit;
}

$id_ticket       = (int)($_POST['id_ticket'] ?? 0);
$estado_actual   = (int)($_POST['estado_actual'] ?? 0);
$id_estado_nuevo = (int)($_POST['id_estado_nuevo'] ?? 0);
$observacion     = trim($_POST['observacion'] ?? '');

if ($id_ticket <= 0 || $id_estado_nuevo <= 0) {
    header('Location: ../tickets.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Actualizar estado del ticket
    $stmt = $pdo->prepare('UPDATE tickets SET id_estado = ? WHERE id_ticket = ?');
    $stmt->execute([$id_estado_nuevo, $id_ticket]);

    // Registrar intervención
    $stmt = $pdo->prepare("
        INSERT INTO intervenciones 
        (observacion, fecha, id_ticket, id_usuario, id_estado_anterior, id_estado_nuevo)
        VALUES (?, NOW(), ?, ?, ?, ?)
    ");
    $stmt->execute([
        $observacion ?: null,
        $id_ticket,
        $_SESSION['usuario_id'],
        $estado_actual ?: null,
        $id_estado_nuevo
    ]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    die('Error al guardar: ' . $e->getMessage());
}

header('Location: ../detalle_ticket.php?id=' . $id_ticket . '&actualizado=1');
exit;
