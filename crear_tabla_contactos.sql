-- ========================================================================
-- Tabla de Contactos
-- Script SQL para crear la tabla de mensajes de contacto
-- ========================================================================

-- Crear tabla si no existe
CREATE TABLE IF NOT EXISTS contactos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL COMMENT 'Nombre del remitente',
    email VARCHAR(100) NOT NULL COMMENT 'Email del remitente',
    telefono VARCHAR(20) COMMENT 'Teléfono del remitente (opcional)',
    asunto VARCHAR(50) NOT NULL COMMENT 'Asunto del mensaje',
    mensaje LONGTEXT NOT NULL COMMENT 'Contenido del mensaje',
    usuario_id INT COMMENT 'ID del usuario si está autenticado',
    ip VARCHAR(45) COMMENT 'IP del cliente',
    navegador TEXT COMMENT 'User-Agent del navegador',
    leido BOOLEAN DEFAULT FALSE COMMENT 'Si el administrador ya leyó el mensaje',
    respondido BOOLEAN DEFAULT FALSE COMMENT 'Si ya se respondió al mensaje',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación del contacto',
    fecha_lectura TIMESTAMP NULL COMMENT 'Fecha en que se leyó el mensaje',
    fecha_respuesta TIMESTAMP NULL COMMENT 'Fecha en que se respondió',
    
    -- Índices para optimización
    INDEX idx_email (email),
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_fecha_creacion (fecha_creacion),
    INDEX idx_leido (leido),
    INDEX idx_asunto (asunto),
    
    -- Clave foránea (si la tabla usuarios existe)
    FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabla para almacenar mensajes de contacto de usuarios';

-- ========================================================================
-- Crear tabla de auditorías si no existe (opcional pero recomendado)
-- ========================================================================

CREATE TABLE IF NOT EXISTS auditorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT COMMENT 'ID del usuario que realizó la acción',
    accion VARCHAR(100) NOT NULL COMMENT 'Tipo de acción realizada',
    detalles TEXT COMMENT 'Detalles adicionales de la acción',
    tabla VARCHAR(50) COMMENT 'Tabla afectada',
    id_registro INT COMMENT 'ID del registro afectado',
    ip VARCHAR(45) COMMENT 'IP del cliente',
    navegador TEXT COMMENT 'User-Agent del navegador',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de la acción',
    
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_fecha (fecha),
    INDEX idx_accion (accion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabla para auditoría de acciones del sistema';

-- ========================================================================
-- Insertar datos de ejemplo en contactos (opcional)
-- ========================================================================

-- INSERT INTO contactos (nombre, email, telefono, asunto, mensaje, ip, navegador, leido)
-- VALUES 
-- ('Juan Pérez', 'juan@example.com', '+58 (0414) 123-4567', 'consulta', 'Tengo una pregunta sobre...', '192.168.1.1', 'Mozilla/5.0...', FALSE),
-- ('María García', 'maria@example.com', '', 'sugerencia', 'Me gustaría sugerir...', '192.168.1.2', 'Mozilla/5.0...', TRUE);

-- ========================================================================
-- Crear vista para contactos pendientes (opcional)
-- ========================================================================

CREATE OR REPLACE VIEW v_contactos_pendientes AS
SELECT 
    id,
    nombre,
    email,
    asunto,
    DATE_FORMAT(fecha_creacion, '%d/%m/%Y %H:%i') AS fecha_contacto,
    DATEDIFF(NOW(), fecha_creacion) AS dias_sin_leer
FROM contactos
WHERE leido = FALSE
ORDER BY fecha_creacion DESC;

-- ========================================================================
-- Comentarios finales
-- ========================================================================
-- Para usar la tabla:
--
-- 1. Crear tabla: Ejecutar este script en tu base de datos
-- 2. Instanciar: $dContacto = new dContacto();
-- 3. Crear tabla: $dContacto->crearTabla();
-- 4. Guardar contacto: $dContacto->guardarContacto($datos);
-- 5. Obtener contactos: $contactos = $dContacto->obtenerContactos();
--
-- Para eliminar la tabla si es necesario:
-- DROP TABLE IF EXISTS contactos;
