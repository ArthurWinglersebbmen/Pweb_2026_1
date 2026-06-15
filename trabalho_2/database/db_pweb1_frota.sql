CREATE DATABASE IF NOT EXISTS db_pweb1_frota;
USE db_pweb1_frota;

-- Tabela Padrão do Sistema (Obrigatória)
CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

-- Inserindo o usuário padrão para testes exigido pelo professor
INSERT INTO usuario (nome, telefone, email, login, senha) 
VALUES ('Administrador', '(00) 00000-0000', 'admin@sistema.com', 'admin', '123');

-- Tabela do CRUD 1: Veículos
CREATE TABLE IF NOT EXISTS veiculo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL UNIQUE,
    modelo VARCHAR(100) NOT NULL,
    ano_fabricacao INT NOT NULL,
    capacidade_passageiros INT NOT NULL
);

-- Tabela do CRUD 2: Motoristas
CREATE TABLE IF NOT EXISTS motorista (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_motorista VARCHAR(100) NOT NULL,
    numero_cnh VARCHAR(20) NOT NULL UNIQUE,
    categoria_cnh VARCHAR(5) NOT NULL,
    validade_exame_medico DATE NOT NULL
);

-- Tabela do CRUD 3: Manutenções
CREATE TABLE IF NOT EXISTS manutencao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    data_manutencao DATE NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (veiculo_id) REFERENCES veiculo(id) ON DELETE CASCADE
);