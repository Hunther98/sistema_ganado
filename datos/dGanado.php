<?php
require_once 'dConexion.php';

class dGanado {
    private $id;
    private $usuario_id;
    private $nombre;
    private $descripcion;
    private $raza;
    private $edad;
    private $peso;
    private $precio;
    private $imagen;
    private $ubicacion;
    private $latitud;
    private $longitud;
    private $estado;
    
    function __construct($id = null, $usuario_id = null, $nombre = null, $descripcion = null, $raza = null, $edad = null, $peso = null, $precio = null, $imagen = null, $ubicacion = null, $latitud = null, $longitud = null, $estado = null) {
        $this->id = $id;
        $this->usuario_id = $usuario_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->raza = $raza;
        $this->edad = $edad;
        $this->peso = $peso;
        $this->precio = $precio;
        $this->imagen = $imagen;
        $this->ubicacion = $ubicacion;
        $this->latitud = $latitud;
        $this->longitud = $longitud;
        $this->estado = $estado;
    }
    
    // Función para registrar ganado
    function registrar() {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "INSERT INTO ganado (usuario_id, nombre, descripcion, raza, edad, peso, precio, imagen, ubicacion, latitud, longitud) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            // $sql = "CALL sp_registrar_usuario(?, ?, ?, ?, ?, ?, ?, @usuario_id)";

            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "isssiddssdd", 
                $this->usuario_id,
                $this->nombre,
                $this->descripcion,
                $this->raza,
                $this->edad,
                $this->peso,
                $this->precio,
                $this->imagen,
                $this->ubicacion,
                $this->latitud,
                $this->longitud
            );
            
            $respuesta = mysqli_stmt_execute($stmt);
            if ($respuesta) {
                $this->id = mysqli_insert_id($con);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al registrar ganado: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Función para obtener todos los animales disponibles
    function obtenerTodos($filtros = []) {
        $cone = new dConexion();
        $ganado = [];
        
        try {
            $con = $cone->Conectar();
            
            // Construir consulta base
            $sql = "SELECT g.*, u.nombre as vendedor_nombre, u.apellido as vendedor_apellido 
                    FROM ganado g 
                    INNER JOIN usuarios u ON g.usuario_id = u.id 
                    WHERE g.estado = 'disponible'";
            
            // Aplicar filtros
            $params = [];
            $types = "";
            
            if (!empty($filtros['raza'])) {
                $sql .= " AND g.raza = ?";
                $params[] = $filtros['raza'];
                $types .= "s";
            }
            
            if (!empty($filtros['edad_min'])) {
                $sql .= " AND g.edad >= ?";
                $params[] = $filtros['edad_min'];
                $types .= "i";
            }
            
            if (!empty($filtros['edad_max'])) {
                $sql .= " AND g.edad <= ?";
                $params[] = $filtros['edad_max'];
                $types .= "i";
            }
            
            if (!empty($filtros['precio_min'])) {
                $sql .= " AND g.precio >= ?";
                $params[] = $filtros['precio_min'];
                $types .= "d";
            }
            
            if (!empty($filtros['precio_max'])) {
                $sql .= " AND g.precio <= ?";
                $params[] = $filtros['precio_max'];
                $types .= "d";
            }
            
            $sql .= " ORDER BY g.fecha_registro DESC";
            
            $stmt = mysqli_prepare($con, $sql);
            
            // Bind parameters si hay filtros
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $ganado[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener ganado: " . $exc->getMessage();
        }
        
        return $ganado;
    }
    
    // Función para obtener ganado por ID
    function obtenerPorId($id) {
        $cone = new dConexion();
        $ganado = null;
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT g.*, u.nombre as vendedor_nombre, u.apellido as vendedor_apellido, u.telefono as vendedor_telefono 
                    FROM ganado g 
                    INNER JOIN usuarios u ON g.usuario_id = u.id 
                    WHERE g.id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $ganado = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener ganado: " . $exc->getMessage();
        }
        
        return $ganado;
    }
    
    // Función para obtener ganado por usuario
    function obtenerPorUsuario($usuario_id) {
        $cone = new dConexion();
        $ganado = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM ganado WHERE usuario_id = ? ORDER BY fecha_registro DESC";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $usuario_id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $ganado[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener ganado del usuario: " . $exc->getMessage();
        }
        
        return $ganado;
    }
    // Función para obtener ganado reciente
    public function obtenerReciente($limite) {
        $cone = new dConexion();
        $ganado = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM ganado ORDER BY id DESC LIMIT ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $limite);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $ganado[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener ganado reciente: " . $exc->getMessage();
        }
        
