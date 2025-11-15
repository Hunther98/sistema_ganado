<?php
require_once __DIR__ . '/../datos/dConexion.php';
require_once __DIR__ . '/../datos/dUsuario.php';

class nListarRol {
    private $dUsuario;

    public function __construct() {
        $this->dUsuario = new dUsuario();
    }

    public function obtenerTodos() {
        return $this->dUsuario->obtenerTodosRol();
    }

    public function obtenerPorId($id) {
        if (!is_numeric($id) || $id <= 0) {
            return null;
        }
        return $this->dUsuario->obtenerPorIdRol($id);
    }

    public function crear($nombre, $descripcion = null) {
        // Validaciones
        $nombre = trim($nombre);
        
        if (empty($nombre)) {
            return [
                'exito' => false,
                'mensaje' => 'El nombre del rol es obligatorio'
            ];
        }

        if (strlen($nombre) < 3) {
            return [
                'exito' => false,
                'mensaje' => 'El nombre del rol debe tener al menos 3 caracteres'
            ];
        }

        if (strlen($nombre) > 50) {
            return [
                'exito' => false,
                'mensaje' => 'El nombre del rol no puede exceder 50 caracteres'
            ];
        }

        return $this->dUsuario->crearRol($nombre, $descripcion);
    }

    public function actualizar($id, $nombre, $descripcion = null) {
        // Validaciones
        if (!is_numeric($id) || $id <= 0) {
            return [
                'exito' => false,
                'mensaje' => 'ID de rol inválido'
            ];
        }

        $nombre = trim($nombre);
        
        if (empty($nombre)) {
            return [
                'exito' => false,
                'mensaje' => 'El nombre del rol es obligatorio'
            ];
        }

        if (strlen($nombre) < 3) {
            return [
                'exito' => false,
                'mensaje' => 'El nombre del rol debe tener al menos 3 caracteres'
            ];
        }

        if (strlen($nombre) > 50) {
            return [
                'exito' => false,
                'mensaje' => 'El nombre del rol no puede exceder 50 caracteres'
            ];
        }

        return $this->dUsuario->actualizarRol($id, $nombre, $descripcion);
    }

    public function eliminar($id) {
        if (!is_numeric($id) || $id <= 0) {
            return [
                'exito' => false,
                'mensaje' => 'ID de rol inválido'
            ];
        }

        return $this->dUsuario->eliminarRol($id);
    }

    public function obtenerEstadisticas() {
        return $this->dUsuario->obtenerEstadisticasRol();
    }
}
?>