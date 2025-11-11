<?php
// Configuración de la aplicación
define('APP_NAME', 'Sistema de Venta de Ganado');
define('APP_VERSION', '1.0.0');
// Ajusta el APP_URL según tu entorno (XAMPP normalmente usa http://localhost/<carpeta>)
define('APP_URL', 'http://localhost:8080//sistema_ganado_septiembre');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'venta_ganado');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8');

// Configuración de uploads
define('UPLOAD_DIR', __DIR__ . '/../presentacion/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Cerrar sesión si está vacía
if (empty($_SESSION)) {
    session_destroy();
}
if (!empty($_SESSION)) {
    // Regenerar ID de sesión para mayor seguridad
    session_regenerate_id(true);
}
if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = 1800; // 30 minutos
    if (time() - $_SESSION['ultimo_acceso'] > $inactividad) {
        // Tiempo de inactividad excedido, destruir sesión
        session_unset();
        session_destroy();
    }
}
if (isset($_SESSION['usuario_id'])) {
    $_SESSION['ultimo_acceso'] = time(); // Actualizar tiempo de último acceso
}

// Función para verificar autenticación
function verificarAutenticacion($tipoRequerido = null) {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . APP_URL . '/presentacion/pLogin.php');
        exit;
    }

    if ($tipoRequerido && (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] != $tipoRequerido)) {
        header('Location: ' . APP_URL . '/presentacion/index.php?error=permisos');
        exit;
    }

    return true;
}
function obtenerEstadisticasGenerales() {
    require_once __DIR__ . '/../negocio/nEstadisticas.php';
    $nEstadisticas = new nEstadisticas();
    return $nEstadisticas->obtenerEstadisticasGenerales();
}

function usuarioPuedeEliminarCuenta($usuario_id) {
    // Evitar dependencias con rutas relativas imprecisas
    require_once __DIR__ . '/../datos/dGanado.php';
    require_once __DIR__ . '/../datos/dVenta.php';
    $dGanado = new dGanado();
    $dVenta = new dVenta();
    $ganado_usuario = $dGanado->obtenerPorUsuario($usuario_id);
    $ventas_pendientes = $dVenta->obtenerVentasPendientesPorUsuario($usuario_id);
    if (count($ganado_usuario) > 0 || count($ventas_pendientes) > 0) {
        return false;
    }
    return true;
}
// Configuración de correo electrónico (placeholder)
define('EMAIL_FROM_ADDRESS', 'noreply@sistemaganado.com');


// Configuración de APIs externas (placeholder)
define('GOOGLE_MAPS_API_KEY', 'TU_API_KEY_AQUI');
define('GOOGLE_MAPS_ENABLED', true);

// Flags de servicios
define('EMAIL_SERVICE_ENABLED', true);
define('SMS_SERVICE_ENABLED', false);
define('PAYMENT_GATEWAY_ENABLED', false);
?>