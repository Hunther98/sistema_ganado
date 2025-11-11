<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../negocio/nUsuario.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php?error=Debes iniciar sesión');
    exit;
}
// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../perfil.php?error=Método no permitido');
    exit;
}

// Verificar autenticación
verificarAutenticacion();

// Validar datos de entrada
if (!isset($_POST['password_confirm']) || empty(trim($_POST['password_confirm']))) {
    header('Location: ../perfil.php?error=La contraseña de confirmación es requerida');
    exit;
}

$password_confirm = trim($_POST['password_confirm']);

try {
    $nUsuario = new nUsuario();
    $resultado = $nUsuario->eliminarCuenta($_SESSION['usuario_id'], $password_confirm);

    if ($resultado['exito']) {
        // Destruir completamente la sesión
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Redireccionar con mensaje de éxito
        header('Location: ../index.php?exito=' . urlencode($resultado['mensaje']) . '&mostrarLogin=true');
        exit;
    } else {
        header('Location: ../perfil.php?error=' . urlencode($resultado['mensaje']));
        exit;
    }
} catch (Exception $e) {
    // Log del error (en producción)
    error_log('Error al eliminar cuenta: ' . $e->getMessage());
    
    // Mensaje genérico al usuario
    header('Location: ../perfil.php?error=Ocurrió un error inesperado');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Cuenta Eliminada</h1>
    <p>Tu cuenta ha sido eliminada exitosamente.</p>
    <a href="../index.php">Volver al inicio</a>
</body>
</html>
