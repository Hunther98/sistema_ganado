<?php
// clase para la conexion a la base de datos//
class dConexion {
    private $servidor;
    private $baseDatos;
    private $puerto;
    private $usuario;
    private $clave;
    
    function __construct(){
        $this->servidor = "localhost";
        $this->baseDatos = "venta_ganado";
        $this->puerto = "3306";
        $this->usuario = "root";
        $this->clave = "";
    }
    
    function Conectar(){
        // Configurar mysqli para mostrar errores
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        try {
            $con = mysqli_connect($this->servidor, $this->usuario, $this->clave, $this->baseDatos, $this->puerto);
            
            // Configurar charset UTF-8
            mysqli_set_charset($con, "utf8");
            
            return $con;
            
        } catch (mysqli_sql_exception $e) {
            echo "Error en la conexión: " . $e->getMessage();
            return false;
        }
    }
}
?>