<?php
/**
 * Sistema de Redirecciones Centralizado
 * Proporciona funciones para redirigir con mensajes y parámetros seguros
 */

require_once __DIR__ . '/config.php';

// ============================================================================
// REDIRECCIONES BÁSICAS
// ============================================================================

/**
 * Redirige a una URL con mensaje de éxito
 * 
 * @param string $url URL de destino (relativa o absoluta)
 * @param string $mensaje Mensaje a mostrar (opcional)
 * @param string $tipo Tipo de mensaje: 'exito', 'info', 'advertencia', 'error'
 */
function redirigirExito($url, $mensaje = '', $tipo = 'exito') {
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo_mensaje'] = $tipo;
    header('Location: ' . $url);
    exit;
}

/**
 * Redirige a una URL con mensaje de error
 * 
 * @param string $url URL de destino
 * @param string $error Descripción del error
 */
function redirigirError($url, $error) {
    $_SESSION['error'] = $error;
    header('Location: ' . $url);
    exit;
}

/**
 * Redirige a URL anterior con mensaje
 * 
 * @param string $mensaje Mensaje a mostrar
 * @param string $tipo Tipo de mensaje
 */
function redirigirAnterior($mensaje = '', $tipo = 'info') {
    $url_anterior = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/presentacion/index.php';
    
    $_SESSION['mensaje'] = $mensaje;
    $_SESSION['tipo_mensaje'] = $tipo;
    
    header('Location: ' . $url_anterior);
    exit;
}

// ============================================================================
// REDIRECCIONES SEGÚN ROL
// ============================================================================

/**
 * Redirige al usuario a su página de inicio según su rol
 * 
 * @param string $rol Rol del usuario (opcional, usa $_SESSION si no se especifica)
 */
function redirigirPorRol($rol = null) {
    require_once __DIR__ . '/seguridad.php';
    
    $rol = $rol ?? obtenerRolActual();
    
    switch ($rol) {
        case ROLE_ADMIN:
            header('Location: ' . APP_URL . '/presentacion/admin/admin.php');
            exit;
        case ROLE_VENDEDOR:
            header('Location: ' . APP_URL . '/presentacion/admin/listar_ganado.php');
            exit;
        case ROLE_COMPRADOR:
            header('Location: ' . APP_URL . '/presentacion/catalogo.php');
            exit;
        default:
            header('Location: ' . APP_URL . '/presentacion/index.php');
            exit;
    }
}

/**
 * Redirige a login manteniendo la URL actual para retorno
 * 
 * @param string $destino URL a la que redirigir después del login
 */
function redirigirALogin($destino = null) {
    $url_retorno = $destino ?? $_SERVER['REQUEST_URI'];
    header('Location: ' . APP_URL . '/presentacion/pLogin.php?retorno=' . urlencode($url_retorno));
    exit;
}

// ============================================================================
// VALIDACIÓN DE REDIRECCIONES SEGURAS
// ============================================================================

/**
 * Valida que una URL sea segura para redirigir
 * Evita redirecciones a sitios externos malintencionados
 * 
 * @param string $url URL a validar
 * @param bool $permitir_externo Si true, permite URLs externas
 * @return bool True si la URL es segura
 */
function esUrlSegura($url, $permitir_externo = false) {
    if (empty($url)) {
        return false;
    }
    
    // Validar que no tenga caracteres peligrosos
    if (preg_match('/[\r\n\0]/', $url)) {
        return false;
    }
    
    // Si es URL relativa, siempre es segura
    if (strpos($url, 'http') !== 0) {
        return true;
    }
    
    // Si es URL absoluta, validar que sea del mismo dominio
    if (!$permitir_externo) {
        $url_parsed = parse_url($url);
        $app_parsed = parse_url(APP_URL);
        
        return $url_parsed['host'] === $app_parsed['host'];
    }
    
    return true;
}

/**
 * Redirige a una URL validada como segura
 * 
 * @param string $url URL de destino
 * @param string $url_defecto URL por defecto si la proporcionada no es segura
 */
function redirigirSeguro($url, $url_defecto = null) {
    $url_defecto = $url_defecto ?? APP_URL . '/presentacion/index.php';
    
    if (!esUrlSegura($url)) {
        header('Location: ' . $url_defecto);
        exit;
    }
    
    // Si es URL relativa, prepender APP_URL
    if (strpos($url, 'http') !== 0 && strpos($url, '/') === 0) {
        $url = APP_URL . $url;
    } elseif (strpos($url, 'http') !== 0) {
        $url = APP_URL . '/presentacion/' . $url;
    }
    
    header('Location: ' . $url);
    exit;
}

