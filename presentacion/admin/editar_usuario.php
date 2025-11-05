<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../negocio/nUsuario.php';

// Verificar si el usuario es administrador
verificarAutenticacion();
if ($_SESSION['usuario_tipo'] != 'admin') {
    header('Location: ../index.php?error=permisos');
    exit;
}

$nUsuario = new nUsuario();
$id = $_GET['id'] ?? '';
$mensaje = '';

// Obtener datos del usuario
$usuario = $nUsuario->obtenerUsuarioPorId($id);
if (!$usuario) {
    header('Location: listar_usuario.php?error=Usuario no encontrado');
    exit;
}
// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $activo = isset($_POST['activo']) ? 1 : 0;

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($email) || empty($tipo)) {
        $mensaje = 'Todos los campos obligatorios deben ser completados';
    } else {
        // Actualizar usuario
        $nUsuario = new nUsuario();
        $resultado = $nUsuario->actualizarUsuario($id, $nombre, $apellido, $email, $telefono, $direccion, $tipo, $activo);

        if ($resultado['exito']) {
            header('Location: listar_usuario.php?exito=' . urlencode($resultado['mensaje']));
            exit;
        } else {
            $mensaje = $resultado['mensaje'];
        }
    }
}

$titulo = 'Editar Usuario';
$rootPath = '../';
?>
<?php include __DIR__ . '/../template/header.php'; ?>

<div class="scrollable-card">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_GET['success']; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_GET['error']; ?>
        </div>
    <?php endif; ?>
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="listar_usuario.php">Usuarios</a></li>
            <li class="breadcrumb-item active">Editar Usuario</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Usuario</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php endif; ?>
                    <?php if (isset($resultado) && $resultado['exito'] == true): ?>
                        <div class="alert alert-success"><?php echo $resultado['mensaje']; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" 
                                       value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo htmlspecialchars($usuario['direccion'] ?? ''); ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">Tipo de Usuario *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="comprador" <?php echo $usuario['tipo'] == 'comprador' ? 'selected' : ''; ?>>Comprador</option>
                                    <option value="vendedor" <?php echo $usuario['tipo'] == 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                                    <option value="admin" <?php echo $usuario['tipo'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estado</label>
                                <div class="form-check form-switch">
                     <input class="form-check-input" type="checkbox" id="activo" name="activo" 
                         <?php echo (!empty($usuario['activo']) && $usuario['activo']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="activo">
                                        Usuario Activo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha de Registro</label>
                            <div class="form-control-plaintext">
                                <?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Actualizar Usuario</button>
                            <a href="listar_usuario.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../template/footer.php'; ?>

