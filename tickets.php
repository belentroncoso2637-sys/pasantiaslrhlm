<?php
$titulo = 'Tickets';
$pagina_actual = 'tickets';
require_once 'includes/header.php';

// Filtros
$q         = trim($_GET['q'] ?? '');
$estado_id = $_GET['estado'] ?? '';
$prio      = $_GET['prioridad'] ?? '';
$ubic_id   = $_GET['ubicacion'] ?? '';
$cat_id    = $_GET['categoria'] ?? '';

$sql = "
    SELECT t.id_ticket, t.titulo, t.prioridad, t.fecha_creacion, t.descripcion,
           e.nombre AS estado, e.color,
           u.nombre AS ubicacion,
           c.nombre AS categoria,
           eq.nombre AS equipo, eq.codigo AS equipo_codigo,
           us.nombre AS solicitante
    FROM tickets t
    JOIN estados e ON e.id_estado = t.id_estado
    JOIN ubicaciones u ON u.id_ubicacion = t.id_ubicacion
    JOIN categorias c ON c.id_categoria = t.id_categoria
    LEFT JOIN equipos eq ON eq.id_equipo = t.id_equipo
    JOIN usuarios us ON us.id_usuario = t.id_usuario
    WHERE 1=1
";
$params = [];

if ($q !== '') {
    $sql .= " AND (t.titulo LIKE ? OR t.descripcion LIKE ? OR eq.nombre LIKE ? OR u.nombre LIKE ?)";
    $like = "%$q%";
    $params = array_merge($params, [$like, $like, $like, $like]);
}
if ($estado_id !== '') {
    $sql .= " AND t.id_estado = ?";
    $params[] = $estado_id;
}
if ($prio !== '') {
    $sql .= " AND t.prioridad = ?";
    $params[] = $prio;
}
if ($ubic_id !== '') {
    $sql .= " AND t.id_ubicacion = ?";
    $params[] = $ubic_id;
}
if ($cat_id !== '') {
    $sql .= " AND t.id_categoria = ?";
    $params[] = $cat_id;
}

$sql .= " ORDER BY t.fecha_creacion DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();

// Datos para filtros
$estados     = $pdo->query('SELECT id_estado, nombre FROM estados ORDER BY orden')->fetchAll();
$ubicaciones = $pdo->query('SELECT id_ubicacion, nombre FROM ubicaciones ORDER BY nombre')->fetchAll();
$categorias  = $pdo->query('SELECT id_categoria, nombre FROM categorias ORDER BY nombre')->fetchAll();
?>

<div class="hd">
  <div>
    <p class="k">Gestión</p>
    <h1>Tickets</h1>
    <small class="mu"><?= count($tickets) ?> resultado(s)</small>
  </div>
  <a href="nuevo_ticket.php" class="btn btn-pr">+ Nuevo ticket</a>
</div>

<form class="filtros" method="get" action="tickets.php">
  <input type="search" name="q" placeholder="Buscar…" value="<?= htmlspecialchars($q) ?>">
  <select name="estado">
    <option value="">Todos los estados</option>
    <?php foreach ($estados as $e): ?>
      <option value="<?= $e['id_estado'] ?>" <?= $estado_id == $e['id_estado'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($e['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <select name="prioridad">
    <option value="">Todas las prioridades</option>
    <option value="Alta"  <?= $prio === 'Alta'  ? 'selected' : '' ?>>Alta</option>
    <option value="Media" <?= $prio === 'Media' ? 'selected' : '' ?>>Media</option>
    <option value="Baja"  <?= $prio === 'Baja'  ? 'selected' : '' ?>>Baja</option>
  </select>
  <select name="ubicacion">
    <option value="">Todas las ubicaciones</option>
    <?php foreach ($ubicaciones as $u): ?>
      <option value="<?= $u['id_ubicacion'] ?>" <?= $ubic_id == $u['id_ubicacion'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($u['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <select name="categoria">
    <option value="">Todas las categorías</option>
    <?php foreach ($categorias as $c): ?>
      <option value="<?= $c['id_categoria'] ?>" <?= $cat_id == $c['id_categoria'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['nombre']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn btn-pr">Filtrar</button>
  <a href="tickets.php" class="btn btn-sec">Limpiar</a>
</form>

<div class="table-wrap">
  <table class="tickets">
    <thead>
      <tr>
        <th>#</th>
        <th>Título</th>
        <th>Categoría</th>
        <th>Ubicación</th>
        <th>Prioridad</th>
        <th>Estado</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($tickets)): ?>
        <tr><td colspan="7" style="text-align:center; color:var(--mu);">No se encontraron tickets.</td></tr>
      <?php else: foreach ($tickets as $t): 
        $clase = match(strtolower($t['estado'])) {
          'pendiente' => 'pen',
          'en revisión', 'en revision' => 'rev',
          'en reparación', 'en reparacion' => 'rep',
          'solucionado', 'resuelto' => 'sol',
          default => 'pen'
        };
      ?>
        <tr>
          <td><?= $t['id_ticket'] ?></td>
          <td>
            <a href="detalle_ticket.php?id=<?= $t['id_ticket'] ?>">
              <?= htmlspecialchars($t['titulo']) ?>
            </a>
            <?php if ($t['equipo']): ?>
              <small class="mu"><?= htmlspecialchars($t['equipo_codigo'] . ' · ' . $t['equipo']) ?></small>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($t['categoria']) ?></td>
          <td><?= htmlspecialchars($t['ubicacion']) ?></td>
          <td><span class="b <?= htmlspecialchars($t['prioridad']) ?>"><?= htmlspecialchars($t['prioridad']) ?></span></td>
          <td><span class="b <?= $clase ?>"><?= htmlspecialchars($t['estado']) ?></span></td>
          <td><?= date('d/m/Y', strtotime($t['fecha_creacion'])) ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'includes/footer.php'; ?>
