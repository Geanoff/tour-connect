CREATE DATABASE IF NOT EXISTS `tour-connect`;
USE `tour-connect`;

-- Salva caminho da imagem 
CREATE TABLE imagens (
    imagem_id INT AUTO_INCREMENT PRIMARY KEY,
-- Caminho da imagem
    caminho VARCHAR(255) NOT NULL,
-- Controle de data
    data_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Criação da tabela passeios
CREATE TABLE passeios (
    passeio_id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao_curta TEXT NOT NULL,
    descricao TEXT NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    duracao VARCHAR(100) NOT NULL,
    dificuldade VARCHAR(50) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    imagem_id INT NOT NULL,  
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela guias
CREATE TABLE guias (
    guia_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    especialidade VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Relacionamento entre os passeios e guias (muitos-para-muitos)
CREATE TABLE passeio_guias (
    passeio_id INT NOT NULL,
    guia_id INT NOT NULL,
    PRIMARY KEY (`passeio_id`, `guia_id`),
    FOREIGN KEY (`passeio_id`) REFERENCES `passeios`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`guia_id`) REFERENCES `guias`(`id`) ON DELETE CASCADE
);

-- Criação da tabela usuários
CREATE TABLE usuarios (
    usuario_id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    cpf CHAR(11) NOT NULL UNIQUE,     
    telefone VARCHAR(20) NOT NULL,    
    senha VARCHAR(255) NOT NULL,      
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de agendamentos
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    usuario_id INT NOT NULL,
    passeio_id INT NOT NULL,
    guia_id INT NOT NULL,

    data_passeio DATE NOT NULL,
    horario TIME NOT NULL,

    preco DECIMAL(10,2) NOT NULL, 
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (passeio_id) REFERENCES passeios(id) ON DELETE CASCADE,
    FOREIGN KEY (guia_id) REFERENCES guias(id) ON DELETE CASCADE
);

