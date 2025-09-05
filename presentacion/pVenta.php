<?php
require_once '../config/config.php';
require_once '../negocio/nVenta.php';
require_once '../negocio/nGanado.php';

verificarAutenticacion();

$nVenta = new nVenta();
$nGanado = new nGanado();

$ganado_id = $_GET['ganado_id'] ?? 0;
$mensaje = '';

// Verificar que el ganado existe y está disponible
$ganado = $nGanado->obtenerPorId($ganado_id);
if (!$ganado || $ganado['estado'] != 'disponible') {
    header('Location: catalogo.php?error=El ganado no está disponible');
    exit;
}

// No permitir comprar propio ganado
if ($ganado['usuario_id'] == $_SESSION['usuario_id']) {
    header('Location: catalogo.php?error=No puedes comprar tu propio ganado');
    exit;
}

// Procesar compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $nVenta->procesarVenta($_SESSION['usuario_id'], $ganado_id);
    
    if ($resultado['exito']) {
        header('Location: mis_compras.php?exito=' . urlencode($resultado['mensaje']));
        exit;
    } else {
        $mensaje = $resultado['mensaje'];
    }
}

$titulo = 'Procesar Compra';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Confirmar Compra</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Información importante</h5>
                        <p>Al confirmar la compra, se registrará una transacción pendiente. El vendedor será notificado y deberá confirmar la venta.</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <img src="../uploads/<?php echo $ganado['imagen']; ?>" alt="<?php echo $ganado['nombre']; ?>" class="img-fluid rounded">
                        </div>
                        <div class="col-md-7">
                            <h4><?php echo $ganado['nombre']; ?></h4>
                            <p class="text-muted"><?php echo $ganado['descripcion']; ?></p>
                            
                            <div class="row">
                                <div class="col-6">
                                    <strong>Raza:</strong><br>
                                    <span class="badge bg-primary"><?php echo $ganado['raza']; ?></span>
                                </div>
                                <div class="col-6">
                                    <strong>Edad:</strong><br>
                                    <span><?php echo $ganado['edad']; ?> años</span>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-6">
                                    <strong>Peso:</strong><br>
                                    <span><?php echo $ganado['peso']; ?> kg</span>
                                </div>
                                <div class="col-6">
                                    <strong>Ubicación:</strong><br>
                                    <span><?php echo $ganado['ubicacion']; ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <h3 class="text-success">$<?php echo number_format($ganado['precio'], 2); ?></h3>
                                <small class="text-muted">Incluye comisión del 5%: $<?php echo number_format($ganado['precio'] * 0.05, 2); ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Información del Vendedor</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nombre:</strong><br>
                                    <?php echo $ganado['vendedor_nombre'] . ' ' . $ganado['vendedor_apellido']; ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Teléfono:</strong><br>
                                    <?php echo $ganado['vendedor_telefono'] ?? 'No disponible'; ?>
                                </div>
                            </div>
                            <div class="mt-2">
                                <strong>Ubicación:</strong><br>
                                <?php echo $ganado['ubicacion']; ?>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmacion" required>
                            <label class="form-check-label" for="confirmacion">
                                Confirmo que deseo proceder con la compra de este animal y acepto los términos y condiciones.
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle"></i> Confirmar Compra
                            </button>
                            <a href="catalogo.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>