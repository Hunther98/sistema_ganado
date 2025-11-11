<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../negocio/nUsuario.php';

// Verificar si el usuario es administrador
verificarAutenticacion();
if ($_SESSION['usuario_tipo'] != 'admin') {
    header('Location: ../index.php?error=permisos');
    exit;
}

$nUsuario = new nUsuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = $_POST['id'] ?? 0;

    switch ($accion) {
        case 'eliminar':
            // Verificar que no sea el usuario actual
            if ($id == $_SESSION['usuario_id']) {
                header('Location: listar_usuario.php?error=No puedes eliminarte a ti mismo');
                exit;
            }

            $resultado = $nUsuario->eliminarUsuario($id);
            
            if ($resultado['exito']) {
                header('Location: listar_usuario.php?exito=' . urlencode($resultado['mensaje']));
            } else {
                header('Location: listar_usuario.php?error=' . urlencode($resultado['mensaje']));
            }
            break;

        default:
            header('Location: listar_usuario.php?error=Acción no válida');
            break;
    }
} else {
    header('Location: listar_usuario.php');
}
exit;
