<?php
/**
 * Utilidades de Validación
 * Proporciona funciones para validar diferentes tipos de datos
 */

// ============================================================================
// VALIDACIÓN DE EMAILS
// ============================================================================

/**
 * Valida si un email es válido
 * 
 * @param string $email Email a validar
 * @return bool True si es válido
 */
function esEmailValido($email) {
    $email = trim(strtolower($email));
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Obtiene versión limpia de un email
 * 
 * @param string $email Email a limpiar
 * @return string|null Email limpio o null si no es válido
 */
function limpiarEmail($email) {
    if (!esEmailValido($email)) {
        return null;
    }
    return trim(strtolower($email));
}

// ============================================================================
// VALIDACIÓN DE NÚMEROS
// ============================================================================

/**
 * Valida si es número entero
 * 
 * @param mixed $valor Valor a validar
 * @param int $minimo Valor mínimo (opcional)
 * @param int $maximo Valor máximo (opcional)
 * @return bool True si es válido
 */
function esNumeroEntero($valor, $minimo = null, $maximo = null) {
    if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
        return false;
    }
    
    $valor = (int)$valor;
    
    if ($minimo !== null && $valor < $minimo) {
        return false;
    }
    
    if ($maximo !== null && $valor > $maximo) {
        return false;
    }
    
    return true;
}

/**
 * Valida si es número flotante
 * 
 * @param mixed $valor Valor a validar
 * @param float $minimo Valor mínimo (opcional)
 * @param float $maximo Valor máximo (opcional)
 * @return bool True si es válido
 */
function esNumeroFlotante($valor, $minimo = null, $maximo = null) {
    if (filter_var($valor, FILTER_VALIDATE_FLOAT) === false) {
        return false;
    }
    
    $valor = (float)$valor;
    
    if ($minimo !== null && $valor < $minimo) {
        return false;
    }
    
    if ($maximo !== null && $valor > $maximo) {
        return false;
    }
    
    return true;
}

// ============================================================================
// VALIDACIÓN DE STRINGS
// ============================================================================

/**
 * Valida si un string tiene longitud válida
 * 
 * @param string $valor String a validar
 * @param int $minimo Longitud mínima
 * @param int $maximo Longitud máxima
 * @return bool True si es válido
 */
function esLongitudValida($valor, $minimo = 0, $maximo = PHP_INT_MAX) {
    $longitud = strlen(trim($valor));
    return $longitud >= $minimo && $longitud <= $maximo;
}

/**
 * Valida si es un nombre válido (solo letras, espacios y algunos caracteres)
 * 
 * @param string $nombre Nombre a validar
 * @param int $minimo Longitud mínima
 * @param int $maximo Longitud máxima
 * @return bool True si es válido
 */
function esNombreValido($nombre, $minimo = 3, $maximo = 100) {
    if (!esLongitudValida($nombre, $minimo, $maximo)) {
        return false;
    }
    
    // Solo permitir letras, números, espacios, guiones y apóstrofes
    return preg_match('/^[a-záéíóúñü0-9\s\-\.\']+$/i', $nombre) === 1;
}

/**
 * Valida si es un teléfono válido
 * 
 * @param string $telefono Teléfono a validar
 * @return bool True si es válido
 */
function esTelefonoValido($telefono) {
    $telefono = trim($telefono);
    
    if (empty($telefono)) {
        return false;
    }
    
    // Permitir números, espacios, guiones, paréntesis y +
    if (!preg_match('/^[0-9\s\-\+\(\)]+$/', $telefono)) {
        return false;
    }
    
    // Debe tener al menos 7 dígitos
    $solo_numeros = preg_replace('/[^0-9]/', '', $telefono);
    return strlen($solo_numeros) >= 7 && strlen($solo_numeros) <= 15;
}

/**
 * Valida si es una URL válida
 * 
 * @param string $url URL a validar
 * @return bool True si es válida
 */
function esUrlValida($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Valida si es una contraseña fuerte
 * Requisitos: al menos 8 caracteres, 1 mayúscula, 1 minúscula, 1 número, 1 carácter especial
 * 
 * @param string $password Contraseña a validar
 * @return array Array con 'valida' y opcionalmente 'errores'
 */
function esContraseñaFuerte($password) {
    $errores = [];
    
    if (strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errores[] = 'Debe contener al menos una letra mayúscula';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errores[] = 'Debe contener al menos una letra minúscula';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errores[] = 'Debe contener al menos un número';
    }
    
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]/', $password)) {
        $errores[] = 'Debe contener al menos un carácter especial (!@#$%^&*)';
    }
    
    return [
        'valida' => empty($errores),
        'errores' => $errores
    ];
}

// ============================================================================
// VALIDACIÓN DE IP
// ============================================================================

/**
 * Valida si es una dirección IP válida
 * 
 * @param string $ip IP a validar
 * @param int $version Versión (4 o 6, 0 para cualquiera)
 * @return bool True si es válida
 */
