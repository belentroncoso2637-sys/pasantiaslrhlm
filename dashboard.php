
<?php
$titulo = 'Inicio';
$pagina_actual = 'home';
require_once 'includes/header.php';

// Estados disponibles
$estados = $pdo->query("
    SELECT id_estado, nombre
    FROM estado
    ORDER BY id_estado
")->fetchAll();

// Contar solicitudes por estado
$conteos = [];
foreach ($estados as $e) {
    $st = $pdo->prepare("
        SELECT COUNT(*)
        FROM Solicitudes
        WHERE id_estado = ?
    ");
    $st->execute([$e['id_estado']]);
    $conteos[$e['id_estado']] = (int)$st->fetchColumn();
}

$total = array_sum($conteos);

// Últimas solicitudes
$ultimos = $pdo->query("
    SELECT
        s.id_solicitud,
        s.descripcion,
        s.fecha,
        e.nombre AS estado,
        p.nombre AS prioridad,
        u.nombre AS ubicacion,
        us.nombre AS solicitante,
        eq.nombre AS equipo
    FROM Solicitudes s
    JOIN estado e ON e.id_estado = s.id_estado
    JOIN Equipos eq ON eq.id_equipo = s.id_equipo
    JOIN Ubicaciones u ON u.id_ubicacion = eq.id_ubicacion
    JOIN Usuarios us ON us.id_usuario = s.id_usuario
    JOIN Prioridad p ON p.id_prioridad = s.id_prioridad
    ORDER BY s.fecha DESC
    LIMIT 8
")->fetchAll();
?>

<div class="hd">
    <div>
        <p class="k">Panel principal</p>
        <h1>Inicio</h1>
        <small class="mu">Resumen de solicitudes del colegio</small>
    </div>
    <a href="nuevo_ticket.php" class="btn btn-pr">
        + Nueva solicitud
    </a>
</div>

<div class="card">
    <p class="k">Distribución por estado</p>

    <?php foreach ($estados as $e):
        $cantidad = $conteos[$e['id_estado']];
        $porcentaje = $total > 0
            ? round(($cantidad / $total) * 100)
            : 0;
    ?>
        <div style="margin-bottom:12px">
            <div style="display:flex;justify-content:space-between">
                <span><?= htmlspecialchars($e['nombre']) ?></span>
                <strong><?= $cantidad ?> (<?= $porcentaje ?>%)</strong>
            </div>
            <div style="height:8px;background:#e5e7eb;border-radius:5px">
                <div style="height:8px;width:<?= $porcentaje ?>%;background:#527da8;border-radius:5px"></div>
            </div>
        </div>
    <?php endforeach; ?>

    <p class="mu">Total de solicitudes: <?= $total ?></p>
</div>

<div class="hd2" style="margin-top:28px">
    <div>
        <p class="k">Actividad reciente</p>
        <h2>Últimas solicitudes</h2>
    </div>
    <a href="tickets.php" class="btn btn-sec">Ver todas</a>
</div>

<div class="tiles">
    <?php if (empty($ultimos)): ?>
        <p class="mu">Todavía no hay solicitudes cargadas.</p>
    <?php else: ?>
        <?php foreach ($ultimos as $t): ?>
            <div class="tile">
                <div class="tt">
                    <h3>
                        <?= htmlspecialchars($t['descripcion']) ?>
                    </h3>
                    <span class="b">
                        <?= htmlspecialchars($t['estado']) ?>
                    </span>
                </div>

                <div class="meta">
                    <?= htmlspecialchars($t['equipo']) ?> ·
                    <?= htmlspecialchars($t['ubicacion']) ?>
                </div>

                <div class="meta">
                    <?= htmlspecialchars($t['prioridad']) ?> ·
                    <?= htmlspecialchars($t['solicitante']) ?>
                </div>

                <div class="meta">
                    <?= date('d/m/Y H:i', strtotime($t['fecha'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

