<?php
/**
 * Este archivo no se usa de forma independiente.
 * La lógica de UPDATE está dentro de editar_ticket.php
 * (mismo patrón que nuevo_ticket.php).
 * Se deja este archivo por si en el futuro se quiere separar la lógica.
 */
require_once __DIR__ . '/../conexion.php';
requerirLogin();
header('Location: ../tickets.php');
exit;
