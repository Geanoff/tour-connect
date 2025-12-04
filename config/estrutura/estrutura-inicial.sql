create database tourconnect;
use tourconnect;

create table usuario(
	id INT auto_increment,
	primary key (id),
	nome VARCHAR(100) not NULL,
	cpf VARCHAR(11) not NULL,
	senha VARCHAR(255) not NULL,
	email VARCHAR(255) not NULL,
	telefone VARCHAR(20) not NULL
);

create table guia(
	id INT auto_increment,
	primary key (id),
	nome VARCHAR(100) not NULL,
	cpf VARCHAR(11) not NULL,
	senha VARCHAR(255) not NULL,
	email VARCHAR(255) not NULL,
	telefone VARCHAR(20) not NULL
);

create table destino(
	id INT AUTO_INCREMENT,
	primary key(id),
	nome_destino VARCHAR(50) not NULL,
	descricao VARCHAR(100) not NULL,
	cidade VARCHAR(50) not NULL,
	id_guia INT not NULL,
	foreign key (id_guia) 
		references guia(id)
);

create table agendamento(
	id int auto_increment,
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

