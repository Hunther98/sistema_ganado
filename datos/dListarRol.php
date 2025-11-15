<?php
require_once __DIR__ . 'dConexion.php';
class dListarRol {
    private $dConexion;

    public function __construct($dConexion) {
        $this->dConexion = $dConexion;
    }

    /**
     * Obtener todos los roles
     */
    public function obtenerTodosRol() {
        try {
            $query = "SELECT 
                        r.id,
                        r.nombre,
                        r.descripcion,
                        r.fecha_creacion,
                        r.fecha_modificacion,
                        COUNT(u.id) as total_usuarios
                      FROM roles r
                      LEFT JOIN usuarios u ON r.rol_id = u.id
                      GROUP BY r.id, r.nombre, r.descripcion, r.fecha_creacion, r.fecha_modificacion
                      ORDER BY r.nombre ASC";
            
            $result = mysqli_query($this->dConexion, $query);
            
            if (!$result) {
                error_log("Error al obtener roles: " . mysqli_error($this->dConexion));
                return [];
            }
            
            $roles = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $roles[] = $row;
            }
            
            return $roles;
        } catch (Exception $e) {
            error_log("Error al obtener roles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener un rol por ID
     */
    public function obtenerPorIdRol($id) {
        try {
            $query = "SELECT * FROM roles WHERE id = ?";
            $stmt = mysqli_prepare($this->dConexion, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            return mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            error_log("Error al obtener rol: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear un nuevo rol
     */
    public function crearRol($nombre, $descripcion = null) {
        try {
            // Verificar si ya existe
            if ($this->existePorNombreRol($nombre)) {
                return [
                    'exito' => false,
                    'mensaje' => 'Ya existe un rol con ese nombre'
                ];
            }

            $query = "INSERT INTO roles (nombre, descripcion, fecha_creacion) 
                      VALUES (?, ?, NOW())";
            
            $stmt = mysqli_prepare($this->dConexion, $query);
            mysqli_stmt_bind_param($stmt, "ss", $nombre, $descripcion);
            
            if (mysqli_stmt_execute($stmt)) {
                return [
                    'exito' => true,
                    'mensaje' => 'Rol creado exitosamente',
                    'id' => mysqli_insert_id($this->dConexion)
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'Error al crear el rol: ' . mysqli_error($this->dConexion)
                ];
            }
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    /**
     * Actualizar un rol
     */
    public function actualizarRol($id, $nombre, $descripcion = null) {
        try {
            // Verificar que el rol existe
            if (!$this->obtenerPorIdRol($id)) {
                return [
                    'exito' => false,
                    'mensaje' => 'El rol no existe'
                ];
            }

            // Verificar si el nombre ya existe en otro rol
            $query = "SELECT id FROM roles WHERE LOWER(nombre) = LOWER(?) AND id != ?";
            $stmt = mysqli_prepare($this->dConexion, $query);
            mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_fetch_assoc($result)) {
                return [
                    'exito' => false,
                    'mensaje' => 'Ya existe otro rol con ese nombre'
                ];
            }

            $query = "UPDATE roles 
                      SET nombre = ?, 
                          descripcion = ?, 
                          fecha_modificacion = NOW() 
                      WHERE id = ?";
            
            $stmt = mysqli_prepare($this->dConexion, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $nombre, $descripcion, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                return [
                    'exito' => true,
                    'mensaje' => 'Rol actualizado exitosamente'
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'Error al actualizar el rol'
                ];
            }
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    /**
     * Eliminar un rol
     */
    public function eliminarRol($id) {
        try {
            // Verificar que el rol existe
            $rol = $this->obtenerPorIdRol($id);
            if (!$rol) {
                return [
                    'exito' => false,
                    'mensaje' => 'El rol no existe'
                ];
            }

            // Verificar si hay usuarios asignados
            $query = "SELECT COUNT(*) as total FROM usuarios WHERE rol_id = ?";
            $stmt = mysqli_prepare($this->dConexion, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultado = mysqli_fetch_assoc($result);

            if ($resultado['total'] > 0) {
                return [
                    'exito' => false,
                    'mensaje' => "No se puede eliminar el rol porque tiene {$resultado['total']} usuario(s) asignado(s)"
                ];
            }

            $query = "DELETE FROM roles WHERE id = ?";
            $stmt = mysqli_prepare($this->dConexion, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            if (mysqli_stmt_execute($stmt)) {
                return [
                    'exito' => true,
                    'mensaje' => 'Rol eliminado exitosamente'
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'Error al eliminar el rol'
                ];
            }
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    /**
     * Verificar si existe un rol con el nombre dado
     */
    private function existePorNombreRol($nombre) {
        $query = "SELECT COUNT(*) as total FROM roles WHERE LOWER(nombre) = LOWER(?)";
        $stmt = mysqli_prepare($this->dConexion, $query);
        mysqli_stmt_bind_param($stmt, "s", $nombre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resultado = mysqli_fetch_assoc($result);
        
        return $resultado['total'] > 0;
    }

    /**
     * Obtener estadísticas de roles
     */
    public function obtenerEstadisticasRol() {
        try {
            $query = "SELECT 
                        COUNT(DISTINCT r.id) as total_roles,
                        COUNT(u.id) as total_usuarios_con_rol,
                        (SELECT COUNT(*) FROM usuarios WHERE rol_id IS NULL) as usuarios_sin_rol
                      FROM roles r
                      LEFT JOIN usuarios u ON r.id = u.rol_id";
            
            $result = mysqli_query($this->dConexion, $query);
            return mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return null;
        }
    }
}
?>