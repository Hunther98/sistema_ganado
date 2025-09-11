<?php
require_once '../datos/dVenta.php';
require_once '../datos/dGanado.php';

class nVenta {
    
    public function procesarVenta($comprador_id, $ganado_id) {
        // Validaciones de negocio
        if (empty($comprador_id) || empty($ganado_id)) {
            return array('exito' => false, 'mensaje' => 'Datos incompletos para procesar la venta');
        }
        
        // Obtener información del ganado
        $dGanado = new dGanado();
        $ganado = $dGanado->obtenerPorId($ganado_id);
        
        if (!$ganado) {
            return array('exito' => false, 'mensaje' => 'El ganado no existe');
        }
        
        if ($ganado['estado'] != 'disponible') {
            return array('exito' => false, 'mensaje' => 'El ganado no está disponible para la venta');
        }
        
        if ($ganado['usuario_id'] == $comprador_id) {
            return array('exito' => false, 'mensaje' => 'No puedes comprar tu propio ganado');
        }
        
        // Calcular comisión (5% del precio)
        $comision = $ganado['precio'] * 0.05;
        $precio_venta = $ganado['precio'];
        
        $venta = new dVenta(null, $comprador_id, $ganado['usuario_id'], $ganado_id, null, $precio_venta, $comision, 'pendiente');
        $resultado = $venta->registrar();
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Venta procesada exitosamente', 'id' => $venta->getId());
        } else {
            return array('exito' => false, 'mensaje' => 'Error al procesar la venta');
        }
    }
    
    public function obtenerVentasPorComprador($comprador_id) {
        $venta = new dVenta();
        return $venta->obtenerPorComprador($comprador_id);
    }
    
    public function obtenerVentasPorVendedor($vendedor_id) {
        $venta = new dVenta();
        return $venta->obtenerPorVendedor($vendedor_id);
    }
    
    public function obtenerVentaPorId($id) {
        $venta = new dVenta();
        return $venta->obtenerPorId($id);
    }
    
    public function actualizarEstadoVenta($id, $estado) {
        // Validaciones de negocio
        if (empty($id) || empty($estado)) {
            return array('exito' => false, 'mensaje' => 'Datos incompletos para actualizar la venta');
        }
        
        $estadosPermitidos = ['pendiente', 'completada', 'cancelada'];
        if (!in_array($estado, $estadosPermitidos)) {
            return array('exito' => false, 'mensaje' => 'Estado de venta no válido');
        }
        
        $venta = new dVenta();
        $resultado = $venta->actualizarEstado($id, $estado);
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Estado de venta actualizado exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al actualizar el estado de la venta');
        }
    }
    public function obtenerVentasPendientesPorUsuario($usuario_id) {
        $venta = new dVenta();
        return $venta->obtenerVentasPendientesPorUsuario($usuario_id);
    }
    public function obtenerTodasLasVentas() {
        $dVenta = new dVenta();
        return $dVenta->obtenerTodas();
    }
}
?>