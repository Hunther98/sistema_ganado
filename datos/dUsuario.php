<?php
require_once 'dConexion.php';

class dUsuario {
    private $id;
    private $nombre;
    private $apellido;
    private $email;
    private $password;
    private $telefono;
    private $direccion;
    private $tipo;
    private $activo;
    
    function __construct($id = null, $nombre = null, $apellido = null, $email = null, $password = null, $telefono = null, $direccion = null, $tipo = null, $activo = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->password = $password;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->tipo = $tipo;
        $this->activo = $activo;
    }
    
    // Función para registrar usuario
    function registrar() {
        $cone = new dConexion();
        $respuesta = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "INSERT INTO usuarios (nombre, apellido, email, password, telefono, direccion, tipo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($con, $sql);
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "sssssss", 
                $this->nombre,
                $this->apellido,
                $this->email,
                $hashedPassword,
                $this->telefono,
                $this->direccion,
                $this->tipo
            );
            
            $respuesta = mysqli_stmt_execute($stmt);
            if ($respuesta) {
                $this->id = mysqli_insert_id($con);
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al registrar usuario: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Función para iniciar sesión
    function iniciarSesion($email, $password) {
        $cone = new dConexion();
        $usuario = null;
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM usuarios WHERE email = ? AND activo = 1";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    $usuario = $row;
                }
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al iniciar sesión: " . $exc->getMessage();
        }
        
        return $usuario;
    }

    // Función para obtener todos los usuarios
    public function obtenerTodos() {
    $cone = new dConexion();
    $usuarios = [];
    
        try {
            $con = $cone->Conectar();
            $sql = "SELECT id, nombre, apellido, email, telefono, direccion, tipo, activo, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";
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
    // Función para obtener usuario por ID
    function obtenerPorId($id) {
        $cone = new dConexion();
        $usuario = null;
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT id, nombre, apellido, email, telefono, direccion, tipo, fecha_registro FROM usuarios WHERE id = ? AND activo = 1";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $usuario = $row;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al obtener usuario: " . $exc->getMessage();
        }
        
        return $usuario;
    }
    
    // Función para verificar si email existe
    function emailExiste($email) {
        $cone = new dConexion();
        $existe = false;
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT id FROM usuarios WHERE email = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_fetch_assoc($result)) {
                $existe = true;
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al verificar email: " . $exc->getMessage();
        }
        
        return $existe;
    }
    // Función para actualizar usuario
    public function actualizar($id, $nombre, $apellido, $email, $telefono, $direccion, $tipo, $activo) {
    $cone = new dConexion();
    $respuesta = false;
    
        try {
            $con = $cone->Conectar();
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ?, direccion = ?, tipo = ?, activo = ? WHERE id = ?";
            
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssii", 
                $nombre,
                $apellido,
                $email,
                $telefono,
                $direccion,
                $tipo,
                $activo,
                $id
            );
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al actualizar usuario: " . $exc->getMessage();
        }
        
        return $respuesta;
    }

    // Función para eliminar usuario
    public function eliminar($id) {
    $cone = new dConexion();
    $respuesta = false;
    
        try {
            $con = $cone->Conectar();
            $sql = "DELETE FROM usuarios WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            $respuesta = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $exc) {
            echo "Error al eliminar usuario: " . $exc->getMessage();
        }
        
        return $respuesta;
    }
    
    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    
    public function getApellido() { return $this->apellido; }
    public function setApellido($apellido) { $this->apellido = $apellido; }
    
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }
    
    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
    
    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }
    
    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; }
}
?>