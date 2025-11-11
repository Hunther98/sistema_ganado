<?php
// Cargar clase de datos de estadísticas (ruta corregida)
require_once __DIR__ . '/../datos/dEstadisticas.php';

class nEstadisticas {
    public function obtenerEstadisticasGenerales() {
        // Aquí iría la lógica para obtener las estadísticas generales
        $dEstadisticas = new dEstadisticas();
        return $dEstadisticas->obtenerEstadisticasGenerales();

    }  

}