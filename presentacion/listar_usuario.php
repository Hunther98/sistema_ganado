[file name]: listar_usuario.php
[file content begin]
<?php
require_once '../config/config.php';
require_once '../negocio/nUsuario.php';

// Verificar si el usuario es administrador
verificarAutenticacion();
if ($_SESSION['usuario_tipo'] != 'admin') {
    header('Location: index.php?error=permisos');
    exit;
}

$nUsuario = new nUsuario();
$usuarios = $nUsuario->obtenerTodosLosUsuarios();

$titulo = 'Gestión de Usuarios';
?>
<?php include 'template/header.php'; ?>

<div class="container">
    <h1><?php echo $titulo; ?></h1>

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

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['apellido']; ?></td>
                    <td><?php echo $usuario['email']; ?></td>
                    <td><?php echo $usuario['telefono']; ?></td>
                    <td><?php echo $usuario['direccion']; ?></td>
                    <td><?php echo $usuario['tipo']; ?></td>
                    <td><?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'template/footer.php'; ?>