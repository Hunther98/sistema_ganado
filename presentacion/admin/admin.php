<?php
require_once __DIR__ . '/../../config/config.php';
verificarAutenticacion('admin');
require_once __DIR__ . '/../../negocio/nUsuario.php';
require_once __DIR__ . '/../../negocio/nGanado.php';
require_once __DIR__ . '/../../negocio/nVenta.php';

$nUsuario = new nUsuario();
$nGanado = new nGanado();
$nVenta = new nVenta();

// Obtener estadísticas
$totalUsuarios = count($nUsuario->obtenerTodosLosUsuarios());
$totalGanado = count($nGanado->obtenerTodos());
$ventas = $nVenta->obtenerVentasPorVendedor($_SESSION['usuario_id']);
$totalVentas = count($ventas);

// Obtener últimos registros
$usuariosRecientes = array_slice($nUsuario->obtenerTodosLosUsuarios(), 0, 5);
$ganadoReciente = array_slice($nGanado->obtenerTodos(), 0, 5);

$titulo = 'Panel de Administración';
$rootPath = '../';
?>

<?php include __DIR__ . '/../template/header.php'; ?>

<div class="container-fluid py-4 scrollable-admin">
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
            <div class="card bg-info text-white">
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
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Ingresos</h6>
                            <h3 class="mb-0">$<?php echo number_format(array_sum(array_column($ventas, 'precio_venta')), 2); ?></h3>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Usuarios Recientes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Usuarios Recientes</h5>
                    <a href="listar_usuario.php" class="btn btn-sm btn-primary">Ver Todos</a>
                </div>
                <div class="card-body">
                    <?php if (count($usuariosRecientes) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Tipo</th>
                                        <th>Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuariosRecientes as $usuario): ?>
                                        <tr>
                                            <td><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></td>
                                            <td><?php echo $usuario['email']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $usuario['tipo'] == 'admin' ? 'danger' : ($usuario['tipo'] == 'vendedor' ? 'success' : 'primary'); ?>">
                                                    <?php echo ucfirst($usuario['tipo']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay usuarios registrados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ganado Reciente -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ganado Reciente</h5>
                    <a href="../detalle_ganado.php" class="btn btn-sm btn-primary">Ver Todo</a>
                </div>
                <div class="card-body">
                    <?php if (count($ganadoReciente) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Raza</th>
                                        <th>Precio</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ganadoReciente as $animal): ?>
                                        <tr>
                                            <td><?php echo $animal['nombre']; ?></td>
                                            <td><?php echo $animal['raza']; ?></td>
                                            <td>$<?php echo number_format($animal['precio'], 2); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $animal['estado'] == 'disponible' ? 'success' : 'danger'; ?>">
                                                    <?php echo ucfirst($animal['estado']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay ganado registrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="listar_usuario.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users"></i> Gestionar Usuarios
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="ver_tablas.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-database"></i> Ver Tablas BD
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="../registrar_usuario.php" class="btn btn-outline-success w-100">
                                <i class="fas fa-user-plus"></i> Nuevo Usuario
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="../../utilidades/logout.php" class="btn btn-outline-danger w-100">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../template/footer.php'; ?>
