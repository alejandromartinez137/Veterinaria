-- Base de datos para el "Expediente Maestro del Santuario de Mascotas"
CREATE DATABASE IF NOT EXISTS santuario_mascotas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE santuario_mascotas;

CREATE TABLE IF NOT EXISTS Mascotas (
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    nombre                VARCHAR(100) NOT NULL,
    especie               VARCHAR(60)  NOT NULL,
    raza                  VARCHAR(60)  NOT NULL,
    edad                  INT          NOT NULL,
    peso_actual           DECIMAL(6,2) NOT NULL,
    senas_fisicas         VARCHAR(150) NOT NULL,
    nombre_responsable    VARCHAR(100) NOT NULL,
    telefono_emergencia   VARCHAR(20)  NOT NULL,
    fecha_registro        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
