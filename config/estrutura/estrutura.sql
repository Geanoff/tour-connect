	create database if not exists tourconnect;
	use tourconnect;

	-- Tabela de usuários
	create table usuarios(
		id INT AUTO_INCREMENT,
		primary key (id),
		nome VARCHAR(100) not NULL,
		cpf VARCHAR(11),
		senha VARCHAR(255) not NULL,
		email VARCHAR(255) not NULL,
		telefone VARCHAR(20)
	);

	-- Tabela de imagens
	create table imagens(
		id INT AUTO_INCREMENT,
		primary key (id),
		caminho VARCHAR(255) not NULL,
		data_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);

	-- Tabela de guias
	create table guia(
		id INT AUTO_INCREMENT,
		primary key (id),
		nome VARCHAR(100) not NULL,
		id_imagem INT,
		especialidade VARCHAR(100),
		data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		foreign key (id_imagem) references imagens(id) ON DELETE SET NULL
	);

	-- Tabela de destinos/passeios
	create table destino(
		id INT AUTO_INCREMENT,
		primary key (id),
		titulo VARCHAR(150) not NULL,
		descricao_curta VARCHAR(255),
		descricao TEXT,
		localizacao VARCHAR(100),
		duracao VARCHAR(50),
		dificuldade VARCHAR(50),
		preco DECIMAL(10,2) DEFAULT 0,
		limite_pessoas INT DEFAULT 10,
		id_imagem INT,
		data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		foreign key (id_imagem) references imagens(id) ON DELETE SET NULL
	);

	-- Tabela de imagens adicionais do destino
	create table destino_imagens(
		id INT AUTO_INCREMENT,
		primary key (id),
		id_destino INT not NULL,
		id_imagem INT not NULL,
		ordem INT DEFAULT 0,
		foreign key (id_destino) references destino(id) ON DELETE CASCADE,
		foreign key (id_imagem) references imagens(id) ON DELETE CASCADE
	);

	-- Tabela de vínculo entre destino e guia
	create table destino_guia(
		id INT AUTO_INCREMENT,
		primary key (id),
		id_destino INT not NULL,
		id_guia INT not NULL,
		foreign key (id_destino) references destino(id) ON DELETE CASCADE,
		foreign key (id_guia) references guia(id) ON DELETE CASCADE,
		UNIQUE KEY unique_destino_guia (id_destino, id_guia)
	);

	-- Tabela de agendamentos
	create table agendamento(
		id INT AUTO_INCREMENT,
		primary key (id),
		id_usuario INT not NULL,
		id_destino INT not NULL,
		id_guia INT not NULL,
		data_passeio DATE not NULL,
		horario VARCHAR(10) not NULL,
		quantidade_pessoas INT DEFAULT 1,
		status ENUM('agendado', 'concluido') DEFAULT 'agendado',
		data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		foreign key (id_usuario) references usuarios(id) ON DELETE CASCADE,
		foreign key (id_destino) references destino(id) ON DELETE CASCADE,
		foreign key (id_guia) references guia(id) ON DELETE CASCADE
	);

	-- Tabela de participantes do agendamento
	create table agendamento_participantes(
		id INT AUTO_INCREMENT,
		primary key (id),
		id_agendamento INT not NULL,
		nome VARCHAR(100) not NULL,
		idade INT not NULL,
		foreign key (id_agendamento) references agendamento(id) ON DELETE CASCADE
	);

	-- Tabela de solicitações para ser guia
	create table solicitacao_guia(
		id INT AUTO_INCREMENT,
		primary key (id),
		id_usuario INT NOT NULL,
		nome VARCHAR(100) NOT NULL,
		email VARCHAR(255) NOT NULL,
		telefone VARCHAR(20),
		mensagem TEXT,
		lida TINYINT(1) DEFAULT 0,
		data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		foreign key (id_usuario) references usuarios(id) ON DELETE CASCADE
	);
