<?php
$titulo = 'Editar ticket';
$pagina_actual = 'tickets';
require_once 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: tickets.php');
    exit;
}

// Cargar ticket actual
$stmt = $pdo->prepare('SELECT * FROM tickets WHERE id_ticket = ?');
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo '<p class="err">Ticket no encontrado.</p>';
    require_once 'includes/footer.php';
    exit;
}

$categorias  = $pdo->query('SELECT id_categoria, nombre FROM categorias ORDER BY nombre')->fetchAll();
$ubicaciones = $pdo->query('SELECT id_ubicacion, nombre FROM ubicaciones ORDER BY nombre')->fetchAll();
$equipos     = $pdo->query('SELECT id_equipo, codigo, nombre, id_ubicacion FROM equipos ORDER BY nombre')->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo_t   = trim($_POST['titulo'] ?? '');
    $desc       = trim($_POST['descripcion'] ?? '');
    $prioridad  = $_POST['prioridad'] ?? 'Media';
    $id_cat     = (int)($_POST['id_categoria'] ?? 0);
    $id_ubic    = (int)($_POST['id_ubicacion'] ?? 0);
    $id_equipo  = (int)($_POST['id_equipo'] ?? 0);
    $fecha_prob = trim($_POST['fecha_problema'] ?? '');
    $prioridades_validas = ['Baja', 'Media', 'Alta'];
    $fecha_valida = preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_prob)
        && checkdate((int)substr($fecha_prob, 5, 2), (int)substr($fecha_prob, 8, 2), (int)substr($fecha_prob, 0, 4));

    $stmt_equipo = $pdo->prepare('SELECT COUNT(*) FROM equipos WHERE id_equipo = ? AND id_ubicacion = ?');
    $stmt_equipo->execute([$id_equipo, $id_ubic]);
    $equipo_valido = $stmt_equipo->fetchColumn() > 0;

    if ($titulo_t === '' || $desc === '' || $id_cat === 0 || $id_ubic === 0 || !$id_equipo || !$fecha_valida) {
        $error = 'Completá todos los campos obligatorios: título, descripción, categoría, fecha, ubicación y equipo.';
    } elseif (!$equipo_valido) {
        $error = 'Seleccioná un equipo válido que pertenezca a la ubicación indicada.';
    } elseif (!in_array($prioridad, $prioridades_validas, true)) {
        $error = 'Seleccioná una prioridad válida.';
    } else {
        $stmt = $pdo->prepare("
            UPDATE tickets SET
                titulo = ?,
                descripcion = ?,
                prioridad = ?,
                fecha_problema = ?,
                id_categoria = ?,
                id_ubicacion = ?,
                id_equipo = ?
            WHERE id_ticket = ?
        ");
        $stmt->execute([
            $titulo_t,
            $desc,
            $prioridad,
            $fecha_prob,
            $id_cat,
            $id_ubic,
            $id_equipo,
            $id
        ]);

        header('Location: detalle_ticket.php?id=' . $id . '&editado=1');
        exit;
    }
} else {
    // Precargar valores del ticket para el formulario
    $_POST['titulo']         = $ticket['titulo'];
    $_POST['descripcion']    = $ticket['descripcion'];
    $_POST['prioridad']      = $ticket['prioridad'];
    $_POST['id_categoria']   = $ticket['id_categoria'];
    $_POST['id_ubicacion']   = $ticket['id_ubicacion'];
    $_POST['id_equipo']      = $ticket['id_equipo'];
    $_POST['fecha_problema'] = $ticket['fecha_problema']
        ? date('Y-m-d', strtotime($ticket['fecha_problema']))
        : '';
}
?>

<a class="back" href="detalle_ticket.php?id=<?= $id ?>">← Volver al ticket</a>

<div class="hd">
  <div>
    <p class="k">Edición</p>
    <h1>Editar ticket #<?= $id ?></h1>
    <small class="mu">Corregí los datos si se cargaron mal</small>
  </div>
</div>

<?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="post" action="" class="card" style="max-width:640px">
  <div class="f">
    <label>Título <span class="req">*</span></label>
    <input type="text" name="titulo" required maxlength="150"
           value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>">
  </div>

  <div class="f">
    <label>Descripción <span class="req">*</span></label>
    <textarea name="descripcion" required><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
  </div>

  <div class="g3">
    <div class="f">
      <label>Categoría <span class="req">*</span></label>
      <select name="id_categoria" required>
        <option value="">Elegir…</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= $c['id_categoria'] ?>"
            <?= (($_POST['id_categoria'] ?? '') == $c['id_categoria']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="f">
      <label>Prioridad</label>
      <select name="prioridad">
        <option value="Baja"  <?= ($_POST['prioridad'] ?? '') === 'Baja'  ? 'selected' : '' ?>>Baja</option>
        <option value="Media" <?= ($_POST['prioridad'] ?? '') === 'Media' ? 'selected' : '' ?>>Media</option>
        <option value="Alta"  <?= ($_POST['prioridad'] ?? '') === 'Alta'  ? 'selected' : '' ?>>Alta</option>
      </select>
    </div>
    <div class="f">
      <label>Fecha del problema <span class="req">*</span></label>
      <input type="date" name="fecha_problema" required
             value="<?= htmlspecialchars($_POST['fecha_problema'] ?? '') ?>">
    </div>
  </div>

  <div class="g2">
    <div class="f">
      <label>Ubicación <span class="req">*</span></label>
      <select name="id_ubicacion" id="id_ubicacion" required>
        <option value="">Elegir…</option>
        <?php foreach ($ubicaciones as $u): ?>
          <option value="<?= $u['id_ubicacion'] ?>"
            <?= (($_POST['id_ubicacion'] ?? '') == $u['id_ubicacion']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($u['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="f">
      <label>Equipo afectado <span class="req">*</span></label>
      <select name="id_equipo" id="id_equipo" required>
        <option value="">Elegir equipo…</option>
        <?php foreach ($equipos as $eq): ?>
          <option value="<?= $eq['id_equipo'] ?>" data-ubic="<?= $eq['id_ubicacion'] ?>"
            <?= (($_POST['id_equipo'] ?? '') == $eq['id_equipo']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($eq['codigo'] . ' · ' . $eq['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="ba">
    <a href="detalle_ticket.php?id=<?= $id ?>" class="btn btn-sec">Cancelar</a>
    <button type="submit" class="btn btn-pr">Guardar cambios</button>
  </div>
</form>

<script>
const selUbic = document.getElementById('id_ubicacion');
const selEq   = document.getElementById('id_equipo');
const opts    = [...selEq.options];

function filtrarEquipos() {
  const ubic = selUbic.value;
  const actual = selEq.value;
  selEq.innerHTML = '';
  opts.forEach(o => {
    if (o.value === '' || o.dataset.ubic === ubic || !ubic) {
      const clone = o.cloneNode(true);
      if (clone.value === actual) clone.selected = true;
      selEq.appendChild(clone);
    }
  });
}
selUbic.addEventListener('change', filtrarEquipos);
</script>

<?php require_once 'includes/footer.php'; ?>
