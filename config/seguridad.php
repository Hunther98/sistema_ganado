<?php
/**
 * Sistema de Seguridad y Control de Roles
 * Proporciona funciones para autenticación, autorización y control de acceso basado en roles
 */

require_once __DIR__ . '/config.php';

// ============================================================================
// CONSTANTES DE ROLES
// ============================================================================
define('ROLE_ADMIN', 'admin');
define('ROLE_VENDEDOR', 'vendedor');
define('ROLE_COMPRADOR', 'comprador');

// Todos los roles válidos del sistema
define('ROLES_VALIDOS', [ROLE_ADMIN, ROLE_VENDEDOR, ROLE_COMPRADOR]);

// ============================================================================
// CONSTANTES DE PERMISOS
// ============================================================================
define('PERMISO_ADMIN_PANEL', 'admin_panel');
define('PERMISO_ADMIN_USUARIOS', 'admin_usuarios');
define('PERMISO_ADMIN_REPORTES', 'admin_reportes');
define('PERMISO_PUBLICAR_GANADO', 'publicar_ganado');
define('PERMISO_VER_COMPRAS', 'ver_compras');
define('PERMISO_VER_VENTAS', 'ver_ventas');
define('PERMISO_PERFIL_EDITAR', 'perfil_editar');
define('PERMISO_VER_CATALOGO', 'ver_catalogo');

// ============================================================================
// MATRIZ DE PERMISOS POR ROL
// ============================================================================
$PERMISOS_POR_ROL = [
    ROLE_ADMIN => [
        PERMISO_ADMIN_PANEL,
        PERMISO_ADMIN_USUARIOS,
        PERMISO_ADMIN_REPORTES,
        PERMISO_VER_CATALOGO,
        PERMISO_PERFIL_EDITAR,
    ],
    ROLE_VENDEDOR => [
        PERMISO_PUBLICAR_GANADO,
        PERMISO_VER_VENTAS,
        PERMISO_VER_CATALOGO,
        PERMISO_PERFIL_EDITAR,
    ],
    ROLE_COMPRADOR => [
        PERMISO_VER_COMPRAS,
        PERMISO_VER_CATALOGO,
        PERMISO_PERFIL_EDITAR,
    ],
];

// ============================================================================
// FUNCIONES DE AUTENTICACIÓN
// ============================================================================

/**
 * Verifica si el usuario está autenticado (tiene sesión activa)
 * 
 * @return bool True si está autenticado, false en caso contrario
 */
