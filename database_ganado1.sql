CREATE DATABASE IF NOT EXISTS venta_ganado;
USE venta_ganado;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usiario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(10) NOT NULL,
    apellido VARCHAR(20) NOT NULL,
    email VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(15) NOT NULL,
    telefono VARCHAR(11),
    direccion TEXT,
    tipo ENUM('comprador', 'vendedor', 'admin') DEFAULT 'comprador',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de ganado
CREATE TABLE IF NOT EXISTS ganado (
    id_ganado INT AUTO_INCREMENT PRIMARY KEY,
    propietario VARCHAR(15) NOT NULL,
    nombre VARCHAR(15) NOT NULL,
    descripcion TEXT,
    raza VARCHAR(10) NOT NULL,
    edad INT NOT NULL,
    peso DECIMAL(10,2) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(25),
    ubicacion VARCHAR(25),
    latitud DECIMAL(10,8),
    longitud DECIMAL(11,8),
    estado ENUM('disponible', 'vendido', 'reservado') DEFAULT 'disponible',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (propietario) REFERENCES usuarios(id),
    INDEX idx_raza (raza),
    INDEX idx_estado (estado)
);

-- Tabla de registro de vacunación
CREATE TABLE IF NOT EXISTS vacunas (
    id_vacuna INT AUTO_INCREMENT PRIMARY KEY,
    ganado_id INT NOT NULL,
    vacuna VARCHAR(100) NOT NULL,
    fecha_vacunacion DATE NOT NULL,
    proxima_vacunacion DATE,
    observaciones TEXT,
    FOREIGN KEY (ganado_id) REFERENCES ganado(id) ON DELETE CASCADE,
    INDEX idx_vacuna (vacuna)
);

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comprador_id INT NOT NULL,
    vendedor_id INT NOT NULL,
    ganado_id INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    precio_venta DECIMAL(10,2) NOT NULL,
    comision DECIMAL(10,2) DEFAULT 0,
    estado ENUM('pendiente', 'completada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (comprador_id) REFERENCES usuarios(id),
    FOREIGN KEY (vendedor_id) REFERENCES usuarios(id),
    FOREIGN KEY (ganado_id) REFERENCES ganado(id),
    INDEX idx_fecha (fecha),
    INDEX idx_estado (estado)
);
-- Tabla de mensajes
CREATE TABLE IF NOT EXISTS mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emisor_id INT NOT NULL,
    receptor_id INT NOT NULL,
    asunto VARCHAR(100),
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (emisor_id) REFERENCES usuarios(id),
    FOREIGN KEY (receptor_id) REFERENCES usuarios(id),
    INDEX idx_emisor (emisor_id),
    INDEX idx_receptor (receptor_id),
    INDEX idx_fecha (fecha)
);