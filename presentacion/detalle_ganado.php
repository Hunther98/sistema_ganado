
<?php
require_once '../config/config.php';
require_once '../negocio/nGanado.php';
require_once '../negocio/nVacunacion.php';
require_once '../negocio/nUsuario.php';
$nGanado = new nGanado();
$nVacunacion = new nVacunacion();
$nUsuario = new nUsuario();


$id = $_GET['id'] ?? 0;
$ganado = $nGanado->obtenerPorId($id);
$usuario = $nUsuario->obtenerUsuarioPorId($ganado['usuario_id']);

if (!$ganado) {
    header('Location: catalogo.php?error=Animal no encontrado');
    exit;
}

$vacunaciones = $nVacunacion->obtenerVacunacionPorId($id);
// $es_propietario = isset($_SESSION['usuario_id']) && $ganado['usuario_id'] == $_SESSION['usuario_id'];

$titulo = $ganado['nombre'];
?>
<?php include 'template/header.php'; ?>

<div class="container">
    <h1><?php echo $titulo; ?></h1>

    <div class="card">
        <div class="card-body">
            <h5>Detalles del Animal</h5>
            <p><strong>Nombre:</strong> <?php echo $ganado['nombre']; ?></p>
            <p><strong>Raza:</strong> <?php echo $ganado['raza']; ?></p>
            <p><strong>Edad:</strong> <?php echo $ganado['edad']; ?> años</p>
            <p><strong>Propietario:</strong> <?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></p>
        </div>
    </div>

    <h5 class="mt-4">Historial de Vacunaciones</h5>
    <?php if (count($vacunaciones) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Vacuna</th>
                    <th>Veterinario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vacunaciones as $vacuna): ?>
                    <tr>
                        <td><?php echo $vacuna['id']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($vacuna['fecha'])); ?></td>
                        <td><?php echo $vacuna['nombre']; ?></td>
                        <td><?php echo $vacuna['veterinario']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No hay vacunaciones registradas para este animal.</div>
    <?php endif; ?>
</div>

<?php include 'template/footer.php'; ?>