function estaAutenticado() {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

/**
 * Verifica si el usuario está autenticado, si no redirige a login
 * 
 * @param string $destino Página de destino después del login (por defecto: anterior)
 * @return bool True si está autenticado
 */
function requerirAutenticacion($destino = null) {
    if (!estaAutenticado()) {
        $url_retorno = $destino ?? $_SERVER['REQUEST_URI'];
        header('Location: ' . APP_URL . '/presentacion/pLogin.php?retorno=' . urlencode($url_retorno));
        exit;
    }
    return true;
}

/**
 * Obtiene el rol actual del usuario
 * 
 * @return string|null Rol del usuario o null si no está autenticado
 */
function obtenerRolActual() {
    return $_SESSION['usuario_tipo'] ?? null;
}

/**
 * Obtiene el ID del usuario actual
 * 
 * @return int|null ID del usuario o null si no está autenticado
 */
function obtenerUsuarioId() {
    return $_SESSION['usuario_id'] ?? null;
}

/**
 * Obtiene el nombre del usuario actual
 * 
 * @return string|null Nombre del usuario o null si no está autenticado
 */
function obtenerUsuarioNombre() {
    return $_SESSION['usuario_nombre'] ?? null;
}

// ============================================================================
// FUNCIONES DE AUTORIZACIÓN
// ============================================================================

/**
 * Verifica si el usuario actual tiene un rol específico
 * 
 * @param string $rol Rol a verificar (ROLE_ADMIN, ROLE_VENDEDOR, ROLE_COMPRADOR)
 * @return bool True si el usuario tiene el rol especificado
 */
function tieneRol($rol) {
    if (!estaAutenticado()) {
        return false;
    }
    return obtenerRolActual() === $rol;
}

/**
 * Verifica si el usuario actual tiene uno de varios roles
 * 
 * @param array $roles Array de roles a verificar
 * @return bool True si el usuario tiene alguno de los roles especificados
 */
function tieneAlgunoDeEstosRoles($roles) {
    if (!estaAutenticado()) {
        return false;
    }
    return in_array(obtenerRolActual(), $roles);
}

/**
 * Verifica si el usuario actual tiene un permiso específico
 * 
 * @param string $permiso Permiso a verificar (usar constantes PERMISO_*)
 * @return bool True si el usuario tiene el permiso
 */
function tienePermiso($permiso) {
    global $PERMISOS_POR_ROL;
    
    if (!estaAutenticado()) {
        return false;
    }
    
    $rol = obtenerRolActual();
    
    if (!isset($PERMISOS_POR_ROL[$rol])) {
        return false;
    }
    
    return in_array($permiso, $PERMISOS_POR_ROL[$rol]);
}

/**
 * Verifica si el usuario tiene uno de varios permisos
 * 
 * @param array $permisos Array de permisos a verificar
 * @return bool True si el usuario tiene alguno de los permisos
 */
function tieneAlgunoDeEstosPermisos($permisos) {
    foreach ($permisos as $permiso) {
        if (tienePermiso($permiso)) {
            return true;
        }
    }
    return false;
}

// ============================================================================
// FUNCIONES DE CONTROL DE ACCESO
// ============================================================================

/**
 * Requiere que el usuario tenga un rol específico
 * Si no lo tiene, redirige a una página de error
 * 
 * @param string $rol Rol requerido
 * @param string $url_error URL a la que redirigir si no tiene permiso
 */
function requerirRol($rol, $url_error = null) {
    requerirAutenticacion();
    
    if (!tieneRol($rol)) {
        $url_error = $url_error ?? (APP_URL . '/presentacion/index.php?error=permisos_insuficientes');
        header('Location: ' . $url_error);
        exit;
    }
}

/**
 * Requiere que el usuario tenga uno de varios roles
 * 
 * @param array $roles Array de roles permitidos
 * @param string $url_error URL a la que redirigir si no tiene permiso
 */
function requerirAlgunoDeEstosRoles($roles, $url_error = null) {
    requerirAutenticacion();
    
    if (!tieneAlgunoDeEstosRoles($roles)) {
        $url_error = $url_error ?? (APP_URL . '/presentacion/index.php?error=permisos_insuficientes');
        header('Location: ' . $url_error);
        exit;
    }
}

/**
 * Requiere que el usuario tenga un permiso específico
 * 
 * @param string $permiso Permiso requerido
 * @param string $url_error URL a la que redirigir si no tiene permiso
 */
function requerirPermiso($permiso, $url_error = null) {
    requerirAutenticacion();
    
    if (!tienePermiso($permiso)) {
        $url_error = $url_error ?? (APP_URL . '/presentacion/index.php?error=permisos_insuficientes');
        header('Location: ' . $url_error);
        exit;
    }
}

/**
 * Requiere que el usuario tenga uno de varios permisos
 * 
 * @param array $permisos Array de permisos permitidos
 * @param string $url_error URL a la que redirigir si no tiene permiso
 */
function requerirAlgunoDeEstosPermisos($permisos, $url_error = null) {
    requerirAutenticacion();
    
    if (!tieneAlgunoDeEstosPermisos($permisos)) {
        $url_error = $url_error ?? (APP_URL . '/presentacion/index.php?error=permisos_insuficientes');
        header('Location: ' . $url_error);
        exit;
    }
}

// ============================================================================
// FUNCIONES DE UTILIDAD
// ============================================================================

/**
 * Obtiene la descripción legible de un rol
 * 
 * @param string $rol Rol a describir
 * @return string Descripción del rol
 */
function obtenerDescripcionRol($rol) {
    $descripciones = [
        ROLE_ADMIN => 'Administrador',
        ROLE_VENDEDOR => 'Vendedor',
        ROLE_COMPRADOR => 'Comprador',
    ];
    
    return $descripciones[$rol] ?? 'Desconocido';
}

/**
 * Obtiene el icono Font Awesome para un rol
 * 
 * @param string $rol Rol
 * @return string Icono HTML de Font Awesome
 */
function obtenerIconoRol($rol) {
    $iconos = [
        ROLE_ADMIN => '<i class="fas fa-shield-alt"></i>',
        ROLE_VENDEDOR => '<i class="fas fa-store"></i>',
        ROLE_COMPRADOR => '<i class="fas fa-shopping-cart"></i>',
    ];
    
    return $iconos[$rol] ?? '<i class="fas fa-user"></i>';
}

/**
 * Valida que un rol sea válido
 * 
 * @param string $rol Rol a validar
 * @return bool True si el rol es válido
 */
function esRolValido($rol) {
    return in_array($rol, ROLES_VALIDOS);
}

/**
 * Obtiene la URL de destino después del login según el rol
 * 
 * @param string $rol Rol del usuario
 * @return string URL de destino
 */
function obtenerUrlDestinoPorRol($rol) {
    switch ($rol) {
        case ROLE_ADMIN:
            return APP_URL . '/presentacion/admin/admin.php';
        case ROLE_VENDEDOR:
            return APP_URL . '/presentacion/admin/listar_ganado.php';
        case ROLE_COMPRADOR:
            return APP_URL . '/presentacion/catalogo.php';
        default:
            return APP_URL . '/presentacion/index.php';
    }
}

// ============================================================================
// FUNCIONES DE AUDITORÍA
// ============================================================================

/**
 * Registra una acción de usuario para auditoría
 * Nota: Requiere tabla 'auditorias' en la BD
 * 
 * @param string $accion Acción realizada
 * @param string $detalles Detalles adicionales de la acción
 * @param string $tabla Tabla afectada (opcional)
 * @param int $id_registro ID del registro afectado (opcional)
 */
function registrarAuditoria($accion, $detalles = '', $tabla = '', $id_registro = 0) {
    if (!estaAutenticado()) {
        return false;
    }
    
    try {
        $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conexion->connect_error) {
            error_log('Error de conexión en auditoría: ' . $conexion->connect_error);
            return false;
        }
        
        // Verificar que la tabla de auditorías existe
        $resultado = $conexion->query("SHOW TABLES LIKE 'auditorias'");
        
        if ($resultado->num_rows === 0) {
            $conexion->close();
            return false;
        }
        
        $usuario_id = obtenerUsuarioId();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'DESCONOCIDA';
        $navegador = $_SERVER['HTTP_USER_AGENT'] ?? 'DESCONOCIDO';
        $fecha = date('Y-m-d H:i:s');
        
        $stmt = $conexion->prepare(
            "INSERT INTO auditorias (usuario_id, accion, detalles, tabla, id_registro, ip, navegador, fecha) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param(
            'isssisss',
            $usuario_id,
            $accion,
            $detalles,
            $tabla,
            $id_registro,
            $ip,
            $navegador,
            $fecha
        );
        
        $resultado = $stmt->execute();
        
        $stmt->close();
        $conexion->close();
        
        return $resultado;
    } catch (Exception $e) {
        error_log('Error al registrar auditoría: ' . $e->getMessage());
        return false;
    }
}

// ============================================================================
// VALIDACIONES DE SEGURIDAD
// ============================================================================

/**
 * Valida que el CSRF token sea válido
 * Genera un token si no existe
 * 
 * @return string Token CSRF
 */
function obtenerCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica que el CSRF token enviado sea válido
 * 
 * @param string $token Token a verificar (de $_POST o $_GET)
 * @return bool True si el token es válido
 */
function verificarCSRFToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Genera HTML de un campo CSRF oculto para formularios
 * 
 * @return string HTML del campo oculto
 */
function generarCampoCSRF() {
    $token = obtenerCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}
