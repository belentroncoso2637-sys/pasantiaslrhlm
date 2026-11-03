<?php
if (!isset($pdo)) require_once __DIR__ . '/../conexion.php';
requerirLogin();
$u = usuarioActual();
$pagina_actual = $pagina_actual ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titulo ?? 'Soporte técnico') ?> · IPET N.º 363</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
<aside>
  <div class="logo">
    <i>363</i>
    <span><b>IPET N.º 363</b><small>Soporte técnico</small></span>
  </div>
  <nav>
    <a href="dashboard.php" class="<?= $pagina_actual === 'home' ? 'on' : '' ?>">
      <svg class="ico" viewBox="0 0 24 24"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>Inicio
    </a>
    <a href="tickets.php" class="<?= $pagina_actual === 'tickets' ? 'on' : '' ?>">
      <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>Solicitudes
    </a>
    <a href="nuevo_ticket.php" class="<?= $pagina_actual === 'nuevo' ? 'on' : '' ?>">
      <svg class="ico" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>Nueva solicitud
    </a>
    <a href="estadisticas.php" class="<?= $pagina_actual === 'stats' ? 'on' : '' ?>">
      <svg class="ico" viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>Estadísticas
    </a>
  </nav>
  <div class="help">
    <span class="q">?</span>
    <div>
      <b>¿Necesitás ayuda?</b>
      <small>Consultá con el área de informática.</small>
    </div>
  </div>
</aside>

<div class="mw">
  <header class="top">
    <div class="sb">
      <svg class="ico" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-5-5"/></svg>
      <form action="tickets.php" method="get" style="margin:0">
        <input type="search" name="q" placeholder="Buscar por problema, persona o lugar…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      </form>
    </div>
    <div class="us">
      <span class="av"><?= strtoupper(mb_substr($u['nombre'], 0, 2)) ?></span>
      <span>
        <b><?= htmlspecialchars($u['nombre']) ?></b>
        <small><?= htmlspecialchars($u['rol']) ?></small>
      </span>
      <a class="out" href="logout.php">Cerrar sesión</a>
    </div>
  </header>
  <main>
