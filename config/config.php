<?php
require_once '../config/config.php';
// Configuración de la aplicación
define('APP_NAME', 'Sistema de Venta de Ganado');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/sistema_ganado');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'venta_ganado');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');

// Configuración de uploads
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Función para verificar autenticación
function verificarAutenticacion($tipoRequerido = null) {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . APP_URL . '../presentacion/pLogin.php');
        exit;
    }
    
    if ($tipoRequerido && $_SESSION['usuario_tipo'] != $tipoRequerido) {
        header('Location: ' . APP_URL . '../presentacion/index.php?error=permisos');
        exit;
    }
    
    return true;
}
function usuarioPuedeEliminarCuenta($usuario_id) {
// Aquí puedes agregar lógica adicional de verificación
// Por ejemplo, verificar que no sea el único administrador
    require_once 'datos/dGanado.php';
    require_once 'datos/dVenta.php';
    $dGanado = new dGanado();
    $dVenta = new dVenta();
        $ganado_usuario = $dGanado->obtenerPorUsuario($usuario_id);
        $ventas_pendientes = $dVenta->obtenerVentasPendientesPorUsuario($usuario_id);
    if (count($ganado_usuario) > 0 || count($ventas_pendientes) > 0) {
        return false;
    }
        return true;
}
// Configuración de APIs externas
define('GOOGLE_MAPS_API_KEY', 'TU_API_KEY_AQUI');
define('GOOGLE_MAPS_ENABLED', true);

// Puedes agregar también configuración para otros servicios
define('EMAIL_SERVICE_ENABLED', true);
define('SMS_SERVICE_ENABLED', false);

?>