CREATE DATABASE whatsapp;
USE whatsapp;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    nombre_real VARCHAR(100) NOT NULL, 
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL 
);

-- Tabla de solicitudes de amistad
CREATE TABLE solicitudes_amistad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_remitente INT NOT NULL, -- Sender ID
    id_receptor INT NOT NULL, -- Receiver ID
    estado ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente', 
    FOREIGN KEY (id_remitente) REFERENCES usuarios(id),
    FOREIGN KEY (id_receptor) REFERENCES usuarios(id)
);

-- Tabla de amistades
CREATE TABLE amistades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario1 INT NOT NULL,
    id_usuario2 INT NOT NULL,
    FOREIGN KEY (id_usuario1) REFERENCES usuarios(id),
    FOREIGN KEY (id_usuario2) REFERENCES usuarios(id)
);

-- Tabla de mensajes
CREATE TABLE mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_remitente INT NOT NULL, 
    id_receptor INT NOT NULL, 
    mensaje VARCHAR(250) NOT NULL, 
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_remitente) REFERENCES usuarios(id),
    FOREIGN KEY (id_receptor) REFERENCES usuarios(id)
);
