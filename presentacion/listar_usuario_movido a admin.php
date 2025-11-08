<?php
// Archivo movido a presentacion/admin/listar_usuario.php
header('Location: admin/listar_usuario.php');
exit;
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Usuarios</h2>
        <a href="registrar_usuario.php" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>
    <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success"><?php echo urldecode($_GET['exito']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo urldecode($_GET['error']); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Lista de Usuarios</h5>
        </div>
        <div class="card-body">
            <?php if (count($usuarios) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaUsuarios">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo $usuario['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <?php echo strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellido'], 0, 1)); ?>
                                                </div>
                                            </div>
                                            <div>
                                                <strong><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $usuario['email']; ?></td>
                                    <td><?php echo $usuario['telefono'] ?? 'N/A'; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $usuario['tipo'] == 'admin' ? 'danger' : ($usuario['tipo'] == 'vendedor' ? 'success' : 'primary'); ?>">
                                            <?php echo ucfirst($usuario['tipo']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $usuario['activo'] ? 'success' : 'secondary'; ?>">
                                            <?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="admin/editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetalles<?php echo $usuario['id']; ?>" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar<?php echo $usuario['id']; ?>" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-secondary" disabled title="No puedes eliminarte a ti mismo">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Detalles -->
                                <div class="modal fade" id="modalDetalles<?php echo $usuario['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles del Usuario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4 text-center">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; font-size: 24px;">
                                                            <?php echo strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellido'], 0, 1)); ?>
                                                        </div>
                                                        <h5 class="mt-3"><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></h5>
                                                        <span class="badge bg-<?php echo $usuario['tipo'] == 'admin' ? 'danger' : ($usuario['tipo'] == 'vendedor' ? 'success' : 'primary'); ?>">
                                                            <?php echo ucfirst($usuario['tipo']); ?>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-6 mb-2">
                                                                <strong>Email:</strong><br>
                                                                <?php echo $usuario['email']; ?>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <strong>Teléfono:</strong><br>
                                                                <?php echo $usuario['telefono'] ?? 'N/A'; ?>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <strong>Estado:</strong><br>
                                                                <span class="badge bg-<?php echo $usuario['activo'] ? 'success' : 'secondary'; ?>">
                                                                    <?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?>
                                                                </span>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <strong>Fecha Registro:</strong><br>
                                                                <?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?>
                                                            </div>
                                                            <div class="col-12 mb-2">
                                                                <strong>Dirección:</strong><br>
                                                                <?php echo $usuario['direccion'] ?? 'N/A'; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <a href="admin/editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary">Editar Usuario</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Eliminar -->
                                <div class="modal fade" id="modalEliminar<?php echo $usuario['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmar Eliminación
                                                </h5>
                                                <button type="button" class="btn-close" data-b
                                                    s-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                                                </div>
                                                <p>¿Estás seguro de que deseas eliminar al usuario <strong><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></strong>?</p>
                                                <p class="text-muted">Email: <?php echo $usuario['email']; ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form method="POST" action="admin/procesar_usuario.php">
                                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <button type="submit" class="btn btn-danger">Eliminar Usuario</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 p-3 bg-light rounded">
                    <h5>Resumen de Usuarios</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Total:</strong> <?php echo count($usuarios); ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Administradores:</strong> <?php echo count(array_filter($usuarios, function ($u) {
                                                                    return $u['tipo'] == 'admin';
                                                                })); ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Vendedores:</strong> <?php echo count(array_filter($usuarios, function ($u) {
                                                                return $u['tipo'] == 'vendedor';
                                                            })); ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Compradores:</strong> <?php echo count(array_filter($usuarios, function ($u) { return $u['tipo'] == 'comprador' && null;})); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h4>No hay usuarios registrados</h4>
                    <p>Comienza registrando el primer usuario en el sistema</p>
                    <a href="registrar_usuario.php" class="btn btn-primary mt-2">Registrar Primer Usuario</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- DataTables Script -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaUsuarios').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
            },
            order: [
                [0, 'desc']
            ],
            pageLength: 10,
            responsive: true
        });
    });
</script>
<?php include 'template/footer.php'; ?>
