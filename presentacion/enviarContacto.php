<?php
/**
 * Procesador del Formulario de Contacto
 * Valida, sanitiza y almacena mensajes de contacto en la base de datos
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/seguridad.php';
require_once __DIR__ . '/../datos/dContacto.php';

// Inicializar variables de error
$errores = [];

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contacto.php?error=formulario_invalido');
    exit;
}

// Validar CSRF token
if (!verificarCSRFToken($_POST['csrf_token'] ?? '')) {
    header('Location: contacto.php?error=formulario_invalido');
    exit;
}

// ============================================================================
// FUNCIONES DE VALIDACIÓN
// ============================================================================

function validarNombre($nombre) {
    $nombre = trim($nombre);
    
    if (empty($nombre)) {
        return ['valido' => false, 'error' => 'El nombre es requerido'];
    }
    
    if (strlen($nombre) < 3) {
        return ['valido' => false, 'error' => 'El nombre debe tener al menos 3 caracteres'];
    }
    
    if (strlen($nombre) > 100) {
        return ['valido' => false, 'error' => 'El nombre no debe exceder 100 caracteres'];
    }
    
    // Validar que solo contiene letras, espacios y algunos caracteres especiales permitidos
    if (!preg_match('/^[a-záéíóúñü\s\-\.\']+$/i', $nombre)) {
        return ['valido' => false, 'error' => 'El nombre contiene caracteres no permitidos'];
    }
    
    return ['valido' => true, 'valor' => htmlspecialchars(strip_tags($nombre))];
}

function validarEmail($email) {
    $email = trim(strtolower($email));
    
    if (empty($email)) {
        return ['valido' => false, 'error' => 'El email es requerido'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['valido' => false, 'error' => 'El email no es válido'];
    }
    
    return ['valido' => true, 'valor' => htmlspecialchars($email)];
}

function validarTelefono($telefono) {
    $telefono = trim($telefono);
    
    // Es opcional, pero si se proporciona debe ser válido
    if (empty($telefono)) {
        return ['valido' => true, 'valor' => ''];
    }
    
    if (strlen($telefono) > 20) {
        return ['valido' => false, 'error' => 'El teléfono no debe exceder 20 caracteres'];
    }
    
    // Solo permitir números, espacios, guiones, paréntesis y + 
    if (!preg_match('/^[0-9\s\-\+\(\)]+$/', $telefono)) {
        return ['valido' => false, 'error' => 'El teléfono contiene caracteres no permitidos'];
    }
    
    return ['valido' => true, 'valor' => htmlspecialchars($telefono)];
}

function validarAsunto($asunto) {
    $asuntos_validos = ['consulta', 'problema', 'sugerencia', 'soporte', 'otro'];
    
    if (empty($asunto)) {
        return ['valido' => false, 'error' => 'El asunto es requerido'];
    }
    
    if (!in_array($asunto, $asuntos_validos)) {
        return ['valido' => false, 'error' => 'El asunto seleccionado no es válido'];
    }
    
    return ['valido' => true, 'valor' => htmlspecialchars($asunto)];
}

function validarMensaje($mensaje) {
    $mensaje = trim($mensaje);
    
    if (empty($mensaje)) {
        return ['valido' => false, 'error' => 'El mensaje es requerido'];
    }
    
    if (strlen($mensaje) < 10) {
        return ['valido' => false, 'error' => 'El mensaje debe tener al menos 10 caracteres'];
    }
    
    if (strlen($mensaje) > 2000) {
        return ['valido' => false, 'error' => 'El mensaje no debe exceder 2000 caracteres'];
    }
    
    // Sanitizar sin permitir HTML ni scripts
    $mensaje = htmlspecialchars(strip_tags($mensaje), ENT_QUOTES, 'UTF-8');
    
    return ['valido' => true, 'valor' => $mensaje];
}

// ============================================================================
// VALIDAR DATOS
// ============================================================================

$validacion_nombre = validarNombre($_POST['nombre'] ?? '');
$validacion_email = validarEmail($_POST['email'] ?? '');
$validacion_telefono = validarTelefono($_POST['telefono'] ?? '');
$validacion_asunto = validarAsunto($_POST['asunto'] ?? '');
$validacion_mensaje = validarMensaje($_POST['mensaje'] ?? '');

// Validar privacidad
$privacidad_aceptada = isset($_POST['privacidad']) && $_POST['privacidad'] == 'on';

// Recolectar errores
if (!$validacion_nombre['valido']) {
    $errores['nombre'] = $validacion_nombre['error'];
}

if (!$validacion_email['valido']) {
    $errores['email'] = $validacion_email['error'];
}

if (!$validacion_telefono['valido']) {
    $errores['telefono'] = $validacion_telefono['error'];
}

if (!$validacion_asunto['valido']) {
    $errores['asunto'] = $validacion_asunto['error'];
}

if (!$validacion_mensaje['valido']) {
    $errores['mensaje'] = $validacion_mensaje['error'];
}

if (!$privacidad_aceptada) {
    $errores['privacidad'] = 'Debes aceptar la política de privacidad';
}

// Si hay errores, redirigir
if (!empty($errores)) {
    $error_key = key($errores);
    header('Location: contacto.php?error=' . urlencode($error_key));
    exit;
}

// ============================================================================
// GUARDAR EN BASE DE DATOS
// ============================================================================

try {
    $dContacto = new dContacto();
    
    $datos_contacto = [
        'nombre' => $validacion_nombre['valor'],
        'email' => $validacion_email['valor'],
        'telefono' => $validacion_telefono['valor'],
        'asunto' => $validacion_asunto['valor'],
        'mensaje' => $validacion_mensaje['valor'],
        'usuario_id' => estaAutenticado() ? obtenerUsuarioId() : null,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        'navegador' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    ];
    
    $resultado = $dContacto->guardarContacto($datos_contacto);
    
    if ($resultado) {
        // Registrar en auditoría
        registrarAuditoria(
            'contacto_enviado',
            'Mensaje de contacto enviado: ' . $validacion_asunto['valor'],
            'contactos',
            $resultado
        );
        
        // ====================================================================
        // ENVIAR EMAIL DE CONFIRMACIÓN (opcional)
        // ====================================================================
        
        // Descomenta esto si tienes configurado el servicio de email
        /*
        if (EMAIL_SERVICE_ENABLED) {
            $para = $validacion_email['valor'];
            $asunto_email = 'Hemos recibido tu mensaje - ' . APP_NAME;
            
            $mensaje_email = "
            <html>
            <head>
                <title>Confirmación de Contacto</title>
            </head>
            <body>
                <h2>Gracias por tu mensaje</h2>
                <p>Hola " . $validacion_nombre['valor'] . ",</p>
                <p>Hemos recibido tu mensaje correctamente. Nuestro equipo lo revisará y se pondrá en contacto contigo en breve.</p>
                <hr>
                <h3>Detalles de tu mensaje:</h3>
                <p><strong>Asunto:</strong> " . $validacion_asunto['valor'] . "</p>
                <p><strong>Mensaje:</strong></p>
                <p>" . nl2br($validacion_mensaje['valor']) . "</p>
                <hr>
                <p>Saludos,<br>El equipo de " . APP_NAME . "</p>
            </body>
            </html>
            ";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: " . EMAIL_FROM_ADDRESS . "\r\n";
            
            mail($para, $asunto_email, $mensaje_email, $headers);
        }
        */
        
        // ====================================================================
        // ENVIAR EMAIL AL ADMIN (opcional)
        // ====================================================================
        
        // Descomenta esto para recibir notificaciones cuando alguien contacte
        /*
        $asunto_admin = 'Nuevo mensaje de contacto de ' . $validacion_nombre['valor'];
        $mensaje_admin = "
        Se ha recibido un nuevo mensaje de contacto:\n\n
        Nombre: " . $validacion_nombre['valor'] . "\n
        Email: " . $validacion_email['valor'] . "\n
        Teléfono: " . ($validacion_telefono['valor'] ?: 'No proporcionado') . "\n
        Asunto: " . $validacion_asunto['valor'] . "\n
        Mensaje:\n" . $validacion_mensaje['valor'] . "\n\n
        IP del cliente: " . $_SERVER['REMOTE_ADDR'] . "\n
        Fecha: " . date('Y-m-d H:i:s') . "\n
        ";
        
        mail(EMAIL_FROM_ADDRESS, $asunto_admin, $mensaje_admin);
        */
        
        // Redirigir con éxito
        header('Location: contacto.php?exito=1');
        exit;
    } else {
        throw new Exception('No se pudo guardar el mensaje de contacto');
    }
    
} catch (Exception $e) {
    error_log('Error en enviarContacto.php: ' . $e->getMessage());
    header('Location: contacto.php?error=error_servidor');
    exit;
}
