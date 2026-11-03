<?php
$titulo = 'Inicio';
$pagina_actual = 'home';
require_once 'includes/header.php';

// Contadores por estado
$estados = $pdo->query('SELECT id_estado, nombre, color FROM estados ORDER BY orden')->fetchAll();
$conteos = [];
foreach ($estados as $e) {
    $st = $pdo->prepare('SELECT COUNT(*) FROM tickets WHERE id_estado = ?');
    $st->execute([$e['id_estado']]);
    $conteos[$e['id_estado']] = (int)$st->fetchColumn();
}
$total = array_sum($conteos) ?: 1;

// Últimos tickets
$ultimos = $pdo->query("
    SELECT t.id_ticket, t.titulo, t.prioridad, t.fecha_creacion,
           e.nombre AS estado, e.color,
           u.nombre AS ubicacion,
           us.nombre AS solicitante
    FROM tickets t
    JOIN estados e ON e.id_estado = t.id_estado
    JOIN ubicaciones u ON u.id_ubicacion = t.id_ubicacion
    JOIN usuarios us ON us.id_usuario = t.id_usuario
    ORDER BY t.fecha_creacion DESC
    LIMIT 8
")->fetchAll();
?>

<div class="hd">
  <div>
    <p class="k">Panel principal</p>
    <h1>Inicio</h1>
    <small class="mu">Resumen del estado de los tickets del colegio</small>
  </div>
  <a href="nuevo_ticket.php" class="btn btn-pr">+ Nuevo ticket</a>
</div>

<!-- Barra de estados -->
<div class="card">
  <p class="k">Distribución por estado</p>
  <div class="sg">
    <?php foreach ($estados as $e): 
      $pct = round(($conteos[$e['id_estado']] / $total) * 100);
      if ($pct < 1 && $conteos[$e['id_estado']] > 0) $pct = 2;
      if ($pct === 0) continue;
      $color = $e['color'] ?: '#7d95b0';
    ?>
      <span style="width:<?= $pct ?>%; background:<?= htmlspecialchars($color) ?>">
        <?= $conteos[$e['id_estado']] ?>
      </span>
    <?php endforeach; ?>
  </div>
  <div class="lgr">
    <?php foreach ($estados as $e): ?>
      <span>
        <i style="background:<?= htmlspecialchars($e['color'] ?: '#7d95b0') ?>"></i>
        <?= htmlspecialchars($e['nombre']) ?> (<?= $conteos[$e['id_estado']] ?>)
      </span>
    <?php endforeach; ?>
  </div>
</div>

<!-- Tarjetas de últimos tickets -->
<div class="hd2" style="margin-top:28px">
  <div>
    <p class="k">Actividad reciente</p>
    <h2>Últimos tickets</h2>
  </div>
  <a href="tickets.php" class="btn btn-sec">Ver todos</a>
</div>

<div class="tiles">
  <?php if (empty($ultimos)): ?>
    <p class="mu">Todavía no hay tickets cargados.</p>
  <?php else: foreach ($ultimos as $t): 
    $clase = match(strtolower($t['estado'])) {
      'pendiente' => 'pen',
      'en revisión', 'en revision' => 'rev',
      'en reparación', 'en reparacion' => 'rep',
      'solucionado', 'resuelto' => 'sol',
      default => 'pen'
    };
  ?>
    <a class="tile" href="detalle_ticket.php?id=<?= $t['id_ticket'] ?>">
      <div class="tt">
        <h3><?= htmlspecialchars($t['titulo']) ?></h3>
        <span class="b <?= $clase ?>"><?= htmlspecialchars($t['estado']) ?></span>
      </div>
      <div class="meta">
        <?= htmlspecialchars($t['ubicacion']) ?> · 
        <span class="b <?= htmlspecialchars($t['prioridad']) ?>"><?= htmlspecialchars($t['prioridad']) ?></span>
      </div>
      <div class="meta">
        <?= date('d/m/Y H:i', strtotime($t['fecha_creacion'])) ?> · <?= htmlspecialchars($t['solicitante']) ?>
      </div>
    </a>
  <?php endforeach; endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
