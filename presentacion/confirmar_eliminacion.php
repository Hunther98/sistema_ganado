<?php
require_once '../config/config.php';
require_once '../negocio/nUsuario.php';
require_once '../negocio/nGanado.php';
require_once '../negocio/nVenta.php';
verificarAutenticacion();

$nUsuario = new nUsuario();
$nGanado = new nGanado();
$nVenta = new nVenta();
$usuario = $nUsuario->obtenerUsuario($_SESSION['usuario_id']);
$ganado_usuario = $nGanado->obtenerPorUsuario($_SESSION['usuario_id']);
$ventas_pendientes = $nVenta->obtenerVentasPendientesPorUsuario($_SESSION['usuario_id']);
$password_confirm = $_POST['password_confirm'] ?? '';


$titulo = 'Confirmar Eliminación de Cuenta';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación de Cuenta
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-circle"></i> ¡Advertencia!</h5>
                        <p class="mb-0">Estás a punto de eliminar tu cuenta permanentemente. Esta acción no se puede deshacer.</p>
                    </div>

                    <?php if (count($ganado_usuario) > 0): ?>
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-cow"></i> Tienes ganado registrado</h6>
                            <p>Actualmente tienes <strong><?php echo count($ganado_usuario); ?> animales</strong> registrados en el sistema.</p>
                            <p class="mb-0">Debes eliminar o transferir tu ganado antes de poder eliminar tu cuenta.</p>
                        </div>
                        <div class="text-center">
                            <a href="listar_ganado.php" class="btn btn-warning">
                                <i class="fas fa-list"></i> Gestionar mi Ganado
                            </a>
                        </div>
                    <?php elseif (count($ventas_pendientes) > 0): ?>
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-shopping-cart"></i> Tienes transacciones pendientes</h6>
                            <p>Actualmente tienes <strong><?php echo count($ventas_pendientes); ?> transacciones pendientes</strong>.</p>
                            <p class="mb-0">Debes completar o cancelar tus transacciones antes de poder eliminar tu cuenta.</p>
                        </div>
                        <div class="text-center">
                            <a href="mis_ventas.php" class="btn btn-warning me-2">
                                <i class="fas fa-store"></i> Mis Ventas
                            </a>
                            <a href="mis_compras.php" class="btn btn-warning">
                                <i class="fas fa-shopping-cart"></i> Mis Compras
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Información importante</h6>
                            <p>Al eliminar tu cuenta:</p>
                            <ul>
                                <li>Todos tus datos personales serán eliminados permanentemente</li>
                                <li>Tu historial de transacciones será eliminado</li>
                                <li>No podrás recuperar tu cuenta</li>
                                <li>Esta acción es irreversible</li>
                            </ul>
                        </div>

                        <form method="POST" action="procesar_eliminar_cuenta.php" id="formEliminarCuenta">
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">
                                    <strong>Ingresa tu contraseña para confirmar:</strong>
                                </label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                <div class="form-text">Debes ingresar tu contraseña actual para verificar tu identidad.</div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="confirmacion" required>
                                <label class="form-check-label" for="confirmacion">
                                    Comprendo que esta acción es permanente e irreversible
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-trash"></i> ELIMINAR MI CUENTA PERMANENTEMENTE
                                </button>
                                <a href="perfil.php" class="btn btn-secondary">Cancelar y volver al perfil</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formEliminarCuenta').addEventListener('submit', function(e) {
    if (!confirm('¿ESTÁS ABSOLUTAMENTE SEGURO DE QUE QUIERES ELIMINAR TU CUENTA? ESTA ACCIÓN NO SE PUEDE DESHACER.')) {
        e.preventDefault();
    }
});
</script>

<?php include 'template/footer.php'; ?>