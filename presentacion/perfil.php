<?php
require_once '../config/config.php';
require_once '../negocio/nUsuario.php';

verificarAutenticacion();

$nUsuario = new nUsuario();
$usuario = $nUsuario->obtenerUsuario($_SESSION['usuario_id']);
$mensaje = '';
$titulo = 'Mi Perfil';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mi Perfil</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-<?php echo strpos($mensaje, 'correctamente') !== false ? 'success' : 'danger'; ?>">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo $usuario['nombre'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" 
                                       value="<?php echo $usuario['apellido'] ?? ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?php echo $usuario['email'] ?? ''; ?>" disabled>
                            <small class="text-muted">El email no puede ser modificado</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   value="<?php echo $usuario['telefono'] ?? ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección de la Hacienda/Finca</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo $usuario['direccion'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipo de Cuenta</label>
                            <div>
                                <span class="badge bg-<?php echo $usuario['tipo'] == 'vendedor' ? 'success' : 'primary'; ?>">
                                    <?php echo ucfirst($usuario['tipo']); ?>
                                </span>
                            </div>
                            <small class="text-muted">
                                <?php if ($usuario['tipo'] == 'comprador'): ?>
                                    ¿Deseas vender ganado? <a href="solicitar_vendedor.php">Solicitar cuenta de vendedor</a>
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Fecha de Registro</label>
                            <div><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Actualizar Perfil</button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="mt-4">
                        <h5>Acciones de Cuenta</h5>
                        <div class="d-flex gap-2">
                            <a href="pRestablecer_Password.php" class="btn btn-outline-warning">Cambiar Contraseña</a>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCuenta">
                                Eliminar Cuenta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar Cuenta -->
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Advertencia:</strong> Esta acción no se puede deshacer. Se eliminarán todos tus datos y publicaciones.
                </div>
                <p>¿Estás seguro de que deseas eliminar tu cuenta permanentemente?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="admin/confirmar_eliminacion.php" class="btn btn-outline-danger">
                    <i class="fas fa-trash"></i> Eliminar Cuenta
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Eliminar Cuenta -->
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación de Cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Advertencia: Esta acción no se puede deshacer</strong>
                </div>

                <p>Para confirmar que eres el propietario de esta cuenta, ingresa tu contraseña:</p>

                <form method="POST" action="admin/procesar_eliminar_cuenta.php" id="formEliminarCuenta">
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-info-circle"></i> ¿Qué sucede cuando eliminas tu cuenta?</h6>
                        <ul class="small">
                            <li>Todos tus datos personales serán eliminados permanentemente</li>
                            <li>Tu ganado registrado será eliminado (primero debes eliminarlo manualmente)</li>
                            <li>Tus transacciones pendientes serán canceladas</li>
                            <li>No podrás recuperar tu cuenta después de la eliminación</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Eliminar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validación adicional antes de enviar el formulario
document.getElementById('formEliminarCuenta').addEventListener('submit', function(e) {
    const password = document.getElementById('password_confirm').value;
    
    if (!password) {
        e.preventDefault();
        alert('Debes ingresar tu contraseña para confirmar la eliminación');
        return false;
    }
    
    if (!confirm('¿ESTÁS ABSOLUTAMENTE SEGURO? Esta acción no se puede deshacer y todos tus datos se perderán permanentemente.')) {
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>
<?php include 'template/footer.php'; ?>