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
$usuariosRecientes = array_slice($nUsuario->obtenerUsuariosRecientes(), 0, 5);
$ganadoReciente = array_slice($nGanado->obtenerGanadoReciente(), 0, 5);
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
    <div class="row mb-3 justify-content-center">
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
            <?php if (!empty($usuariosRecientes)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuariosRecientes as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['apellido'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['tipo'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No hay usuarios recientes.</div>
            <?php endif; ?>
        </div>
        <div class="col-md-4 mb-3">
            <h5>Animales Recientes</h5>
            <?php if (!empty($ganadoReciente)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Raza</th>
                                <th class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ganadoReciente as $animal): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($animal['nombre'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($animal['raza'] ?? ''); ?></td>
                                    <td class="text-end"><?php echo isset($animal['precio']) ? number_format($animal['precio'], 2) : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No hay animales recientes.</div>
            <?php endif; ?>
        </div>
        <div class="col-md-4 mb-3">
            <h5>Ventas Recientes</h5>
            <?php if (!empty($ventasRecientes)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Comprador</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventasRecientes as $venta): 
                                $comprador = $nUsuario->obtenerUsuarioPorId($venta['comprador_id']) ?? null;
                                $nombreComprador = $comprador ? trim(($comprador['nombre'] ?? '') . ' ' . ($comprador['apellido'] ?? '')) : 'N/A';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($nombreComprador); ?></td>
                                    <td><?php echo htmlspecialchars($venta['estado'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($venta['fecha'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No hay ventas recientes.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'template/footer.php'; ?>