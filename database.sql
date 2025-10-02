CREATE DATABASE IF NOT EXISTS agendamento_system;
USE agendamento_system;

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT NOT NULL COMMENT 'Duração em minutos',
    price DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS professionals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(191) UNIQUE,
    phone VARCHAR(20),
    specialties TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    professional_id INT,
    client_name VARCHAR(255) NOT NULL,
    client_phone VARCHAR(20) NOT NULL,
    client_email VARCHAR(191),
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    is_online BOOLEAN DEFAULT FALSE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (professional_id) REFERENCES professionals(id)
);

CREATE TABLE IF NOT EXISTS business_hours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day_of_week TINYINT NOT NULL COMMENT '0=domingo, 1=segunda, etc',
    open_time TIME NOT NULL,
    close_time TIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(255) NOT NULL,
    api_key VARCHAR(191) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO services (name, description, duration, price) VALUES
('Corte Masculino', 'Corte de cabelo masculino tradicional', 30, 25.00),
('Corte Feminino', 'Corte de cabelo feminino', 45, 35.00),
('Escova', 'Escova e finalização', 60, 40.00),
('Coloração', 'Coloração completa', 120, 80.00),
('Manicure', 'Manicure completa', 45, 20.00);

INSERT INTO professionals (name, email, phone, specialties) VALUES
('João Silva', 'joao@salon.com', '(11) 99999-1111', 'Cortes masculinos, Barba'),
('Maria Santos', 'maria@salon.com', '(11) 99999-2222', 'Cortes femininos, Escova'),
('Ana Costa', 'ana@salon.com', '(11) 99999-3333', 'Coloração, Tratamentos'),
('Pedro Lima', 'pedro@salon.com', '(11) 99999-4444', 'Manicure, Pedicure');

INSERT INTO business_hours (day_of_week, open_time, close_time) VALUES
(1, '09:00:00', '18:00:00'),
(2, '09:00:00', '18:00:00'),
(3, '09:00:00', '18:00:00'),
(4, '09:00:00', '18:00:00'),
(5, '09:00:00', '18:00:00'),
(6, '09:00:00', '16:00:00');

INSERT INTO api_keys (key_name, api_key) VALUES
('Admin Key', 'agendamento_api_key_2024');
