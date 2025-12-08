<?php
require_once 'dConexion.php';

class dVenta {
    private $id;
    private $comprador_id;
    private $vendedor_id;
    private $ganado_id;
    private $fecha;
    private $precio_venta;
    private $comision;
    private $estado;
    
    function __construct($id = null, $comprador_id = null, $vendedor_id = null, $ganado_id = null, $fecha = null, $precio_venta = null, $comision = null, $estado = null) {
        $this->id = $id;
        $this->comprador_id = $comprador_id;
        $this->vendedor_id = $vendedor_id;
        $this->ganado_id = $ganado_id;
        $this->fecha = $fecha;
        $this->precio_venta = $precio_venta;
        $this->comision = $comision;
        $this->estado = $estado;
    }
    
    // Función para registrar venta
    function registrar() {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            
            // Iniciar transacción
            mysqli_begin_transaction($con);
            
            // 1. Registrar la venta
            $sql = "INSERT INTO ventas (comprador_id, vendedor_id, ganado_id, precio_venta, comision) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "iiidd", 
                $this->comprador_id,
                $this->vendedor_id,
                $this->ganado_id,
                $this->precio_venta,
                $this->comision
            );
            
            $resultado = mysqli_stmt_execute($stmt);
            
            if ($resultado) {
                $this->id = mysqli_insert_id($con);
                
                // 2. Actualizar estado del ganado a "vendido"
                $sqlUpdate = "UPDATE ganado SET estado = 'vendido' WHERE id = ?";
                $stmtUpdate = mysqli_prepare($con, $sqlUpdate);
                mysqli_stmt_bind_param($stmtUpdate, "i", $this->ganado_id);
                $resultadoUpdate = mysqli_stmt_execute($stmtUpdate);
                
                if ($resultadoUpdate) {
                    mysqli_commit($con);
                    $respuesta = true;
                } else {
                    mysqli_rollback($con);
                }
                
                mysqli_stmt_close($stmtUpdate);
            } else {
                mysqli_rollback($con);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al registrar venta: " . $exc->getMessage();
            if (isset($con)) {
                mysqli_rollback($con);
            }
        }
        
        return $respuesta;
    }
    
    // Función para obtener ventas por comprador
    function obtenerPorComprador($comprador_id) {
        $cone = new dConexion();
        $ventas = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT v.*, g.nombre as ganado_nombre, g.imagen as ganado_imagen, 
                           u.nombre as vendedor_nombre, u.apellido as vendedor_apellido
                    FROM ventas v
                    INNER JOIN ganado g ON v.ganado_id = g.id
                    INNER JOIN usuarios u ON v.vendedor_id = u.id
                    WHERE v.comprador_id = ?
                    ORDER BY v.fecha DESC";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $comprador_id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $ventas[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener ventas: " . $exc->getMessage();
        }
        
        return $ventas;
    }
    
    // Función para obtener ventas por vendedor
    function obtenerPorVendedor($vendedor_id) {
        $cone = new dConexion();
        $ventas = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT v.*, g.nombre as ganado_nombre, g.imagen as ganado_imagen, 
                           u.nombre as comprador_nombre, u.apellido as comprador_apellido
                    FROM ventas v
                    INNER JOIN ganado g ON v.ganado_id = g.id
                    INNER JOIN usuarios u ON v.comprador_id = u.id
                    WHERE v.vendedor_id = ?
                    ORDER BY v.fecha DESC";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $vendedor_id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $ventas[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener ventas: " . $exc->getMessage();
        }
        
        return $ventas;
    }
    
    // Función para obtener venta por ID
    function obtenerPorId($id) {
        $cone = new dConexion();
        $venta =[];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT v.*, g.nombre as ganado_nombre, g.descripcion as ganado_descripcion, g.imagen as ganado_imagen,
                           uv.nombre as vendedor_nombre, uv.apellido as vendedor_apellido, uv.telefono as vendedor_telefono,
                           uc.nombre as comprador_nombre, uc.apellido as comprador_apellido, uc.telefono as comprador_telefono
                    FROM ventas v
                    INNER JOIN ganado g ON v.ganado_id = g.id
                    INNER JOIN usuarios uv ON v.vendedor_id = uv.id
                    INNER JOIN usuarios uc ON v.comprador_id = uc.id
                    WHERE v.id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $venta = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener venta: " . $exc->getMessage();
        }
        
        return $venta;
    }
    
    // Función para actualizar estado de venta
    function actualizarEstado($id, $estado) {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "UPDATE ventas SET estado = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "si", $estado, $id);
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al actualizar venta: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    // agregado
// Función para obtener ventas pendientes por usuario (como comprador o vendedor)
    public function obtenerVentasPendientesPorUsuario($usuario_id) {
        $cone = new dConexion();
        $ventas = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM ventas WHERE (comprador_id = ? OR vendedor_id = ?) AND estado = 'pendiente'";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $usuario_id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $ventas[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener ventas pendientes: " . $exc->getMessage();
        }
        
        return $ventas;
    }

    // Función para obtener todas las ventas
    public function obtenerTodas() {
        $cone = new dConexion();
        $ventas = [];

        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM ventas";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $ventas[] = $row;
            }

            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener todas las ventas: " . $exc->getMessage();
        }

        return $ventas;
    }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getCompradorId() { return $this->comprador_id; }
    public function setCompradorId($comprador_id) { $this->comprador_id = $comprador_id; }
    
    public function getVendedorId() { return $this->vendedor_id; }
    public function setVendedorId($vendedor_id) { $this->vendedor_id = $vendedor_id; }
    
    public function getGanadoId() { return $this->ganado_id; }
    public function setGanadoId($ganado_id) { $this->ganado_id = $ganado_id; }
    
    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    
    public function getPrecioVenta() { return $this->precio_venta; }
    public function setPrecioVenta($precio_venta) { $this->precio_venta = $precio_venta; }
    
    public function getComision() { return $this->comision; }
    public function setComision($comision) { $this->comision = $comision; }
    
    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }
}
?>