        return $ganado;
    }
    
    // Función para actualizar ganado
    function actualizar() {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "UPDATE ganado SET nombre = ?, descripcion = ?, raza = ?, edad = ?, peso = ?, precio = ?, imagen = ?, ubicacion = ?, latitud = ?, longitud = ? WHERE id = ? AND usuario_id = ?";
            // $sql = "CALL sp_actualizar_usuario(?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "sssiddssddii", 
                $this->nombre,
                $this->descripcion,
                $this->raza,
                $this->edad,
                $this->peso,
                $this->precio,
                $this->imagen,
                $this->ubicacion,
                $this->latitud,
                $this->longitud,
                $this->id,
                $this->usuario_id
            );
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al actualizar ganado: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Función para eliminar ganado
    function eliminar($id, $usuario_id) {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "DELETE FROM ganado WHERE id = ? AND usuario_id = ?";
            // $sql = "CALL sp_eliminar_usuario_logico(?, ?, ?)";

            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $id, $usuario_id);
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al eliminar ganado: " . $exc->getMessage();
        }
        
        return $respuesta;
    }

     function restaurarUsuario($id) {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            
            // Llamar al procedimiento almacenado
            $sql = "CALL sp_restaurar_usuario(?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al restaurar usuario: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Obtener todos los usuarios (sin cambios, es una consulta simple)
    function obtenerTodosLosUsuarios() {
        $cone = new dConexion();
        $usuarios = [];
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT id, nombre, apellido, email, telefono, direccion, tipo, activo, 
                           cuenta_inactiva, fecha_registro, eliminado, fecha_eliminacion
                    FROM usuarios 
                    WHERE eliminado = FALSE 
                    ORDER BY fecha_registro DESC";
            $result = mysqli_query($con, $sql);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $usuarios[] = $row;
            }
            
            mysqli_free_result($result);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener usuarios: " . $exc->getMessage();
        }
        
        return $usuarios;
    }
    
    // Función para actualizar el estado del ganado (disponible, vendido, reservado)
    public function actualizarEstado($ganado_id, $estado) {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "UPDATE ganado SET estado = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "si", $estado, $ganado_id);
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al actualizar estado del ganado: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Getters y Setters
    public function getId() {
         return $this->id; 
    }
    public function setId($id) {
         $this->id = $id;
    }

    public function getUsuarioId() {
         return $this->usuario_id;
    }
    public function setUsuarioId($usuario_id) {
         $this->usuario_id = $usuario_id;
    }

    public function getNombre() {
         return $this->nombre;
    }
    public function setNombre($nombre) {
         $this->nombre = $nombre;
    }

    public function getDescripcion() {
         return $this->descripcion;
    }
    public function setDescripcion($descripcion) {
         $this->descripcion = $descripcion;
    }

    public function getRaza() {
         return $this->raza;
    }
    public function setRaza($raza) {
         $this->raza = $raza;
    }

    public function getEdad() {
         return $this->edad;
    }
    public function setEdad($edad) {
         $this->edad = $edad;
    }

    public function getPeso() {
         return $this->peso;
    }
    public function setPeso($peso) {
         $this->peso = $peso;
    }

    public function getPrecio() {
         return $this->precio;
    }
    public function setPrecio($precio) {
         $this->precio = $precio;
    }

    public function getImagen() {
         return $this->imagen;
    }
    public function setImagen($imagen) {
         $this->imagen = $imagen;
    }

    public function getUbicacion() {
         return $this->ubicacion;
    }
    public function setUbicacion($ubicacion) {
         $this->ubicacion = $ubicacion;
    }

    public function getLatitud() {
         return $this->latitud;
    }
    public function setLatitud($latitud) {
         $this->latitud = $latitud;
    }

    public function getLongitud() {
         return $this->longitud;
    }
    public function setLongitud($longitud) {
         $this->longitud = $longitud;
    }

    public function getEstado() {
         return $this->estado;
    }
    public function setEstado($estado) {
         $this->estado = $estado;
    }
}
?>