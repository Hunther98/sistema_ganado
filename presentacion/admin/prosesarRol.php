<?php
require_once __DIR__ . '../../config/config.php';

verificarAutenticacion("admin");
// session_start();

// // Verificar si el usuario está autenticado y es administrador
// if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
//     header('Location: ../login.php');
//     exit();
// }

require_once __DIR__ . '/../../negocio/nListarRol.php';

// Verificar que se recibió una acción
if (!isset($_POST['accion'])) {
    $_SESSION['mensaje'] = 'Acción no especificada';
    $_SESSION['tipo_mensaje'] = 'danger';
    header('Location: aListarRol.php');
    exit();
}

$nListarRol = new nListarRol();
$accion = $_POST['accion'];

switch ($accion) {
    case 'crear':
        procesarCrear($nListarRol);
        break;
        
    case 'actualizar':
        procesarActualizar($nListarRol);
        break;
        
    case 'eliminar':
        procesarEliminar($nListarRol);
        break;
        
    default:
        $_SESSION['mensaje'] = 'Acción no válida';
        $_SESSION['tipo_mensaje'] = 'danger';
        header('Location: aListarRol.php');
        exit();
}

/**
 * Procesar la creación de un nuevo rol
 */
function procesarCrear($nListarRol) {
    try {
        // Validar que se recibieron los datos necesarios
        if (empty($_POST['nombre'])) {
            $_SESSION['mensaje'] = 'El nombre del rol es obligatorio';
            $_SESSION['tipo_mensaje'] = 'danger';
            header('Location: aListarRol.php');
            exit();
        }

        $nombre = trim($_POST['nombre']);
        $descripcion = !empty($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        // Crear el rol
        $resultado = $nListarRol->crear($nombre, $descripcion);

        if ($resultado['exito']) {
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo_mensaje'] = 'danger';
        }

    } catch (Exception $e) {
        $_SESSION['mensaje'] = 'Error al crear el rol: ' . $e->getMessage();
        $_SESSION['tipo_mensaje'] = 'danger';
        error_log('Error en procesarCrear: ' . $e->getMessage());
    }

    header('Location: aListarRol.php');
    exit();
}

/**
 * Procesar la actualización de un rol
 */
function procesarActualizar($nListarRol) {
    try {
        // Validar que se recibieron los datos necesarios
        if (empty($_POST['id']) || empty($_POST['nombre'])) {
            $_SESSION['mensaje'] = 'Datos incompletos para actualizar el rol';
            $_SESSION['tipo_mensaje'] = 'danger';
            header('Location: aListarRol.php');
            exit();
        }

        $id = intval($_POST['id']);
        $nombre = trim($_POST['nombre']);
        $descripcion = !empty($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

        // Actualizar el rol
        $resultado = $nListarRol->actualizar($id, $nombre, $descripcion);

        if ($resultado['exito']) {
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo_mensaje'] = 'danger';
        }

    } catch (Exception $e) {
        $_SESSION['mensaje'] = 'Error al actualizar el rol: ' . $e->getMessage();
        $_SESSION['tipo_mensaje'] = 'danger';
        error_log('Error en procesarActualizar: ' . $e->getMessage());
    }

    header('Location: aListarRol.php');
    exit();
}

/**
 * Procesar la eliminación de un rol
 */
function procesarEliminar($nListarRol) {
    try {
        // Validar que se recibió el ID
        if (empty($_POST['id'])) {
            $_SESSION['mensaje'] = 'ID de rol no especificado';
            $_SESSION['tipo_mensaje'] = 'danger';
            header('Location: aListarRol.php');
            exit();
        }

        $id = intval($_POST['id']);

        // Verificar que el rol existe antes de intentar eliminarlo
        $rol = $nListarRol->obtenerPorId($id);
        if (!$rol) {
            $_SESSION['mensaje'] = 'El rol especificado no existe';
            $_SESSION['tipo_mensaje'] = 'danger';
            header('Location: aListarRol.php');
            exit();
        }

        // Eliminar el rol
        $resultado = $nListarRol->eliminar($id);

        if ($resultado['exito']) {
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = $resultado['mensaje'];
            $_SESSION['tipo_mensaje'] = 'warning';
        }

    } catch (Exception $e) {
        $_SESSION['mensaje'] = 'Error al eliminar el rol: ' . $e->getMessage();
        $_SESSION['tipo_mensaje'] = 'danger';
        error_log('Error en procesarEliminar: ' . $e->getMessage());
    }

    header('Location: aListarRol.php');
    exit();
}

// Si se llega aquí directamente sin POST, redirigir
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: aListarRol.php');
    exit();
}
?>