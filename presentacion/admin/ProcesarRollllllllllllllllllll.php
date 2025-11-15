<?php
// Archivo: presentacion/admin/ProcesarRol.php
session_start();

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../pLogin.php?error=' . urlencode('Debe iniciar sesión'));
    exit;
}

// Verificar que sea administrador (ajusta según tu sistema)
if ($_SESSION['tipo'] != 'admin') {
    header('Location: ListarRoles.php?error=' . urlencode('Acceso denegado'));
    exit;
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ListarRoles.php?error=' . urlencode('Método no permitido'));
    exit;
}

// Incluir archivos necesarios
require_once __DIR__ . '/../../negocio/nListarRol.php';

// Obtener la acción
$accion = $_POST['accion'] ?? '';

try {
    $nListarRol = new nListarRol();
    
    switch ($accion) {
        case 'crear':
            // CREAR NUEVO ROL
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? null;
            
            $resultado = $nListarRol->crear($nombre, $descripcion);
            
            if ($resultado['exito']) {
                header('Location: ListarRoles.php?exito=' . urlencode($resultado['mensaje']));
            } else {
                header('Location: ListarRoles.php?error=' . urlencode($resultado['mensaje']));
            }
            break;

        case 'editar':
            // EDITAR ROL EXISTENTE
            $id = $_POST['id'] ?? 0;
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? null;
            
            $resultado = $nListarRol->actualizar($id, $nombre, $descripcion);
            
            if ($resultado['exito']) {
                header('Location: ListarRoles.php?exito=' . urlencode($resultado['mensaje']));
            } else {
                header('Location: ListarRoles.php?error=' . urlencode($resultado['mensaje']));
            }
            break;

        case 'eliminar':
            // ELIMINAR ROL
            $id = $_POST['id'] ?? 0;
            
            $resultado = $nListarRol->eliminar($id);
            
            if ($resultado['exito']) {
                header('Location: ListarRoles.php?exito=' . urlencode($resultado['mensaje']));
            } else {
                header('Location: ListarRoles.php?error=' . urlencode($resultado['mensaje']));
            }
            break;

        default:
            header('Location: ListarRoles.php?error=' . urlencode('Acción no válida'));
            break;
    }

} catch (Exception $e) {
    // Manejo de errores
    header('Location: ListarRoles.php?error=' . urlencode('Error: ' . $e->getMessage()));
    exit;
}
?>