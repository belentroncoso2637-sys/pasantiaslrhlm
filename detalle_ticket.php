<?php
$titulo = 'Detalle del ticket';
$pagina_actual = 'tickets';
require_once 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: tickets.php');
    exit;
}

// Ticket
$stmt = $pdo->prepare("
    SELECT t.*, 
           e.nombre AS estado, e.color AS estado_color, e.id_estado,
           u.nombre AS ubicacion,
           c.nombre AS categoria,
           eq.nombre AS equipo, eq.codigo AS equipo_codigo, eq.tipo AS equipo_tipo,
           us.nombre AS solicitante, us.rol AS solicitante_rol
    FROM tickets t
    JOIN estados e ON e.id_estado = t.id_estado
    JOIN ubicaciones u ON u.id_ubicacion = t.id_ubicacion
    JOIN categorias c ON c.id_categoria = t.id_categoria
    LEFT JOIN equipos eq ON eq.id_equipo = t.id_equipo
    JOIN usuarios us ON us.id_usuario = t.id_usuario
    WHERE t.id_ticket = ?
");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo '<p class="err">Ticket no encontrado.</p>';
    require_once 'includes/footer.php';
    exit;
}

// Intervenciones (historial)
$stmt = $pdo->prepare("
    SELECT i.*, 
           us.nombre AS tecnico,
           ea.nombre AS estado_ant,
           en.nombre AS estado_nuevo
    FROM intervenciones i
    JOIN usuarios us ON us.id_usuario = i.id_usuario
    LEFT JOIN estados ea ON ea.id_estado = i.id_estado_anterior
    JOIN estados en ON en.id_estado = i.id_estado_nuevo
    WHERE i.id_ticket = ?
    ORDER BY i.fecha ASC
");
$stmt->execute([$id]);
$intervenciones = $stmt->fetchAll();

$estados = $pdo->query('SELECT id_estado, nombre FROM estados ORDER BY orden')->fetchAll();

$mensaje = '';
if (isset($_GET['creado']))     $mensaje = 'Ticket creado correctamente.';
if (isset($_GET['actualizado'])) $mensaje = 'Estado actualizado y observación guardada.';
if (isset($_GET['editado']))    $mensaje = 'Datos del ticket actualizados correctamente.';
?>

<a class="back" href="tickets.php">← Volver a tickets</a>

<?php if ($mensaje): ?><div class="ok"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<div class="hd">
  <div>
    <p class="k">Ticket #<?= $ticket['id_ticket'] ?></p>
    <h1><?= htmlspecialchars($ticket['titulo']) ?></h1>
    <small class="mu">
      Creado el <?= date('d/m/Y H:i', strtotime($ticket['fecha_creacion'])) ?>
      por <?= htmlspecialchars($ticket['solicitante']) ?>
    </small>
  </div>
  <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    <?php
      $clase = match(strtolower($ticket['estado'])) {
        'pendiente' => 'pen',
        'en revisión', 'en revision' => 'rev',
        'en reparación', 'en reparacion' => 'rep',
        'solucionado', 'resuelto' => 'sol',
        default => 'pen'
      };
    ?>
    <span class="b <?= $clase ?>" style="font-size:13px; padding:6px 12px;">
      <?= htmlspecialchars($ticket['estado']) ?>
    </span>
    <a href="editar_ticket.php?id=<?= $ticket['id_ticket'] ?>" class="btn btn-sec">
      Editar datos
    </a>
  </div>
</div>

<div style="display:grid; grid-template-columns: 1.6fr 1fr; gap:20px; align-items:start;">

  <!-- Columna izquierda: datos + historial -->
  <div>
    <div class="card">
      <p class="k">Información del problema</p>
      <p style="margin:0 0 12px; white-space:pre-wrap;"><?= htmlspecialchars($ticket['descripcion'] ?: 'Sin descripción.') ?></p>
      <div class="g3" style="margin:0;">
        <div>
          <label class="k">Categoría</label>
          <div><?= htmlspecialchars($ticket['categoria']) ?></div>
        </div>
        <div>
          <label class="k">Prioridad</label>
          <div><span class="b <?= htmlspecialchars($ticket['prioridad']) ?>"><?= htmlspecialchars($ticket['prioridad']) ?></span></div>
        </div>
        <div>
          <label class="k">Ubicación</label>
          <div><?= htmlspecialchars($ticket['ubicacion']) ?></div>
        </div>
      </div>
      <?php if ($ticket['equipo']): ?>
        <div style="margin-top:14px;">
          <label class="k">Equipo</label>
          <div><?= htmlspecialchars($ticket['equipo_codigo'] . ' · ' . $ticket['equipo']) ?>
            <small class="mu"><?= htmlspecialchars($ticket['equipo_tipo']) ?></small>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <div class="card" style="margin-top:18px;">
      <p class="k">Historial de intervenciones</p>
      <?php if (empty($intervenciones)): ?>
        <p class="mu">Todavía no hay intervenciones registradas.</p>
      <?php else: ?>
        <div class="timeline">
          <?php foreach ($intervenciones as $iv): ?>
            <div class="ev">
              <strong>
                <?php if ($iv['estado_ant']): ?>
                  <?= htmlspecialchars($iv['estado_ant']) ?> → <?= htmlspecialchars($iv['estado_nuevo']) ?>
                <?php else: ?>
                  Estado: <?= htmlspecialchars($iv['estado_nuevo']) ?>
                <?php endif; ?>
              </strong>
              <?php if ($iv['observacion']): ?>
                <div style="margin-top:4px;"><?= htmlspecialchars($iv['observacion']) ?></div>
              <?php endif; ?>
              <div class="ev-meta">
                <?= date('d/m/Y H:i', strtotime($iv['fecha'])) ?> · <?= htmlspecialchars($iv['tecnico']) ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Columna derecha: cambiar estado -->
  <div class="card">
    <p class="k">Actualizar seguimiento</p>
    <p class="mu" style="margin-bottom:14px;">Registrá una intervención y/o cambiá el estado del ticket.</p>

    <form method="post" action="backend/cambiar_estado.php">
      <input type="hidden" name="id_ticket" value="<?= $ticket['id_ticket'] ?>">
      <input type="hidden" name="estado_actual" value="<?= $ticket['id_estado'] ?>">

      <div class="f">
        <label>Nuevo estado</label>
        <select name="id_estado_nuevo" required>
          <?php foreach ($estados as $e): ?>
            <option value="<?= $e['id_estado'] ?>" <?= $e['id_estado'] == $ticket['id_estado'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($e['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="f">
        <label>Observación / acción realizada</label>
        <textarea name="observacion" placeholder="Ej: Se cambió el cable de red, se limpió el polvo interno…"></textarea>
      </div>

      <button type="submit" class="btn btn-pr" style="width:100%; justify-content:center;">
        Guardar intervención
      </button>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
