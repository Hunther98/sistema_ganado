<?php
require_once '../config/config.php';
verificarAutenticacion('admin');
require_once '../negocio/nUsuario.php';
require_once '../negocio/nGanado.php';
require_once '../negocio/nVenta.php';


$nUsuario = new nUsuario();
$nGanado = new nGanado();
$nVenta = new nVenta();
// Obtener estadísticas
$totalUsuarios = count($nUsuario->obtenerTodosLosUsuarios());
$totalGanado = count($nGanado->obtenerTodosLosAnimales());
$totalVentas = count($nVenta->obtenerTodasLasVentas());
// Obtener últimos registros
$usuariosRecientes = array_slice($nUsuario->obtenerTodosLosUsuarios(), 0, 5);
$ganadoReciente = array_slice($nGanado->obtenerTodosLosAnimales(), 0, 5);
$ventasRecientes = array_slice($nVenta->obtenerTodasLasVentas(), 0, 5); 
$titulo = 'Panel de Administración';
?>
<?php include 'template/header.php'; ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Panel de Administración</h2>
        <span class="badge bg-primary">Admin</span>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Usuarios</h6>
                            <h3 class="mb-0"><?php echo $totalUsuarios; ?></h3>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Animales</h6>
                            <h3 class="mb-0"><?php echo $totalGanado; ?></h3>
                        </div>
                        <i class="fas fa-cow fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Ventas</h6>
                            <h3 class="mb-0"><?php echo $totalVentas; ?></h3>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Registros -->
    <h4 class="mb-4">Últimos Registros</h4>
    <div class="row">
        <div class="col-md-4 mb-3">
            <h5>Usuarios Recientes</h5>
            <ul class="list-group">
                <?php foreach ($usuariosRecientes as $usuario): ?>
                    <li class="list-group-item"><?php echo $usuario['nombre']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-4 mb-3">
            <h5>Animales Recientes</h5>
            <ul class="list-group">
                <?php foreach ($ganadoReciente as $animal): ?>
                    <li class="list-group-item"><?php echo $animal['nombre']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-4 mb-3">
            <h5>Ventas Recientes</h5>
            <ul class="list-group">
                <?php foreach ($ventasRecientes as $venta): ?>
                    <li class="list-group-item"><?php echo $venta['producto']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php include 'template/footer.php'; ?>