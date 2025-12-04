-- Base de datos para sistema de venta de ganado
CREATE DATABASE IF NOT EXISTS venta_ganado;
USE venta_ganado;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(15),
    direccion TEXT,
    tipo ENUM('comprador', 'vendedor', 'admin') DEFAULT 'comprador',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de ganado
CREATE TABLE IF NOT EXISTS ganado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    raza VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    peso DECIMAL(10,2) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    ubicacion VARCHAR(255),
    latitud DECIMAL(10,8),
    longitud DECIMAL(11,8),
    estado ENUM('disponible', 'vendido', 'reservado') DEFAULT 'disponible',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_raza (raza),
    INDEX idx_estado (estado)
);

-- Tabla de registro de vacunación
CREATE TABLE IF NOT EXISTS vacunaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
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

-- Insertar datos de ejemplo
INSERT INTO usuarios (nombre, apellido, email, password, telefono, direccion, tipo) VALUES
('Admin', 'Sistema', 'admin@ventaganado.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123456789', 'Dirección principal', 'admin'),
('Juan', 'Pérez', 'juan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '987654321', 'Hacienda Santa María', 'vendedor'),
('María', 'González', 'maria@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '555123456', 'Finca La Esperanza', 'vendedor');

INSERT INTO ganado (usuario_id, nombre, descripcion, raza, edad, peso, precio, imagen, ubicacion, latitud, longitud) VALUES
(2, 'Brahman Rojo', 'Toro Brahman Rojo de excelente pedigree, certificado', 'Brahman', 4, 850.50, 2500.00, 'brahman_rojo.jpg', 'Hacienda Santa María, km 25 carretera norte', 14.634915, -90.506882),
(2, 'Angus Negro', 'Vaca Angus preñada, primer parto', 'Angus', 3, 600.75, 1800.00, 'angus_negro.jpg', 'Hacienda Santa María, km 25 carretera norte', 14.634915, -90.506882),
(3, 'Holstein', 'Vaca lechera Holstein, alta producción', 'Holstein', 5, 700.25, 2200.00, 'holstein.jpg', 'Finca La Esperanza, valle de San Miguel', 14.589768, -90.551654);

INSERT INTO vacunaciones (ganado_id, vacuna, fecha_vacunacion, proxima_vacunacion, observaciones) VALUES
(1, 'Fiebre Aftosa', '2024-01-15', '2024-07-15', 'Aplicada por médico veterinario certificado'),
(1, 'Brucelosis', '2023-11-20', '2024-11-20', 'Vacuna RB51'),
(2, 'Fiebre Aftosa', '2024-01-10', '2024-07-10', 'Sin reacciones adversas'),
(3, 'Fiebre Aftosa', '2024-01-05', '2024-07-05', 'Normal');