<?php
require_once __DIR__ . '/../datos/dVacunacion.php';

class nVacunacion {
    
    public function registrarVacunacion($ganado_id, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones) {
        // Validaciones de negocio
        if (empty($ganado_id) || empty($vacuna) || empty($fecha_vacunacion)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        // Validar formato de fecha
        if (!DateTime::createFromFormat('Y-m-d', $fecha_vacunacion)) {
            return array('exito' => false, 'mensaje' => 'Formato de fecha de vacunación no válido');
        }
        
        if (!empty($proxima_vacunacion) && !DateTime::createFromFormat('Y-m-d', $proxima_vacunacion)) {
            return array('exito' => false, 'mensaje' => 'Formato de fecha de próxima vacunación no válido');
        }
        
        $vacunacion = new dVacunacion(null, $ganado_id, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones);
        $resultado = $vacunacion->registrar();
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Vacunación registrada exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al registrar la vacunación');
        }
    }
    
    public function obtenerVacunacionesPorGanado($ganado_id) {
        $vacunacion = new dVacunacion();
        return $vacunacion->obtenerPorGanado($ganado_id);
    }
    
    public function obtenerVacunacionPorId($id) {
        $vacunacion = new dVacunacion();
        return $vacunacion->obtenerPorId($id);
    }
    
    public function actualizarVacunacion($id, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones) {
        // Validaciones de negocio
        if (empty($id) || empty($vacuna) || empty($fecha_vacunacion)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        // Validar formato de fecha
        if (!DateTime::createFromFormat('Y-m-d', $fecha_vacunacion)) {
            return array('exito' => false, 'mensaje' => 'Formato de fecha de vacunación no válido');
        }
        
        if (!empty($proxima_vacunacion) && !DateTime::createFromFormat('Y-m-d', $proxima_vacunacion)) {
            return array('exito' => false, 'mensaje' => 'Formato de fecha de próxima vacunación no válido');
        }
        
        $vacunacion = new dVacunacion($id, null, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones);
        $resultado = $vacunacion->actualizar();
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Vacunación actualizada exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al actualizar la vacunación');
        }
    }
    
    public function eliminarVacunacion($id) {
        // Validaciones de negocio
        if (empty($id)) {
            return array('exito' => false, 'mensaje' => 'ID de vacunación es requerido');
        }
        
        $vacunacion = new dVacunacion();
        $resultado = $vacunacion->eliminar($id);
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Vacunación eliminada exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al eliminar la vacunación');
        }
    }
    
    public function obtenerVacunasComunes() {
        // Vacunas comunes para ganado
        return array(
            'Fiebre Aftosa',
            'Brucelosis',
            'Carbón Sintomático',
            'Clostridiales',
            'Leptospirosis',
            'Rinotraqueítis Infecciosa Bovina',
            'Diarrea Viral Bovina',
            'Parainfluenza',
            'Respiratory Syncytial Virus',
            'Campylobacteriosis',
            'Tricomoniasis',
            'Rabia',
            'Tuberculosis',
            'Queratitis Infecciosa'
        );
    }
}
?>