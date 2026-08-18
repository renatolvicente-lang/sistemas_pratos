CREATE DATABASE IF NOT EXISTS sistemas_pratos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sistemas_pratos;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pratos (
    id_prato INT AUTO_INCREMENT PRIMARY KEY,
    nome_prato VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    id_usuario INT NOT NULL,
    CONSTRAINT fk_pratos_usuarios
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios (id_usuario)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;
