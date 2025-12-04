create database tourconnect;
use tourconnect;

create table usuario(
	id INT AUTO_INCREMENT,
	primary key (id),
	nome VARCHAR(100) not NULL,
	cpf VARCHAR(11) not NULL,
	senha VARCHAR(255) not NULL,
	email VARCHAR(255) not NULL,
	telefone VARCHAR(20) not NULL
);

CREATE TABLE guia(
    id INT AUTO_INCREMENT,
	PRIMARY KEY (id),
    nome VARCHAR(255) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    especialidade VARCHAR(255) NOT NULL
);

CREATE TABLE destino(
    id INT AUTO_INCREMENT,
	PRIMARY KEY (id),
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

create table agendamento(
	id int AUTO_INCREMENT,
	primary key (id),
	nome VARCHAR(100) not NULL,
	horario_inicio DATETIME not NULL,
	horario_saida DATETIME not NULL,
	id_guia INT not NULL,
	id_destino INT not NULL,
	foreign key (id_destino)
		references destino(id),
	foreign key (id_guia)
		references guia(id)
);

create table usuario_agendamento(
	id_usuario int not NULL,
	id_agendamento int not NULL,
	primary key (id_usuario, id_agendamento),
	foreign key (id_usuario)
		references usuario(id),
	foreign key (id_agendamento)
		references agendamento(id)
);

CREATE TABLE imagens (
    id INT AUTO_INCREMENT,
	PRIMARY KEY (id),
    caminho VARCHAR(255) NOT NULL,
    data_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);