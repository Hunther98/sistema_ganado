<?php
require_once __DIR__ . '/../../config/config.php';

verificarAutenticacion("admin");
// Verificar si el usuario está autenticado y es administrador
// if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

require_once __DIR__ . '/../../negocio/nAsignarRol.php';

$nAsignarRol = new nAsignarRol();
$usuarios = $nAsignarRol->obtenerUsuariosConRoles();
$roles = $nAsignarRol->obtenerTodosRoles();

$mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;
$tipo_mensaje = isset($_SESSION['tipo_mensaje']) ? $_SESSION['tipo_mensaje'] : null;
unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
method_exists($nAsignarRol, 'asignarRol');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Roles - Sistema Ganadero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --accent-color: #3498db;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-main {
            padding: 2rem 0;
        }

        .header-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .table-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .table-custom thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
        }

        .table-custom thead th {
            border: none;
            padding: 1rem;
        }

        .table-custom tbody tr:hover {
            background: #f8f9fa;
        }

        .badge-rol {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .btn-asignar {
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            background: var(--accent-color);
            color: white;
            border: none;
        }

        .btn-asignar:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
        }

        .back-button {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--accent-color);
            color: white;
            border: none;
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-button:hover {
            background: #2980b9;
            transform: translateY(-5px);
        }

        .sin-rol {
            color: #95a5a6;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container container-main">
        <div class="header-card">
            <h1><i class="fas fa-users-cog me-2"></i>Asignar Roles a Usuarios</h1>
            <p class="text-muted mb-0">Gestiona los roles de cada usuario del sistema</p>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-card">
            <h2 class="mb-4"><i class="fas fa-list me-2"></i>Lista de Usuarios</h2>

            <?php if (empty($usuarios)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users" style="font-size: 4rem; color: #bdc3c7;"></i>
                    <h4 class="mt-3">No hay usuarios registrados</h4>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Tipo</th>
                                <th>Rol Actual</th>
                                <th>Estado</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><strong>#<?php echo $usuario['id']; ?></strong></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefono'] ?? '-'); ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo ucfirst($usuario['tipo']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($usuario['rol_nombre']): ?>
                                        <span class="badge badge-rol bg-primary">
                                            <i class="fas fa-user-tag me-1"></i>
                                            <?php echo htmlspecialchars($usuario['rol_nombre']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="sin-rol">Sin rol asignado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($usuario['activo']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-asignar" 
                                            onclick="abrirModal(<?php echo $usuario['id']; ?>, '<?php echo addslashes($usuario['nombre'] . ' ' . $usuario['apellido']); ?>', <?php echo $usuario['rol_id'] ?? 'null'; ?>)">
                                        <i class="fas fa-user-edit me-1"></i>Asignar Rol
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <button class="back-button" onclick="window.location.href='admin.php'" title="Volver al Dashboard">
            <i class="fas fa-arrow-left"></i>
        </button>
    </div>

    <!-- Modal Asignar Rol -->
    <div class="modal fade" id="modalAsignarRol" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class="fas fa-user-tag me-2"></i>Asignar Rol a Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="procesarAsignarRol.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="usuario_id" id="usuario_id">
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-user me-1"></i>Usuario:
                            </label>
                            <p class="form-control-static"><strong id="usuario_nombre"></strong></p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="rol_id" class="form-label">
                                <i class="fas fa-user-tag me-1"></i>Seleccionar Rol:
                            </label>
                            <select class="form-select" id="rol_id" name="rol_id">
                                <option value="">-- Sin Rol --</option>
                                <?php foreach ($roles as $rol): ?>
                                    <option value="<?php echo $rol['id']; ?>">
                                        <?php echo htmlspecialchars($rol['nombre']); ?>
                                        <?php if ($rol['descripcion']): ?>
                                            - <?php echo htmlspecialchars($rol['descripcion']); ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Selecciona "Sin Rol" para quitar el rol actual</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirModal(usuarioId, usuarioNombre, rolActual) {
            document.getElementById('usuario_id').value = usuarioId;
            document.getElementById('usuario_nombre').textContent = usuarioNombre;
            
            // Seleccionar el rol actual
            const selectRol = document.getElementById('rol_id');
            if (rolActual) {
                selectRol.value = rolActual;
            } else {
                selectRol.value = '';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('modalAsignarRol'));
            modal.show();
        }

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
