use tourconnect;

-- Inserindo imagens dos guias
INSERT INTO imagens (caminho) VALUES
('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop'),
('https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop'),
('https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&h=200&fit=crop'),
('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&h=200&fit=crop'),
('https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop'),
('https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&h=200&fit=crop'),
('https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200&h=200&fit=crop'),
('https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&h=200&fit=crop');

-- Inserindo guias
INSERT INTO guia (nome, id_imagem, especialidade) VALUES
('Carlos Silva', 1, 'Trilhas e Ecoturismo'),
('Ana Oliveira', 2, 'Praias e Mergulho'),
('Pedro Santos', 3, 'Aventura e Rapel'),
('Maria Costa', 4, 'Turismo Histórico'),
('João Ferreira', 5, 'Fotografia de Natureza'),
('Fernanda Lima', 6, 'Passeios de Barco'),
('Ricardo Souza', 7, 'Escalada e Montanhismo'),
('Juliana Alves', 8, 'Observação de Fauna');

-- Inserindo imagens dos destinos
INSERT INTO imagens (caminho) VALUES
('https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop'),
('https://images.unsplash.com/photo-1483729558449-99ef09a8c325?w=800&h=600&fit=crop'),
('https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?w=800&h=600&fit=crop'),
('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&h=600&fit=crop'),
('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop'),
('https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&h=600&fit=crop'),
('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&h=600&fit=crop'),
('https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop');

