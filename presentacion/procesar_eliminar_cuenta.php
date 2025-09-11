<?php
require_once '../config/config.php';
require_once '../negocio/nUsuario.php';
session_start();

$nUsuario = new nUsuario();
$password_confirm = $_POST['password_confirm'] ?? '';

$resultado = $nUsuario->eliminarCuenta($_SESSION['usuario_id'], $password_confirm);

if ($resultado['exito']) {
    // Cerrar sesión
    session_unset();
    session_destroy();
    
    // Redirigir con mensaje de éxito
    header('Location: index.php?exito=' . urlencode($resultado['mensaje']));
    exit;
} else {
    // Redirigir con mensaje de error
    header('Location: perfil.php?error=' . urlencode($resultado['mensaje']));
    exit;
}