<?php
require_once '../config/config.php';
require_once '../negocio/nVenta.php';
require_once '../negocio/nGanado.php';

verificarAutenticacion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?error=Método no permitido');
    exit;
}

$nVenta = new nVenta();
$nGanado = new nGanado();

$accion = $_POST['accion'] ?? '';
$venta_id = $_POST['venta_id'] ?? 0;
$ganado_id = $_POST['ganado_id'] ?? 0;

// Validar que tenemos los datos necesarios
if (empty($accion)) {
    header('Location: ../index.php?error=Acción no especificada');
    exit;
}

try {
    switch ($accion) {
        case 'comprar':
            // Procesar nueva compra
            if (empty($ganado_id)) {
                throw new Exception('ID de ganado no especificado');
            }
            
            $resultado = $nVenta->procesarVenta($_SESSION['usuario_id'], $ganado_id);
            
            if ($resultado['exito']) {
                header('Location: mis_compras.php?exito=' . urlencode($resultado['mensaje']));
            } else {
                header('Location: catalogo.php?error=' . urlencode($resultado['mensaje']));
            }
            break;
            
        case 'completar':
            // Completar venta (para vendedores)
            if (empty($venta_id)) {
                throw new Exception('ID de venta no especificado');
            }
            
            // Verificar que el usuario tiene permisos para completar esta venta
            $venta = $nVenta->obtenerVentaPorId($venta_id);
            if (!$venta || $venta['vendedor_id'] != $_SESSION['usuario_id']) {
                throw new Exception('No tienes permisos para completar esta venta');
            }
            
            $resultado = $nVenta->actualizarEstadoVenta($venta_id, 'completada');
            
            if ($resultado['exito']) {
                header('Location: mis_ventas.php?exito=' . urlencode($resultado['mensaje']));
            } else {
                header('Location: mis_ventas.php?error=' . urlencode($resultado['mensaje']));
            }
            break;
            
        case 'cancelar':
            // Cancelar venta
            if (empty($venta_id)) {
                throw new Exception('ID de venta no especificado');
            }
            
            // Verificar que el usuario tiene permisos para cancelar esta venta
            $venta = $nVenta->obtenerVentaPorId($venta_id);
            if (!$venta || 
                ($venta['comprador_id'] != $_SESSION['usuario_id'] && 
                 $venta['vendedor_id'] != $_SESSION['usuario_id'])) {
                throw new Exception('No tienes permisos para cancelar esta venta');
            }
            
            $resultado = $nVenta->actualizarEstadoVenta($venta_id, 'cancelada');
            
            // Reactivar el ganado si se cancela la venta
            if ($resultado['exito']) {
                $nGanado->actualizarEstadoGanado($venta['ganado_id'], 'disponible');
                
                if ($venta['comprador_id'] == $_SESSION['usuario_id']) {
                    header('Location: mis_compras.php?exito=' . urlencode($resultado['mensaje']));
                } else {
                    header('Location: mis_ventas.php?exito=' . urlencode($resultado['mensaje']));
                }
            } else {
                if ($venta['comprador_id'] == $_SESSION['usuario_id']) {
                    header('Location: mis_compras.php?error=' . urlencode($resultado['mensaje']));
                } else {
                    header('Location: mis_ventas.php?error=' . urlencode($resultado['mensaje']));
                }
            }
            break;
            
        case 'confirmar_entrega':
            // Confirmar entrega (para compradores)
            if (empty($venta_id)) {
                throw new Exception('ID de venta no especificado');
            }
            
            // Verificar que el usuario es el comprador
            $venta = $nVenta->obtenerVentaPorId($venta_id);
            if (!$venta || $venta['comprador_id'] != $_SESSION['usuario_id']) {
                throw new Exception('No tienes permisos para confirmar esta entrega');
            }
            
            $resultado = $nVenta->actualizarEstadoVenta($venta_id, 'entregada');
            
            if ($resultado['exito']) {
                header('Location: mis_compras.php?exito=' . urlencode('Entrega confirmada exitosamente'));
            } else {
                header('Location: mis_compras.php?error=' . urlencode($resultado['mensaje']));
            }
            break;
            
        default:
            throw new Exception('Acción no válida');
    }
    
} catch (Exception $e) {
    // Determinar a dónde redirigir basado en el contexto
    $pagina_redireccion = '../index.php';
    
    if (isset($venta)) {
        if ($venta['comprador_id'] == $_SESSION['usuario_id']) {
            $pagina_redireccion = 'mis_compras.php';
        } else {
            $pagina_redireccion = 'mis_ventas.php';
        }
    } else if ($accion == 'comprar') {
        $pagina_redireccion = 'catalogo.php';
    }
    
    header('Location: ' . $pagina_redireccion . '?error=' . urlencode($e->getMessage()));
    exit;
}