CREATE DATABASE sistemas_pratos;

use sistemas_pratos;

CREATE table usuarios(
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR NOT NULL,
    senha INT NOT NULL

);

CREATE table pratos(
    id_prato INT PRIMARY KEY AUTO_INCREMENT,
    nome_prato VARCHAR NOT NULL,
    descrição TEXT NOT NULL,
    preco FLOAT NOT NULL,
    categoria VARCHAR NOT NULL,
    id_usuario VARCHAR,
    foreign KEY (id_usuario) references usuarios(id)
);