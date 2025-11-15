<?php
require_once __DIR__ . '/../datos/dConexion.php';
require_once __DIR__ . '/../datos/dUsuario.php';

class nAsignarRol {
    private $dUsuario;

    public function __construct() {
        $this->dUsuario = new dUsuario();
    }

    public function obtenerUsuariosConRoles() {
        return $this->dUsuario->obtenerUsuariosConRoles();
    }

    public function obtenerTodosRoles() {
        return $this->dUsuario->obtenerTodosRol();
    }

    public function asignarRol($usuario_id, $rol_id) {
        // Validaciones
        if (!is_numeric($usuario_id) || $usuario_id <= 0) {
            return [
                'exito' => false,
                'mensaje' => 'ID de usuario inválido'
            ];
        }

        // Permitir null para quitar el rol
        if ($rol_id !== null && $rol_id !== '' && (!is_numeric($rol_id) || $rol_id <= 0)) {
            return [
                'exito' => false,
                'mensaje' => 'ID de rol inválido'
            ];
        }

        return $this->dUsuario->asignarRol($usuario_id, $rol_id);
    }
}
?>