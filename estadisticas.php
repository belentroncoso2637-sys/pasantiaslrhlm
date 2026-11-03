<?php
$titulo = 'Estadísticas';
$pagina_actual = 'stats';
require_once 'includes/header.php';

// Totales
$total = (int)$pdo->query('SELECT COUNT(*) FROM tickets')->fetchColumn();
$abiertos = (int)$pdo->query("
    SELECT COUNT(*) FROM tickets t
    JOIN estados e ON e.id_estado = t.id_estado
    WHERE e.nombre NOT IN ('Solucionado', 'Resuelto')
")->fetchColumn();
$resueltos = $total - $abiertos;

// Por estado
$por_estado = $pdo->query("
    SELECT e.nombre, e.color, COUNT(t.id_ticket) AS cantidad
    FROM estados e
    LEFT JOIN tickets t ON t.id_estado = e.id_estado
    GROUP BY e.id_estado
    ORDER BY e.orden
")->fetchAll();

// Por categoría
$por_categoria = $pdo->query("
    SELECT c.nombre, COUNT(t.id_ticket) AS cantidad
    FROM categorias c
    LEFT JOIN tickets t ON t.id_categoria = c.id_categoria
    GROUP BY c.id_categoria
    ORDER BY cantidad DESC
")->fetchAll();

// Por ubicación
$por_ubicacion = $pdo->query("
    SELECT u.nombre, COUNT(t.id_ticket) AS cantidad
    FROM ubicaciones u
    LEFT JOIN tickets t ON t.id_ubicacion = u.id_ubicacion
    GROUP BY u.id_ubicacion
    ORDER BY cantidad DESC
")->fetchAll();

// Por prioridad
$por_prioridad = $pdo->query("
    SELECT prioridad, COUNT(*) AS cantidad
    FROM tickets
    GROUP BY prioridad
    ORDER BY FIELD(prioridad, 'Alta', 'Media', 'Baja')
")->fetchAll();
?>

<div class="hd">
  <div>
    <p class="k">Reportes</p>
    <h1>Estadísticas</h1>
    <small class="mu">Indicadores de frecuencia de problemas</small>
  </div>
</div>

<div class="stat-cards">
  <div class="stat-card">
    <div class="num"><?= $total ?></div>
    <div class="lbl">Total de tickets</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:var(--alta)"><?= $abiertos ?></div>
    <div class="lbl">Abiertos / en curso</div>
  </div>
  <div class="stat-card">
    <div class="num" style="color:#27ae60"><?= $resueltos ?></div>
    <div class="lbl">Resueltos</div>
  </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

  <div class="card">
    <p class="k">Por estado</p>
    <?php foreach ($por_estado as $r): 
      $pct = $total > 0 ? round(($r['cantidad'] / $total) * 100) : 0;
    ?>
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
        <span>
          <i style="display:inline-block;width:10px;height:10px;border-radius:3px;background:<?= htmlspecialchars($r['color'] ?: '#7d95b0') ?>;margin-right:6px;"></i>
          <?= htmlspecialchars($r['nombre']) ?>
        </span>
        <strong><?= $r['cantidad'] ?> <small class="mu">(<?= $pct ?>%)</small></strong>
      </div>
      <div style="height:6px; background:var(--bd); border-radius:4px; margin-bottom:12px;">
        <div style="height:100%; width:<?= $pct ?>%; background:<?= htmlspecialchars($r['color'] ?: '#7d95b0') ?>; border-radius:4px;"></div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <p class="k">Por categoría de problema</p>
    <?php foreach ($por_categoria as $r): 
      $pct = $total > 0 ? round(($r['cantidad'] / $total) * 100) : 0;
    ?>
      <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
        <span><?= htmlspecialchars($r['nombre']) ?></span>
        <strong><?= $r['cantidad'] ?> <small class="mu">(<?= $pct ?>%)</small></strong>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <p class="k">Por ubicación</p>
    <?php foreach ($por_ubicacion as $r): 
      if ($r['cantidad'] == 0) continue;
      $pct = $total > 0 ? round(($r['cantidad'] / $total) * 100) : 0;
    ?>
      <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
        <span><?= htmlspecialchars($r['nombre']) ?></span>
        <strong><?= $r['cantidad'] ?> <small class="mu">(<?= $pct ?>%)</small></strong>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <p class="k">Por prioridad</p>
    <?php foreach ($por_prioridad as $r): 
      $pct = $total > 0 ? round(($r['cantidad'] / $total) * 100) : 0;
    ?>
      <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
        <span class="b <?= htmlspecialchars($r['prioridad']) ?>"><?= htmlspecialchars($r['prioridad']) ?></span>
        <strong><?= $r['cantidad'] ?> <small class="mu">(<?= $pct ?>%)</small></strong>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
