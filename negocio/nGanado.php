<?php
require_once __DIR__ . '/../datos/dGanado.php';
require_once __DIR__ . '/../datos/dVacunacion.php';

class nGanado {
    
    public function registrarGanado($usuario_id, $nombre, $descripcion, $raza, $edad, $peso, $precio, $imagen, $ubicacion, $latitud, $longitud) {
        // Validaciones de negocio
        if (empty($nombre) || empty($raza) || empty($edad) || empty($peso) || empty($precio)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        if (!is_numeric($edad) || $edad <= 0) {
            return array('exito' => false, 'mensaje' => 'La edad debe ser un número mayor a 0');
        }
        
        if (!is_numeric($peso) || $peso <= 0) {
            return array('exito' => false, 'mensaje' => 'El peso debe ser un número mayor a 0');
        }
        
        if (!is_numeric($precio) || $precio <= 0) {
            return array('exito' => false, 'mensaje' => 'El precio debe ser un número mayor a 0');
        }
        
        if (empty($imagen)) {
            return array('exito' => false, 'mensaje' => 'Debe subir una imagen del animal');
        }
        
        $ganado = new dGanado(null, $usuario_id, $nombre, $descripcion, $raza, $edad, $peso, $precio, $imagen, $ubicacion, $latitud, $longitud, 'disponible');
        $resultado = $ganado->registrar();
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Ganado registrado exitosamente', 'id' => $ganado->getId());
        } else {
            return array('exito' => false, 'mensaje' => 'Error al registrar el ganado');
        }
    }
    
    public function actualizarGanado($id, $usuario_id, $nombre, $descripcion, $raza, $edad, $peso, $precio, $imagen, $ubicacion, $latitud, $longitud) {
        // Validaciones de negocio
        if (empty($id) || empty($nombre) || empty($raza) || empty($edad) || empty($peso) || empty($precio)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        if (!is_numeric($edad) || $edad <= 0) {
            return array('exito' => false, 'mensaje' => 'La edad debe ser un número mayor a 0');
        }
        
        if (!is_numeric($peso) || $peso <= 0) {
            return array('exito' => false, 'mensaje' => 'El peso debe ser un número mayor a 0');
        }
        
        if (!is_numeric($precio) || $precio <= 0) {
            return array('exito' => false, 'mensaje' => 'El precio debe ser un número mayor a 0');
        }
        
        $ganado = new dGanado($id, $usuario_id, $nombre, $descripcion, $raza, $edad, $peso, $precio, $imagen, $ubicacion, $latitud, $longitud, null);
        $resultado = $ganado->actualizar();
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Ganado actualizado exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al actualizar el ganado');
        }
    }
    
    public function eliminarGanado($id, $usuario_id) {
        // Validaciones de negocio
        if (empty($id)) {
            return array('exito' => false, 'mensaje' => 'ID de ganado es requerido');
        }
        
        $ganado = new dGanado();
        $resultado = $ganado->eliminar($id, $usuario_id);
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Ganado eliminado exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al eliminar el ganado');
        }
    }
    
    public function obtenerTodos($filtros = []) {
        $ganado = new dGanado();
        return $ganado->obtenerTodos($filtros);
    }
    
    public function obtenerPorId($id) {
        $ganado = new dGanado();
        return $ganado->obtenerPorId($id);
    }
    
    public function obtenerPorUsuario($usuario_id) {
        $ganado = new dGanado();
        return $ganado->obtenerPorUsuario($usuario_id);
    }
    
    public function obtenerRazas() {
        // Razas comunes de ganado
        return array(
            'Angus', 'Brahman', 'Hereford', 'Simmental', 'Limousin', 'Charolais',
            'Holstein', 'Jersey', 'Guernsey', 'Brown Swiss', 'Ayrshire', 'Shorthorn',
            'Brangus', 'Beefmaster', 'Charbray', 'Santa Gertrudis', 'Red Angus', 'Gelbvieh',
            'Salers', 'Highland', 'Texas Longhorn', 'Wagyu', 'Piedmontese', 'Devon'
        );
    }
    
    public function registrarVacunacion($ganado_id, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones) {
        // Validaciones de negocio
        if (empty($ganado_id) || empty($vacuna) || empty($fecha_vacunacion)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        $vacunacion = new dVacunacion(null, $ganado_id, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones);
        $resultado = $vacunacion->registrar();
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Vacunación registrada exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al registrar la vacunación');
        }
    }
    
    public function obtenerVacunaciones($ganado_id) {
        $vacunacion = new dVacunacion();
            return $vacunacion->obtenerPorGanado($ganado_id);
        }

        public function actualizarEstadoGanado($ganado_id, $estado) {
        // Validaciones de negocio
        if (empty($ganado_id) || empty($estado)) {
            return array('exito' => false, 'mensaje' => 'Datos incompletos');
        }
        
        $estadosPermitidos = ['disponible', 'vendido', 'reservado'];
        if (!in_array($estado, $estadosPermitidos)) {
            return array('exito' => false, 'mensaje' => 'Estado no válido');
        }
        
        $ganado = new dGanado();
        $resultado = $ganado->actualizarEstado($ganado_id, $estado);
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Estado actualizado exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al actualizar el estado');
        }
    }
    public function obtenerGanadoReciente($limite = 5) {
        $ganado = new dGanado();
        
        return $ganado->obtenerReciente($limite);

    }
    public function procesarCompra($ganado_id, $comprador_id) {
        // Validaciones de negocio
        if (empty($ganado_id) || empty($comprador_id)) {
            return array('exito' => false, 'mensaje' => 'Datos incompletos para procesar la compra');
        }

        $venta = new dVenta(null, $ganado_id, $comprador_id);
        $resultado = $venta->registrar();

        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Compra procesada exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al procesar la compra');
        }
    }

    public function obtenerVentasPendientesPorUsuario($usuario_id) {
        $venta = new dVenta();
        return $venta->obtenerVentasPendientesPorUsuario($usuario_id);
    }

    public function comprarGanado($ganado_id, $comprador_id) {
        // Validaciones de negocio
        if (empty($ganado_id) || empty($comprador_id)) {
            return array('exito' => false, 'mensaje' => 'Datos incompletos para comprar el ganado');
        }
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

    public function obtenerTodosLosAnimales() {
        $dGanado = new dGanado();
        return $dGanado->obtenerTodos();
    }

    public function obtenerTodasLasVentas() {
        $dVenta = new dVenta();
        return $dVenta->obtenerTodas();
    }
}

?>
