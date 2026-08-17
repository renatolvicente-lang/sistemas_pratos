CREATE DATABASE sistemas_pratos;

use sistemas_pratos;

CREATE table usuarios(
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL

);

CREATE table pratos(
    id_prato INT PRIMARY KEY AUTO_INCREMENT,
    nome_prato VARCHAR(255) NOT NULL,
    descrição TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    id_usuario INT,
    foreign KEY (id_usuario) references usuarios(id_usuario)
);