-- Inserindo destinos/passeios
INSERT INTO destino (titulo, descricao_curta, descricao, localizacao, duracao, dificuldade, preco, id_imagem) VALUES
(
	'Piscinas Naturais de Maragogi',
	'Mergulhe nas águas cristalinas do Caribe Brasileiro.',
	'Descubra as famosas Piscinas Naturais de Maragogi, conhecidas como Galés. Localizadas a cerca de 6 km da costa, essas formações de corais criam verdadeiros aquários naturais com águas cristalinas e mornas.\n\nDurante o passeio de catamarã, você terá a oportunidade de mergulhar com snorkel e observar uma rica vida marinha, incluindo peixes coloridos, corais e até pequenas tartarugas.\n\nO que está incluso:\n• Transporte de ida e volta\n• Equipamento de snorkel\n• Colete salva-vidas\n• Guia acompanhante',
	'Maragogi - AL',
	'3-4 horas',
	'Fácil',
	150.00,
	9
),
(
	'Trilha do Pico da Bandeira',
	'Conquiste o terceiro ponto mais alto do Brasil.',
	'Uma das trilhas mais desafiadoras e recompensadoras do Brasil. O Pico da Bandeira, com 2.891 metros de altitude, oferece uma vista espetacular do nascer do sol acima das nuvens.\n\nA trilha tem aproximadamente 5 km de extensão até o cume, passando por paisagens de campos de altitude e formações rochosas únicas.\n\nO que está incluso:\n• Guia especializado\n• Transporte 4x4\n• Café da manhã no topo\n• Seguro aventura',
	'Alto Caparaó - MG',
	'6-8 horas',
	'Difícil',
	280.00,
	10
),
(
	'Passeio de Barco em Fernando de Noronha',
	'Navegue pelas águas mais cristalinas do Atlântico.',
	'Explore as belezas de Fernando de Noronha em um passeio de barco inesquecível. Visite praias paradisíacas acessíveis apenas por mar e tenha a chance de nadar com golfinhos.\n\nO roteiro inclui paradas para mergulho em pontos com visibilidade de até 50 metros, onde é possível observar tartarugas, raias e uma infinidade de peixes tropicais.\n\nO que está incluso:\n• Passeio de barco (4 horas)\n• Equipamento de snorkel\n• Frutas e bebidas\n• Guia bilíngue',
	'Fernando de Noronha - PE',
	'4 horas',
	'Fácil',
	350.00,
	11
),
(
	'Chapada dos Veadeiros - Cachoeiras',
	'Descubra as cachoeiras mais bonitas do Cerrado brasileiro.',
	'A Chapada dos Veadeiros é um paraíso para os amantes de natureza. Este roteiro inclui visita às principais cachoeiras da região, com quedas d\'água impressionantes em meio ao cerrado.\n\nAs trilhas passam por formações rochosas de mais de 1 bilhão de anos, campos de flores e piscinas naturais de águas geladas e revigorantes.\n\nO que está incluso:\n• Transporte em veículo 4x4\n• Guia credenciado\n• Ingresso no parque\n• Lanche de trilha',
	'Alto Paraíso - GO',
	'8 horas',
	'Moderado',
	220.00,
	12
),
(
	'Bonito - Flutuação no Rio da Prata',
	'Flutue em águas cristalinas entre peixes e natureza.',
	'O Rio da Prata oferece uma das experiências de flutuação mais incríveis do mundo. Com visibilidade de até 40 metros, você flutuará por 2 km observando cardumes de peixes, plantas aquáticas e a paisagem subaquática única.\n\nNão é necessário saber nadar - o colete e a correnteza fazem todo o trabalho enquanto você aprecia a natureza.\n\nO que está incluso:\n• Roupa de neoprene\n• Máscara e snorkel\n• Colete flutuador\n• Guia especializado',
	'Bonito - MS',
	'3 horas',
	'Fácil',
	290.00,
	13
),
(
	'Lençóis Maranhenses - Lagoas Azuis',
	'Caminhe sobre dunas e banhe-se em lagoas de água doce.',
	'Os Lençóis Maranhenses são um espetáculo da natureza: dunas de areia branca que se estendem até o horizonte, intercaladas por lagoas de água cristalina em tons de azul e verde.\n\nO passeio inclui visita às principais lagoas (Azul e Bonita) com tempo livre para banho e contemplação desta paisagem única no mundo.\n\nO que está incluso:\n• Transfer em 4x4\n• Guia local\n• Água e frutas\n• Tempo livre nas lagoas',
	'Barreirinhas - MA',
	'5 horas',
	'Fácil',
	180.00,
	14
),
(
	'Trilha da Pedra do Telégrafo',
	'A foto mais famosa do Rio de Janeiro te espera.',
	'A Pedra do Telégrafo é um dos pontos turísticos mais fotografados do Brasil. A trilha de 2,5 km leva você ao topo com vista panorâmica para a Praia de Grumari e toda a costa carioca.\n\nNo topo, você poderá tirar a famosa foto que dá a ilusão de estar pendurado sobre o abismo - totalmente seguro e impressionante!\n\nO que está incluso:\n• Guia experiente\n• Fotos profissionais\n• Transporte de Barra da Tijuca\n• Água e snacks',
	'Rio de Janeiro - RJ',
	'4 horas',
	'Moderado',
	120.00,
	15
),
(
	'Jalapão - Fervedouros',
	'Flutue nas nascentes de água cristalina do Tocantins.',
	'Os Fervedouros são nascentes de água que brotam do solo com tal pressão que é impossível afundar - você literalmente flutua! A sensação é única e relaxante.\n\nAlém dos fervedouros, o roteiro inclui dunas douradas, cachoeiras e a paisagem única do Jalapão, um dos destinos mais preservados do Brasil.\n\nO que está incluso:\n• Veículo 4x4 exclusivo\n• Guia credenciado\n• Todas as taxas de entrada\n• Almoço regional',
	'Mateiros - TO',
	'Dia inteiro',
	'Fácil',
	320.00,
	16
);

-- Vinculando guias aos destinos (destino_id, guia_id)
INSERT INTO destino_guia (id_destino, id_guia) VALUES
(1, 1), (1, 2),
(2, 1), (2, 3),
(3, 2), (3, 6), (3, 8),
(4, 1), (4, 5), (4, 8),
(5, 2), (5, 8),
(6, 1), (6, 5),
(7, 1), (7, 3), (7, 5),
(8, 1), (8, 5), (8, 8);
