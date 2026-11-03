-- =====================================================
-- Instalación completa - Sistema de Soporte Técnico
-- IPET N.º 363
-- Ejecutar en phpMyAdmin (pestaña SQL)
-- =====================================================

CREATE DATABASE IF NOT EXISTS soporte_tecnico_colegio
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE soporte_tecnico_colegio;

-- -----------------------------------------------------
-- Categorías (problemas tipificados)
-- -----------------------------------------------------
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categorias (id_categoria, nombre) VALUES
(1, 'Falla táctil'),
(2, 'No enciende'),
(3, 'Falla HDMI'),
(4, 'Pérdida de conexión con impresora'),
(5, 'Funcionamiento lento');

-- -----------------------------------------------------
-- Ubicaciones
-- -----------------------------------------------------
CREATE TABLE ubicaciones (
    id_ubicacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO ubicaciones (id_ubicacion, nombre) VALUES
(1, 'Preceptoría'),
(2, 'Laboratorio'),
(3, 'Taller'),
(4, 'Direccion'),
(5, 'Pañol'),
(6, 'SUM'),
(7, 'Aula');

-- -----------------------------------------------------
-- Estados (ciclo de vida del ticket)
-- -----------------------------------------------------
CREATE TABLE estados (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    orden INT NOT NULL DEFAULT 0,
    color VARCHAR(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO estados (id_estado, nombre, orden, color) VALUES
(1, 'Pendiente',       1, '#7d95b0'),
(2, 'En revisión',     2, '#5a82ae'),
(3, 'En reparación',   3, '#3a6a9b'),
(4, 'Solucionado',     4, '#1f4a76');

-- -----------------------------------------------------
-- Usuarios
-- -----------------------------------------------------
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (id_usuario, nombre, usuario, password_hash, rol) VALUES
(1, 'Tapia', 'tapia', '123456', 'Profesor'),
(2, 'Soporte Técnico', 'soporte', '123456', 'Soporte técnico');

-- -----------------------------------------------------
-- Equipos
-- -----------------------------------------------------
CREATE TABLE equipos (
    id_equipo INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) DEFAULT NULL,
    id_ubicacion INT NOT NULL,
    CONSTRAINT fk_equipos_ubicacion
        FOREIGN KEY (id_ubicacion) REFERENCES ubicaciones(id_ubicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO equipos (id_equipo, codigo, nombre, tipo, id_ubicacion) VALUES
(1,  'EQ-001', 'Computadora gris 34',              'Computadora', 2),
(2,  'EQ-002', 'Computadora 29',                   'Computadora', 2),
(3,  'EQ-003', 'Computadora 31',                   'Computadora', 2),
(4,  'EQ-004', 'Computadora 22',                   'Computadora', 2),
(5,  'EQ-005', 'Computadora 6',                    'Computadora', 2),
(6,  'EQ-006', 'Computadora 15',                   'Computadora', 2),
(7,  'EQ-007', 'Computadora 33',                   'Computadora', 2),
(8,  'EQ-008', 'Computadora 35',                   'Computadora', 2),
(9,  'EQ-009', 'Computadora 25',                   'Computadora', 2),
(10, 'EQ-010', 'Tele 1',                           'Televisor',   2),
(11, 'EQ-011', 'Computadora gris de Preceptoría',  'Computadora', 1),
(12, 'EQ-012', 'Computadora de Preceptoría 1',     'Computadora', 1);

-- -----------------------------------------------------
-- Tickets
-- -----------------------------------------------------
CREATE TABLE tickets (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    prioridad VARCHAR(20) NOT NULL DEFAULT 'Media',
    fecha_problema DATETIME DEFAULT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_categoria INT NOT NULL,
    id_ubicacion INT NOT NULL,
    id_equipo INT DEFAULT NULL,
    id_estado INT NOT NULL,
    id_usuario INT NOT NULL,
    
    CONSTRAINT fk_tickets_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    CONSTRAINT fk_tickets_ubicacion FOREIGN KEY (id_ubicacion) REFERENCES ubicaciones(id_ubicacion),
    CONSTRAINT fk_tickets_equipo    FOREIGN KEY (id_equipo)    REFERENCES equipos(id_equipo) ON DELETE SET NULL,
    CONSTRAINT fk_tickets_estado    FOREIGN KEY (id_estado)    REFERENCES estados(id_estado),
    CONSTRAINT fk_tickets_usuario   FOREIGN KEY (id_usuario)   REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tickets 
(id_ticket, titulo, descripcion, prioridad, fecha_problema, fecha_creacion, 
 id_categoria, id_ubicacion, id_equipo, id_estado, id_usuario) 
VALUES
(1,  'Falla táctil - Computadora gris 34',              'La computadora gris 34 presenta falla táctil',                    'Media', '2026-10-27', '2026-10-27 09:00:00', 1, 2, 1,  1, 1),
(2,  'No enciende - Computadora 29',                    'La computadora 29 no enciende',                                   'Alta',  '2026-10-27', '2026-10-27 09:15:00', 2, 2, 2,  1, 1),
(3,  'No enciende - Computadora 31',                    'La computadora 31 no enciende',                                   'Alta',  '2026-10-27', '2026-10-27 09:20:00', 2, 2, 3,  1, 1),
(4,  'No enciende - Computadora 22',                    'La computadora 22 no enciende',                                   'Alta',  '2026-10-27', '2026-10-27 09:25:00', 2, 2, 4,  1, 1),
(5,  'No enciende - Computadora 6',                     'La computadora 6 no enciende',                                    'Alta',  '2026-10-27', '2026-10-27 09:30:00', 2, 2, 5,  1, 1),
(6,  'No enciende - Computadora 15',                    'La computadora 15 no enciende',                                   'Alta',  '2026-10-27', '2026-10-27 09:35:00', 2, 2, 6,  1, 1),
(7,  'No enciende - Computadora 33',                    'La computadora 33 no enciende',                                   'Alta',  '2026-10-27', '2026-10-27 09:40:00', 2, 2, 7,  1, 1),
(8,  'No enciende - Computadora 35',                    'La computadora 35 no enciende',                                   'Alta',  '2026-10-27', '2026-10-27 09:45:00', 2, 2, 8,  1, 1),
(9,  'No enciende - Computadora 25',                    'La computadora 25 no enciende',                                   'Alta',  '2026-10-27', '2026-10-27 09:50:00', 2, 2, 9,  1, 1),
(10, 'Falla HDMI - Tele 1',                             'No funcionan las entradas HDMI 1 y 2',                            'Media', '2026-10-27', '2026-10-27 10:00:00', 3, 2, 10, 1, 1),
(11, 'Pérdida de conexión - Computadora gris Preceptoría', 'Perdió la conexión con la impresora',                          'Media', '2026-10-27', '2026-10-27 10:10:00', 4, 1, 11, 1, 1),
(12, 'Funcionamiento lento - Computadora Preceptoría 1', 'La computadora funciona lentamente y necesita optimización',    'Media', '2026-10-27', '2026-10-27 10:20:00', 5, 1, 12, 1, 1);

-- -----------------------------------------------------
-- Intervenciones (historial)
-- -----------------------------------------------------
CREATE TABLE intervenciones (
    id_intervencion INT AUTO_INCREMENT PRIMARY KEY,
    observacion TEXT,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_ticket INT NOT NULL,
    id_usuario INT NOT NULL,
    id_estado_anterior INT DEFAULT NULL,
    id_estado_nuevo INT NOT NULL,
    
    CONSTRAINT fk_intervenciones_ticket          FOREIGN KEY (id_ticket)          REFERENCES tickets(id_ticket) ON DELETE CASCADE,
    CONSTRAINT fk_intervenciones_usuario         FOREIGN KEY (id_usuario)         REFERENCES usuarios(id_usuario),
    CONSTRAINT fk_intervenciones_estado_anterior FOREIGN KEY (id_estado_anterior) REFERENCES estados(id_estado) ON DELETE SET NULL,
    CONSTRAINT fk_intervenciones_estado_nuevo    FOREIGN KEY (id_estado_nuevo)    REFERENCES estados(id_estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Intervenciones iniciales (creación de cada ticket)
INSERT INTO intervenciones (observacion, fecha, id_ticket, id_usuario, id_estado_anterior, id_estado_nuevo) VALUES
('Ticket creado y asignado', '2026-10-27 09:00:00', 1,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:15:00', 2,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:20:00', 3,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:25:00', 4,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:30:00', 5,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:35:00', 6,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:40:00', 7,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:45:00', 8,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 09:50:00', 9,  1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 10:00:00', 10, 1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 10:10:00', 11, 1, NULL, 1),
('Ticket creado y asignado', '2026-10-27 10:20:00', 12, 1, NULL, 1);

-- Índices útiles
CREATE INDEX idx_tickets_estado ON tickets(id_estado);
CREATE INDEX idx_tickets_fecha  ON tickets(fecha_creacion);
CREATE INDEX idx_intervenciones_ticket ON intervenciones(id_ticket);
