<?php
/**
 * Capa de Datos - Contactos
 * Maneja las operaciones CRUD con la tabla de contactos
 */

require_once __DIR__ . '/../config/config.php';

class dContacto {
    
    private $conexion;
    
    public function __construct() {
        $this->conectar();
    }
    
    /**
     * Establece conexión con la base de datos
     */
    private function conectar() {
        $this->conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->conexion->connect_error) {
            die('Error de conexión: ' . $this->conexion->connect_error);
        }
        
        $this->conexion->set_charset(DB_CHARSET);
    }
    
    /**
     * Crear tabla de contactos si no existe
     */
    public function crearTabla() {
        $sql = "
            CREATE TABLE IF NOT EXISTS contactos (
                id INT PRIMARY KEY AUTO_INCREMENT,
                nombre VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                telefono VARCHAR(20),
                asunto VARCHAR(50) NOT NULL,
                mensaje LONGTEXT NOT NULL,
                usuario_id INT,
                ip VARCHAR(45),
                navegador TEXT,
                leido BOOLEAN DEFAULT FALSE,
                respondido BOOLEAN DEFAULT FALSE,
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                fecha_lectura TIMESTAMP NULL,
                fecha_respuesta TIMESTAMP NULL,
                INDEX idx_email (email),
                INDEX idx_usuario_id (usuario_id),
                INDEX idx_fecha_creacion (fecha_creacion),
                INDEX idx_leido (leido),
                FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
        
        return $this->conexion->query($sql);
    }
    
    /**
     * Guarda un mensaje de contacto en la base de datos
     * 
     * @param array $datos Array con los datos del contacto
     *              - nombre: string
     *              - email: string
     *              - telefono: string
     *              - asunto: string
     *              - mensaje: string
     *              - usuario_id: int (opcional)
     *              - ip: string
     *              - navegador: string
     * @return int|false ID del contacto guardado o false en caso de error
     */
    public function guardarContacto($datos) {
        $sql = "
            INSERT INTO contactos 
            (nombre, email, telefono, asunto, mensaje, usuario_id, ip, navegador)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
        
        $stmt = $this->conexion->prepare($sql);
        
        if (!$stmt) {
            error_log('Error en preparación: ' . $this->conexion->error);
            return false;
        }
        
        $usuario_id = $datos['usuario_id'] ?? null;
        
        $stmt->bind_param(
            'sssssiss',
            $datos['nombre'],
            $datos['email'],
            $datos['telefono'],
            $datos['asunto'],
            $datos['mensaje'],
            $usuario_id,
            $datos['ip'],
            $datos['navegador']
        );
        
        if ($stmt->execute()) {
            $id = $this->conexion->insert_id;
            $stmt->close();
            return $id;
        } else {
            error_log('Error en ejecución: ' . $stmt->error);
            $stmt->close();
            return false;
        }
    }
    
    /**
     * Obtiene todos los contactos con opción de paginación
     * 
     * @param int $pagina Número de página (por defecto 1)
     * @param int $por_pagina Registros por página (por defecto 20)
     * @param bool $solo_no_leidos Si true, solo obtiene contactos no leídos
     * @return array Array con datos de contactos y paginación
     */
    public function obtenerContactos($pagina = 1, $por_pagina = 20, $solo_no_leidos = false) {
        $offset = ($pagina - 1) * $por_pagina;
        
        // Obtener total de registros
        $sql_count = "SELECT COUNT(*) as total FROM contactos";
        if ($solo_no_leidos) {
            $sql_count .= " WHERE leido = FALSE";
        }
        
        $resultado_count = $this->conexion->query($sql_count);
        $total = $resultado_count->fetch_assoc()['total'];
        
        // Obtener contactos
        $sql = "
            SELECT 
                id, nombre, email, telefono, asunto, mensaje,
                usuario_id, ip, navegador, leido, respondido,
                fecha_creacion, fecha_lectura, fecha_respuesta
            FROM contactos
        ";
        
        if ($solo_no_leidos) {
            $sql .= " WHERE leido = FALSE";
        }
        
        $sql .= " ORDER BY fecha_creacion DESC LIMIT ? OFFSET ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ii', $por_pagina, $offset);
        $stmt->execute();
        
        $contactos = [];
        $resultado = $stmt->get_result();
        
        while ($fila = $resultado->fetch_assoc()) {
            $contactos[] = $fila;
        }
        
        $stmt->close();
        
        return [
            'contactos' => $contactos,
            'total' => $total,
            'pagina' => $pagina,
            'por_pagina' => $por_pagina,
            'total_paginas' => ceil($total / $por_pagina)
        ];
    }
    
    /**
     * Obtiene un contacto específico por ID
     * 
     * @param int $id ID del contacto
     * @return array|null Datos del contacto o null si no existe
     */
    public function obtenerContactoPorId($id) {
        $sql = "
            SELECT * FROM contactos WHERE id = ?
        ";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $contacto = $resultado->fetch_assoc();
        
        $stmt->close();
        
        return $contacto;
    }
    
    /**
     * Marca un contacto como leído
     * 
     * @param int $id ID del contacto
     * @return bool True si se actualizó correctamente
     */
    public function marcarComoLeido($id) {
        $sql = "
            UPDATE contactos 
            SET leido = TRUE, fecha_lectura = NOW() 
            WHERE id = ?
        ";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $resultado = $stmt->execute();
        
        $stmt->close();
        
        return $resultado;
    }
    
    /**
     * Marca un contacto como respondido
     * 
     * @param int $id ID del contacto
     * @return bool True si se actualizó correctamente
     */
    public function marcarComoRespondido($id) {
        $sql = "
            UPDATE contactos 
            SET respondido = TRUE, fecha_respuesta = NOW() 
            WHERE id = ?
        ";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $resultado = $stmt->execute();
        
        $stmt->close();
        
        return $resultado;
    }
    
    /**
     * Obtiene el total de contactos sin leer
     * 
     * @return int Total de contactos no leídos
     */
    public function obtenerTotalSinLeer() {
        $sql = "SELECT COUNT(*) as total FROM contactos WHERE leido = FALSE";
        
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        
        return $fila['total'] ?? 0;
    }
    
    /**
     * Obtiene contactos por email
     * 
     * @param string $email Email a buscar
     * @return array Array con los contactos encontrados
     */
    public function obtenerContactosPorEmail($email) {
        $sql = "
            SELECT * FROM contactos 
            WHERE email = ? 
            ORDER BY fecha_creacion DESC
        ";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        
        $contactos = [];
        $resultado = $stmt->get_result();
        
        while ($fila = $resultado->fetch_assoc()) {
            $contactos[] = $fila;
        }
        
        $stmt->close();
        
        return $contactos;
    }
    
    /**
     * Obtiene contactos por usuario ID
     * 
     * @param int $usuario_id ID del usuario
     * @return array Array con los contactos del usuario
     */
    public function obtenerContactosPorUsuario($usuario_id) {
        $sql = "
            SELECT * FROM contactos 
            WHERE usuario_id = ? 
            ORDER BY fecha_creacion DESC
        ";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        
        $contactos = [];
        $resultado = $stmt->get_result();
        
        while ($fila = $resultado->fetch_assoc()) {
            $contactos[] = $fila;
        }
        
        $stmt->close();
        
        return $contactos;
    }
    
    /**
     * Elimina un contacto
     * 
     * @param int $id ID del contacto a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function eliminarContacto($id) {
        $sql = "DELETE FROM contactos WHERE id = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $resultado = $stmt->execute();
        
        $stmt->close();
        
        return $resultado;
    }
    
    /**
     * Busca contactos por término (nombre, email, asunto, mensaje)
     * 
     * @param string $termino Término a buscar
     * @return array Array con los contactos encontrados
     */
    public function buscarContactos($termino) {
        $termino = '%' . $this->conexion->real_escape_string($termino) . '%';
        
        $sql = "
            SELECT * FROM contactos 
            WHERE nombre LIKE ? 
               OR email LIKE ? 
               OR asunto LIKE ? 
               OR mensaje LIKE ?
            ORDER BY fecha_creacion DESC
            LIMIT 100
        ";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param('ssss', $termino, $termino, $termino, $termino);
        $stmt->execute();
        
        $contactos = [];
        $resultado = $stmt->get_result();
        
        while ($fila = $resultado->fetch_assoc()) {
            $contactos[] = $fila;
        }
        
        $stmt->close();
        
        return $contactos;
    }
    
    /**
     * Obtiene estadísticas de contactos
     * 
     * @return array Array con estadísticas
     */
    public function obtenerEstadisticas() {
        $sql = "
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN leido = FALSE THEN 1 ELSE 0 END) as sin_leer,
                SUM(CASE WHEN respondido = TRUE THEN 1 ELSE 0 END) as respondidos,
                SUM(CASE WHEN asunto = 'consulta' THEN 1 ELSE 0 END) as consultas,
                SUM(CASE WHEN asunto = 'problema' THEN 1 ELSE 0 END) as problemas,
                SUM(CASE WHEN asunto = 'sugerencia' THEN 1 ELSE 0 END) as sugerencias,
                SUM(CASE WHEN asunto = 'soporte' THEN 1 ELSE 0 END) as soportes
            FROM contactos
        ";
        
        $resultado = $this->conexion->query($sql);
        
        return $resultado->fetch_assoc();
    }
    
    /**
     * Cierra la conexión
     */
    public function cerrar() {
        $this->conexion->close();
    }
}
