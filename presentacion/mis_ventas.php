[file name]: mis_ventas.php
[file content begin]
<?php
require_once '../config/config.php';
require_once '../negocio/nVenta.php';

verificarAutenticacion('vendedor');

$nVenta = new nVenta();
$ventas = $nVenta->obtenerVentasPorVendedor($_SESSION['usuario_id']);

$titulo = 'Mis Ventas';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">Mis Ventas</h2>

    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success"><?php echo $_GET['exito']; ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Historial de Ventas</h5>
        </div>
        <div class="card-body">
            <?php if (count($ventas) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Animal</th>
                                <th>Comprador</th>
                                <th>Fecha</th>
                                <th>Precio</th>
                                <th>Comisión</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $venta): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../uploads/<?php echo $venta['ganado_imagen']; ?>" alt="<?php echo $venta['ganado_nombre']; ?>" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div><?php echo $venta['ganado_nombre']; ?></div>
                                        </div>
                                    </td>
                                    <td><?php echo $venta['comprador_nombre'] . ' ' . $venta['comprador_apellido']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($venta['fecha'])); ?></td>
                                    <td>$<?php echo number_format($venta['precio_venta'], 2); ?></td>
                                    <td>$<?php echo number_format($venta['comision'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $venta['estado'] == 'completada' ? 'success' : ($venta['estado'] == 'cancelada' ? 'danger' : 'warning'); ?>">
                                            <?php echo ucfirst($venta['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="detalle_venta.php?id=<?php echo $venta['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <?php if ($venta['estado'] == 'pendiente'): ?>
                                            <form method="POST" action="procesar_venta.php" class="d-inline">
                                                <input type="hidden" name="venta_id" value="<?php echo $venta['id']; ?>">
                                                <input type="hidden" name="accion" value="completar">
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Confirmar venta completada?')">
                                                    <i class="fas fa-check"></i> Completar
                                                </button>
                                            </form>
                                            <form method="POST" action="procesar_venta.php" class="d-inline">
                                                <input type="hidden" name="venta_id" value="<?php echo $venta['id']; ?>">
                                                <input type="hidden" name="accion" value="cancelar">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Cancelar esta venta?')">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 p-3 bg-light rounded">
                    <h5>Resumen de Ventas</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Total Ventas:</strong> $<?php echo number_format(array_sum(array_column($ventas, 'precio_venta')), 2); ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Total Comisiones:</strong> $<?php echo number_format(array_sum(array_column($ventas, 'comision')), 2); ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Ventas Completadas:</strong> <?php echo count(array_filter($ventas, function($v) { return $v['estado'] == 'completada'; })); ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Ventas Pendientes:</strong> <?php echo count(array_filter($ventas, function($v) { return $v['estado'] == 'pendiente'; })); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>No tienes ventas registradas</h4>
                    <p>Cuando realices ventas, aparecerán en este historial.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>
[file content end]