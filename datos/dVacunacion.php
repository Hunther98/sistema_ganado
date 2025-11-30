<?php
require_once 'dConexion.php';

class dVacunacion {
    private $id;
    private $ganado_id;
    private $vacuna;
    private $fecha_vacunacion;
    private $proxima_vacunacion;
    private $observaciones;
    
    function __construct($id = null, $ganado_id = null, $vacuna = null, $fecha_vacunacion = null, $proxima_vacunacion = null, $observaciones = null) {
        $this->id = $id;
        $this->ganado_id = $ganado_id;
        $this->vacuna = $vacuna;
        $this->fecha_vacunacion = $fecha_vacunacion;
        $this->proxima_vacunacion = $proxima_vacunacion;
        $this->observaciones = $observaciones;
    }
    
    // Función para registrar vacunación
    function registrar() {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "INSERT INTO vacunas (ganado_id, vacuna, fecha_vacunacion, proxima_vacunacion, observaciones) VALUES (?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "issss", 
                $this->ganado_id,
                $this->vacuna,
                $this->fecha_vacunacion,
                $this->proxima_vacunacion,
                $this->observaciones
            );
            
            $respuesta = mysqli_stmt_execute($stmt);
            if ($respuesta) {
                $this->id = mysqli_insert_id($con);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al registrar vacunación: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Función para obtener vacunaciones por ganado
    function obtenerPorGanado($ganado_id) {
        $cone = new dConexion();
        $vacunaciones = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM vacunas WHERE ganado_id = ? ORDER BY fecha_vacunacion DESC";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $ganado_id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $vacunaciones[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener vacunaciones por ganado : " . $exc->getMessage();
        }
        
        return $vacunaciones;
    }
    
    // Función para obtener vacunación por ID
    function obtenerPorId($id) {
        $cone = new dConexion();
        $vacunacion = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM vacunaciones WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $vacunacion = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener vacunación por id : " . $exc->getMessage();
        }
        
        return $vacunacion;
    }
    
    // Función para actualizar vacunación
    function actualizar() {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "UPDATE vacunas SET vacuna = ?, fecha_vacunacion = ?, proxima_vacunacion = ?, observaciones = ? WHERE id = ?";
            
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ssssi", 
                $this->vacuna,
                $this->fecha_vacunacion,
                $this->proxima_vacunacion,
                $this->observaciones,
                $this->id
            );
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al actualizar vacunación: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Función para eliminar vacunación
    function eliminar($id) {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "DELETE FROM vacunas WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al eliminar vacunación: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getGanadoId() { return $this->ganado_id; }
    public function setGanadoId($ganado_id) { $this->ganado_id = $ganado_id; }
    
    public function getVacuna() { return $this->vacuna; }
    public function setVacuna($vacuna) { $this->vacuna = $vacuna; }
    
    public function getFechaVacunacion() { return $this->fecha_vacunacion; }
    public function setFechaVacunacion($fecha_vacunacion) { $this->fecha_vacunacion = $fecha_vacunacion; }
    
    public function getProximaVacunacion() { return $this->proxima_vacunacion; }
    public function setProximaVacunacion($proxima_vacunacion) { $this->proxima_vacunacion = $proxima_vacunacion; }
    
    public function getObservaciones() { return $this->observaciones; }
    public function setObservaciones($observaciones) { $this->observaciones = $observaciones; }
}
?>