// ============================================================================
// PARÁMETROS DE URL SEGUROS
// ============================================================================

/**
 * Obtiene un parámetro GET validado
 * 
 * @param string $clave Clave del parámetro
 * @param string $tipo Tipo esperado: 'int', 'string', 'email', 'url'
 * @param mixed $defecto Valor por defecto
 * @return mixed Valor validado o valor por defecto
 */
function obtenerParametroGET($clave, $tipo = 'string', $defecto = null) {
    if (!isset($_GET[$clave])) {
        return $defecto;
    }
    
    $valor = $_GET[$clave];
    
    switch ($tipo) {
        case 'int':
            return filter_var($valor, FILTER_VALIDATE_INT) !== false ? (int)$valor : $defecto;
        case 'email':
            return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false ? $valor : $defecto;
        case 'url':
            return filter_var($valor, FILTER_VALIDATE_URL) !== false ? $valor : $defecto;
        case 'string':
        default:
            return htmlspecialchars(strip_tags($valor));
    }
}

/**
 * Obtiene un parámetro POST validado
 * 
 * @param string $clave Clave del parámetro
 * @param string $tipo Tipo esperado: 'int', 'string', 'email', 'url'
 * @param mixed $defecto Valor por defecto
 * @return mixed Valor validado o valor por defecto
 */
function obtenerParametroPOST($clave, $tipo = 'string', $defecto = null) {
    if (!isset($_POST[$clave])) {
        return $defecto;
    }
    
    $valor = $_POST[$clave];
    
    switch ($tipo) {
        case 'int':
            return filter_var($valor, FILTER_VALIDATE_INT) !== false ? (int)$valor : $defecto;
        case 'email':
            return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false ? $valor : $defecto;
        case 'url':
            return filter_var($valor, FILTER_VALIDATE_URL) !== false ? $valor : $defecto;
        case 'string':
        default:
            return htmlspecialchars(strip_tags($valor));
    }
}

// ============================================================================
// HELPERS DE MENSAJES
// ============================================================================

/**
 * Obtiene y limpia el mensaje de sesión actual
 * 
 * @return array|null Array con 'mensaje' y 'tipo', o null si no hay
 */
function obtenerMensajeSesion() {
    if (!isset($_SESSION['mensaje'])) {
        return null;
    }
    
    $mensaje = [
        'texto' => $_SESSION['mensaje'],
        'tipo' => $_SESSION['tipo_mensaje'] ?? 'info'
    ];
    
    unset($_SESSION['mensaje']);
    unset($_SESSION['tipo_mensaje']);
    
    return $mensaje;
}

/**
 * Obtiene y limpia el error de sesión actual
 * 
 * @return string|null Error o null si no hay
 */
function obtenerErrorSesion() {
    if (!isset($_SESSION['error'])) {
        return null;
    }
    
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
    
    return $error;
}

/**
 * Genera HTML para mostrar un mensaje de sesión
 * 
 * @return string HTML de alerta Bootstrap o string vacío
 */
function mostrarMensajeSesion() {
    $mensaje = obtenerMensajeSesion();
    
    if ($mensaje === null) {
        return '';
    }
    
    $tipo_bootstrap = [
        'exito' => 'success',
        'info' => 'info',
        'advertencia' => 'warning',
        'error' => 'danger'
    ][$mensaje['tipo']] ?? 'info';
    
    $iconos = [
        'success' => 'fa-check-circle',
        'info' => 'fa-info-circle',
        'warning' => 'fa-exclamation-circle',
        'danger' => 'fa-times-circle'
    ];
    
    $icono = $iconos[$tipo_bootstrap] ?? 'fa-info-circle';
    
    return "
    <div class='alert alert-{$tipo_bootstrap} alert-dismissible fade show' role='alert'>
        <i class='fas {$icono}'></i> {$mensaje['texto']}
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
    ";
}

/**
 * Genera HTML para mostrar un error de sesión
 * 
 * @return string HTML de alerta Bootstrap o string vacío
 */
function mostrarErrorSesion() {
    $error = obtenerErrorSesion();
    
    if ($error === null) {
        return '';
    }
    
    return "
    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
        <i class='fas fa-exclamation-circle'></i> <strong>Error:</strong> {$error}
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
    ";
}
