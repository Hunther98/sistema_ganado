<?php
require_once '../config/config.php';
require_once '../negocio/nGanado.php';

verificarAutenticacion('vendedor');

$nGanado = new nGanado();
$ganado = $nGanado->obtenerPorUsuario($_SESSION['usuario_id']);

$titulo = 'Mis Animales';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Animales</h2>
        <a href="pGanado.php?accion=agregar" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Animal
        </a>
    </div>

    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success"><?php echo $_GET['exito']; ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
    <?php endif; ?>

    <div class="row">
        <?php if (count($ganado) > 0): ?>
            <?php foreach ($ganado as $animal): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <img src="../uploads/<?php echo $animal['imagen']; ?>" class="card-img-top" alt="<?php echo $animal['nombre']; ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $animal['nombre']; ?></h5>
                            <h6 class="text-primary">$<?php echo number_format($animal['precio'], 2); ?></h6>
                            
                            <div class="row mb-2">
                                <div class="col-6">
                                    <small class="text-muted"><i class="fas fa-cow"></i> <?php echo $animal['raza']; ?></small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted"><i class="fas fa-birthday-cake"></i> <?php echo $animal['edad']; ?> años</small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted"><i class="fas fa-weight"></i> <?php echo $animal['peso']; ?> kg</small>
                                </div>
                                <div class="col-6">
                                    <span class="badge bg-<?php echo $animal['estado'] == 'disponible' ? 'success' : ($animal['estado'] == 'vendido' ? 'danger' : 'warning'); ?>">
                                        <?php echo ucfirst($animal['estado']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <p class="card-text small"><?php echo substr($animal['descripcion'], 0, 100); ?>...</p>
                            
                            <div class="d-flex justify-content-between">
                                <a href="detalle_ganado.php?id=<?php echo $animal['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <?php if ($animal['estado'] == 'disponible'): ?>
                                    <a href="pGanado.php?accion=editar&id=<?php echo $animal['id']; ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                <?php endif; ?>
                                <a href="pGanado.php?accion=eliminar&id=<?php echo $animal['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar este animal?');">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>
                            </div>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Publicado: <?php echo date('d/m/Y', strtotime($animal['fecha_registro'])); ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>No tienes animales registrados</h4>
                    <p>Comienza publicando tu primer animal en la plataforma</p>
                    <a href="pGanado.php?accion=agregar" class="btn btn-primary mt-2">Publicar Primer Animal</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'template/footer.php'; ?>