function esIPValida($ip, $version = 0) {
    $flags = 0;
    
    if ($version == 4) {
        $flags = FILTER_FLAG_IPV4;
    } elseif ($version == 6) {
        $flags = FILTER_FLAG_IPV6;
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP, $flags) !== false;
}

/**
 * Obtiene la dirección IP del cliente
 * 
 * @return string Dirección IP del cliente
 */
function obtenerIPCliente() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    // Validar y retornar
    return esIPValida($ip) ? $ip : '0.0.0.0';
}

// ============================================================================
// VALIDACIÓN DE FECHAS
// ============================================================================

/**
 * Valida si es una fecha válida
 * 
 * @param string $fecha Fecha a validar (formato Y-m-d)
 * @return bool True si es válida
 */
function esFechaValida($fecha) {
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    return $d && $d->format('Y-m-d') === $fecha;
}

/**
 * Valida si es una fecha y hora válida
 * 
 * @param string $fecha_hora Fecha y hora a validar (formato Y-m-d H:i:s)
 * @return bool True si es válida
 */
function esFechaHoraValida($fecha_hora) {
    $d = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hora);
    return $d && $d->format('Y-m-d H:i:s') === $fecha_hora;
}

/**
 * Valida si una fecha está entre dos fechas
 * 
 * @param string $fecha Fecha a validar (Y-m-d)
 * @param string $fecha_inicio Fecha inicial (Y-m-d)
 * @param string $fecha_fin Fecha final (Y-m-d)
 * @return bool True si está en el rango
 */
function estaEnRangoFechas($fecha, $fecha_inicio, $fecha_fin) {
    return $fecha >= $fecha_inicio && $fecha <= $fecha_fin;
}

/**
 * Calcula la edad a partir de una fecha de nacimiento
 * 
 * @param string $fecha_nacimiento Fecha de nacimiento (Y-m-d)
 * @return int Edad en años
 */
function calcularEdad($fecha_nacimiento) {
    $fecha = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
    if (!$fecha) {
        return 0;
    }
    
    $hoy = new DateTime();
    return $hoy->diff($fecha)->y;
}

// ============================================================================
// VALIDACIÓN DE ARCHIVOS
// ============================================================================

/**
 * Valida si la extensión de archivo es permitida
 * 
 * @param string $nombre_archivo Nombre del archivo
 * @param array $extensiones_permitidas Array de extensiones permitidas
 * @return bool True si la extensión es válida
 */
function esExtensionValida($nombre_archivo, $extensiones_permitidas) {
    $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    return in_array($extension, $extensiones_permitidas);
}

/**
 * Obtiene el tipo MIME de un archivo
 * 
 * @param string $ruta_archivo Ruta al archivo
 * @return string Tipo MIME
 */
function obtenerTipoMIME($ruta_archivo) {
    if (function_exists('mime_content_type')) {
        return mime_content_type($ruta_archivo);
    }
    
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $ruta_archivo);
        finfo_close($finfo);
        return $mime;
    }
    
    return 'application/octet-stream';
}

/**
 * Valida el tamaño de un archivo
 * 
 * @param int $tamaño_bytes Tamaño en bytes
 * @param int $maximo_bytes Tamaño máximo permitido en bytes
 * @return bool True si está dentro del límite
 */
function esTamañoValido($tamaño_bytes, $maximo_bytes) {
    return $tamaño_bytes > 0 && $tamaño_bytes <= $maximo_bytes;
}

/**
 * Valida si es una imagen válida por tipo MIME
 * 
 * @param string $ruta_archivo Ruta al archivo
 * @return bool True si es una imagen válida
 */
function esImagenValida($ruta_archivo) {
    $tipos_validos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $mime = obtenerTipoMIME($ruta_archivo);
    return in_array($mime, $tipos_validos);
}

// ============================================================================
// FUNCIONES DE SANITIZACIÓN VINCULADAS
// ============================================================================

/**
 * Sanitiza un string general
 * 
 * @param string $valor String a sanitizar
 * @return string String sanitizado
 */
function sanitizarString($valor) {
    return htmlspecialchars(strip_tags(trim($valor)), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitiza una entrada numérica
 * 
 * @param mixed $valor Valor a sanitizar
 * @return int|float Valor numérico sanitizado
 */
function sanitizarNumero($valor) {
    if (strpos($valor, '.') !== false) {
        return (float)filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    return (int)filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Sanitiza un email
 * 
 * @param string $email Email a sanitizar
 * @return string Email sanitizado
 */
function sanitizarEmail($email) {
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}

/**
 * Sanitiza una URL
 * 
 * @param string $url URL a sanitizar
 * @return string URL sanitizada
 */
function sanitizarUrl($url) {
    return filter_var($url, FILTER_SANITIZE_URL);
}
