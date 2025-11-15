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
    
    // Función para iniciar sesión (acepta email o nombre)
    function iniciarSesion($identificador, $password) {
        $cone = new dConexion();
        $usuario = null;
        
        try {
            $con = $cone->Conectar();
            $sql = "SELECT * FROM usuarios WHERE (email = ? OR nombre = ?) AND activo = 1";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $identificador, $identificador);
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
            $sql = "SELECT nombre, apellido, email, telefono, direccion, tipo, fecha_registro FROM usuarios WHERE id = ? AND (activo = 1 OR activo = 0)";
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
    function emailExiste($email, $exclude_id = null) {
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
     //cambios
    // Función para eliminar usuario
    public function eliminar($id) {
    $cone = new dConexion();
    $respuesta = false;
    
        try {
            $con = $cone->Conectar();
            $sql = "UPDATE usuarios SET activo = 0 WHERE id = ?";
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
    // En datos/dUsuario.php, agregar estas funciones a la clase dUsuario

public function obtenerCompletoPorId($id) {
    $cone = new dConexion();
    $usuario = null;
    
    try {
        $con = $cone->Conectar();
        $sql = "SELECT * FROM usuarios WHERE id = ?";
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
        echo "Error al obtener usuario completo: " . $exc->getMessage();
    }
    
    return $usuario;
}

// La función eliminar ya debería existir, pero la mejoramos:
public function eliminarCuenta($id) {
    $cone = new dConexion();
    $respuesta = false;
    
    try {
        $con = $cone->Conectar();
        
        // Iniciar transacción para asegurar la integridad de los datos
        mysqli_begin_transaction($con);
        
        // 1. Primero eliminamos registros relacionados en otras tablas
        // (Estas funciones deben crearse en las respectivas clases)
        
        // Eliminar vacunaciones del ganado del usuario
        $this->eliminarVacunacionesPorUsuario($con, $id);
        
        // Eliminar ganado del usuario
        $this->eliminarGanadoPorUsuario($con, $id);
        
        // Eliminar ventas relacionadas con el usuario
        $this->eliminarVentasPorUsuario($con, $id);
        
        // 2. Finalmente eliminamos el usuario
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        $respuesta = mysqli_stmt_execute($stmt);
        if ($respuesta) {
            mysqli_commit($con);
        } else {
            mysqli_rollback($con);
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    } catch (Exception $exc) {
        if (isset($con)) {
            mysqli_rollback($con);
        }
        echo "Error al eliminar usuario: " . $exc->getMessage();
    }
    
    return $respuesta;
}

// Funciones auxiliares para eliminar datos relacionados
private function eliminarVacunacionesPorUsuario($con, $usuario_id) {
    // Primero obtenemos todos los IDs del ganado del usuario
    $ganado_ids = [];
    $sql = "SELECT id FROM ganado WHERE usuario_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $ganado_ids[] = $row['id'];
    }
    mysqli_stmt_close($stmt);
    
    // Eliminamos las vacunaciones de cada animal
    if (!empty($ganado_ids)) {
        $placeholders = implode(',', array_fill(0, count($ganado_ids), '?'));
        $sql = "DELETE FROM vacunas WHERE ganado_id IN ($placeholders)";
        $stmt = mysqli_prepare($con, $sql);
        
        // Dinámicamente bindeamos los parámetros
        $types = str_repeat('i', count($ganado_ids));
        $stmt->bind_param($types, ...$ganado_ids);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

private function eliminarGanadoPorUsuario($con, $usuario_id) {
    $sql = "DELETE FROM ganado WHERE usuario_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

private function eliminarVentasPorUsuario($con, $usuario_id) {
    // Eliminar ventas donde el usuario es comprador o vendedor
    $sql = "DELETE FROM ventas WHERE comprador_id = ? OR vendedor_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $usuario_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
    public function listarUsuarios($con) {
        $usuarios = array();
        $sql = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
        $result = mysqli_query($con, $sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }
    function mostrarUsuarios() {
        try {
            $cone = new dConexion();
            $con = $cone->Conectar();
            $sql = "SELECT * FROM usuarios ORDER BY fecha_registro DESC";
            $result = mysqli_query($con, $sql);
            return $result;
        } catch (Exception $exc) {
            echo "Error al obtener usuarios: " . $exc->getMessage();
        }
        return null;
    }
    
    public function obtenerTodosRol() {
        $cone = new dConexion();
        $roles = [];
        
        try {
            $con = $cone->Conectar();
            $query = "SELECT 
                        r.id,
                        r.nombre,
                        r.descripcion,
                        r.fecha_creacion,
                        r.fecha_modificacion,
                        COUNT(u.id) as total_usuarios
                      FROM roles r
                      LEFT JOIN usuarios u ON r.id = u.rol_id
                      GROUP BY r.id, r.nombre, r.descripcion, r.fecha_creacion, r.fecha_modificacion
                      ORDER BY r.nombre ASC";
            
            $result = mysqli_query($con, $query);
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $roles[] = $row;
                }
            }
            
            mysqli_close($con);
        } catch (Exception $exc) {
            error_log("Error al obtener roles: " . $exc->getMessage());
        }
        
        return $roles;
    }

    public function obtenerPorIdRol($id) {
        $cone = new dConexion();
        $rol = null;
        
        try {
            $con = $cone->Conectar();
            $query = "SELECT * FROM roles WHERE id = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $rol = mysqli_fetch_assoc($result);
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (Exception $e) {
            error_log("Error al obtener rol: " . $e->getMessage());
        }
        
        return $rol;
    }

    public function crearRol($nombre, $descripcion = null) {
        $cone = new dConexion();
        
        try {
            $con = $cone->Conectar();
            
            // Verificar si ya existe
            if ($this->existePorNombreRol($nombre, $con)) {
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => 'Ya existe un rol con ese nombre'
                ];
            }

            $query = "INSERT INTO roles (nombre, descripcion, fecha_creacion) 
                      VALUES (?, ?, NOW())";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ss", $nombre, $descripcion);
            
            if (mysqli_stmt_execute($stmt)) {
                $id = mysqli_insert_id($con);
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                
                return [
                    'exito' => true,
                    'mensaje' => 'Rol creado exitosamente',
                    'id' => $id
                ];
            } else {
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                
                return [
                    'exito' => false,
                    'mensaje' => 'Error al crear el rol'
                ];
            }
        } catch (Exception $e) {
            if (isset($con)) {
                mysqli_close($con);
            }
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    public function actualizarRol($id, $nombre, $descripcion = null) {
        $cone = new dConexion();
        
        try {
            $con = $cone->Conectar();
            
            // Verificar que el rol existe
            if (!$this->obtenerPorIdRol($id)) {
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => 'El rol no existe'
                ];
            }

            // Verificar si el nombre ya existe en otro rol
            $query = "SELECT id FROM roles WHERE LOWER(nombre) = LOWER(?) AND id != ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_fetch_assoc($result)) {
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => 'Ya existe otro rol con ese nombre'
                ];
            }
            mysqli_stmt_close($stmt);

            $query = "UPDATE roles 
                      SET nombre = ?, 
                          descripcion = ?, 
                          fecha_modificacion = NOW() 
                      WHERE id = ?";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $nombre, $descripcion, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                return [
                    'exito' => true,
                    'mensaje' => 'Rol actualizado exitosamente'
                ];
            } else {
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => 'Error al actualizar el rol'
                ];
            }
        } catch (Exception $e) {
            if (isset($con)) {
                mysqli_close($con);
            }
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    public function eliminarRol($id) {
        $cone = new dConexion();
        
        try {
            $con = $cone->Conectar();
            
            // Verificar que el rol existe
            $rol = $this->obtenerPorIdRol($id);
            if (!$rol) {
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => 'El rol no existe'
                ];
            }

            // Verificar si hay usuarios asignados
            $query = "SELECT COUNT(*) as total FROM usuarios WHERE rol_id = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultado = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if ($resultado['total'] > 0) {
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => "No se puede eliminar el rol porque tiene {$resultado['total']} usuario(s) asignado(s)"
                ];
            }

            $query = "DELETE FROM roles WHERE id = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                return [
                    'exito' => true,
                    'mensaje' => 'Rol eliminado exitosamente'
                ];
            } else {
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => 'Error al eliminar el rol'
                ];
            }
        } catch (Exception $e) {
            if (isset($con)) {
                mysqli_close($con);
            }
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    private function existePorNombreRol($nombre, $con) {
        $query = "SELECT COUNT(*) as total FROM roles WHERE LOWER(nombre) = LOWER(?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $nombre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resultado = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        return $resultado['total'] > 0;
    }

    public function obtenerEstadisticasRol() {
        $cone = new dConexion();
        $estadisticas = null;
        
        try {
            $con = $cone->Conectar();
            $query = "SELECT 
                        COUNT(DISTINCT r.id) as total_roles,
                        COUNT(u.id) as total_usuarios_con_rol,
                        (SELECT COUNT(*) FROM usuarios WHERE rol_id IS NULL) as usuarios_sin_rol
                      FROM roles r
                      LEFT JOIN usuarios u ON r.id = u.rol_id";
            
            $result = mysqli_query($con, $query);
            $estadisticas = mysqli_fetch_assoc($result);
            
            mysqli_close($con);
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
        }
        
        return $estadisticas;
    }
    
    
/**
 * Asignar un rol a un usuario
 */
public function asignarRol($usuario_id, $rol_id) {
    $cone = new dConexion();
    $respuesta = false;
    
    try {
        $con = $cone->Conectar();
        
        // Si rol_id es null, se quita el rol
        if ($rol_id === null || $rol_id === '') {
            $sql = "UPDATE usuarios SET rol_id = NULL WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        } else {
            // Verificar que el rol existe
            $sqlVerificar = "SELECT id FROM roles WHERE id = ?";
            $stmtVerificar = mysqli_prepare($con, $sqlVerificar);
            mysqli_stmt_bind_param($stmtVerificar, "i", $rol_id);
            mysqli_stmt_execute($stmtVerificar);
            $resultVerificar = mysqli_stmt_get_result($stmtVerificar);
            
            if (!mysqli_fetch_assoc($resultVerificar)) {
                mysqli_stmt_close($stmtVerificar);
                mysqli_close($con);
                return [
                    'exito' => false,
                    'mensaje' => 'El rol especificado no existe'
                ];
            }
            mysqli_stmt_close($stmtVerificar);
            
            // Asignar el rol
            $sql = "UPDATE usuarios SET rol_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $rol_id, $usuario_id);
        }
        
        $respuesta = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        
        if ($respuesta) {
            return [
                'exito' => true,
                'mensaje' => 'Rol asignado exitosamente'
            ];
        } else {
            return [
                'exito' => false,
                'mensaje' => 'Error al asignar el rol'
            ];
        }
        
    } catch (Exception $exc) {
        if (isset($con)) {
            mysqli_close($con);
        }
        return [
            'exito' => false,
            'mensaje' => 'Error: ' . $exc->getMessage()
        ];
    }
}

/**
 * Obtener usuarios con sus roles
 */
public function obtenerUsuariosConRoles() {
    $cone = new dConexion();
    $usuarios = [];
    
    try {
        $con = $cone->Conectar();
        $sql = "SELECT 
                    u.id,
                    u.nombre,
                    u.apellido,
                    u.email,
                    u.telefono,
                    u.tipo,
                    u.activo,
                    u.fecha_registro,
                    r.id as rol_id,
                    r.nombre as rol_nombre
                FROM usuarios u
                LEFT JOIN roles r ON u.rol_id = r.id
                ORDER BY u.fecha_registro DESC";
        
        $result = mysqli_query($con, $sql);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }
        
        mysqli_free_result($result);
        mysqli_close($con);
    } catch (Exception $exc) {
        echo "Error al obtener usuarios con roles: " . $exc->getMessage();
    }
    
    return $usuarios;
}
    
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