CREATE DATABASE IF NOT EXISTS sistemas_pratos
USE sistemas_pratos;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS pratos (
    id_prato INT AUTO_INCREMENT PRIMARY KEY,
    nome_prato VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
);
