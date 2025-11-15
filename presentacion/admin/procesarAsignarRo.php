<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

require_once __DIR__ . '/../../negocio/nAsignarRol.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: aAsignarRol.php');
    exit();
}

try {
    if (!isset($_POST['usuario_id'])) {
        $_SESSION['mensaje'] = 'Usuario no especificado';
        $_SESSION['tipo_mensaje'] = 'danger';
        header('Location: aAsignarRol.php');
        exit();
    }

    $usuario_id = intval($_POST['usuario_id']);
    $rol_id = !empty($_POST['rol_id']) ? intval($_POST['rol_id']) : null;

    $nAsignarRol = new nAsignarRol();
    $resultado = $nAsignarRol->asignarRol($usuario_id, $rol_id);

    if ($resultado['exito']) {
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo_mensaje'] = 'danger';
    }

} catch (Exception $e) {
    $_SESSION['mensaje'] = 'Error al asignar rol: ' . $e->getMessage();
    $_SESSION['tipo_mensaje'] = 'danger';
    error_log('Error en procesarAsignarRol: ' . $e->getMessage());
}

header('Location: aAsignarRol.php');
exit();
?>