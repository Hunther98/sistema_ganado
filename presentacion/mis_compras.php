<?php
require_once '../config/config.php';
require_once '../negocio/nVenta.php';

verificarAutenticacion();

$nVenta = new nVenta();
$compras = $nVenta->obtenerVentasPorComprador($_SESSION['usuario_id']);

$titulo = 'Mis Compras';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">Mis Compras</h2>

    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success"><?php echo $_GET['exito']; ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Historial de Compras</h5>
        </div>
        <div class="card-body">
            <?php if (count($compras) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Animal</th>
                                <th>Vendedor</th>
                                <th>Fecha</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($compras as $compra): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../uploads/<?php echo $compra['ganado_imagen']; ?>" alt="<?php echo $compra['ganado_nombre']; ?>" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div><?php echo $compra['ganado_nombre']; ?></div>
                                        </div>
                                    </td>
                                    <td><?php echo $compra['vendedor_nombre'] . ' ' . $compra['vendedor_apellido']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($compra['fecha'])); ?></td>
                                    <td>$<?php echo number_format($compra['precio_venta'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $compra['estado'] == 'completada' ? 'success' : ($compra['estado'] == 'cancelada' ? 'danger' : 'warning'); ?>">
                                            <?php echo ucfirst($compra['estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="detalle_compra.php?id=<?php echo $compra['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <?php if ($compra['estado'] == 'pendiente'): ?>
                                            <form method="POST" action="procesar_compra.php" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $compra['id']; ?>">
                                                <input type="hidden" name="estado" value="cancelada">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Cancelar esta compra?')">
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
                    <h5>Resumen de Compras</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Total Gastado:</strong> $<?php echo number_format(array_sum(array_column($compras, 'precio_venta')), 2); ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Compras Completadas:</strong> <?php echo count(array_filter($compras, function($c) { return $c['estado'] == 'completada'; })); ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Compras Pendientes:</strong> <?php echo count(array_filter($compras, function($c) { return $c['estado'] == 'pendiente'; })); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>No tienes compras registradas</h4>
                    <p>Cuando realices compras, aparecerán en este historial.</p>
                    <a href="catalogo.php" class="btn btn-primary mt-2">Explorar Catálogo</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>