<?php
// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
$usuarioLogado = isset($_SESSION['usuario_id']);

$tituloPagina = 'Detalhes do Passeio';
$cssPagina = 'passeio.css';
require_once '../components/layout/base-inicio.php';

// Pega o ID do passeio da URL
$passeioId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Dados mockados dos passeios (futuramente virá do banco)
$todosPasseios = [
    1 => [
        'id' => 1,
        'titulo' => 'Trilha da Serra',
        'descricao_curta' => 'Aventura em meio à natureza com vistas incríveis.',
        'descricao' => 'A Trilha da Serra é um dos roteiros mais deslumbrantes do Brasil. Durante o percurso de aproximadamente 8km, você será guiado por paisagens exuberantes da Mata Atlântica, com paradas em mirantes naturais que oferecem vistas panorâmicas de tirar o fôlego.

O passeio inclui travessia por riachos cristalinos, observação de fauna e flora nativas, e uma parada especial em uma cachoeira escondida onde é possível tomar um banho refrescante.

Nível de dificuldade: Moderado
Duração média: 4 a 5 horas
O que levar: Água, lanche leve, protetor solar, repelente e calçado adequado para trilha.',
        'localizacao' => 'Serra do Mar - Paraná',
        'duracao' => '4-5 horas',
        'dificuldade' => 'Moderado',
        'preco' => 75.00,
        'imagens' => [
            'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1501555088652-021faa106b9b?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&h=600&fit=crop',
        ]
    ],
    2 => [
        'id' => 2,
        'titulo' => 'Cachoeira Azul',
        'descricao_curta' => 'Um dos destinos mais procurados para quem ama água e natureza.',
        'descricao' => 'A Cachoeira Azul é um verdadeiro paraíso escondido. Com suas águas cristalinas e tons azulados únicos, este destino proporciona uma experiência inesquecível.

O trajeto até a cachoeira passa por uma trilha leve de aproximadamente 2km em meio à mata preservada. Ao chegar, você poderá nadar nas piscinas naturais formadas pelas quedas d\'água.

Nível de dificuldade: Fácil
Duração média: 3 a 4 horas
O que levar: Roupa de banho, toalha, protetor solar e água.',
        'localizacao' => 'Vale do Ribeira - SP',
        'duracao' => '3-4 horas',
        'dificuldade' => 'Fácil',
        'preco' => 120.00,
        'imagens' => [
            'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1546514355-7fdc90ccbd03?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1504214208698-ea1916a2195a?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1482192505345-5655af888cc4?w=800&h=600&fit=crop',
        ]
    ],
    3 => [
        'id' => 3,
        'titulo' => 'City Tour Histórico',
        'descricao_curta' => 'Explore os pontos turísticos e históricos da cidade.',
        'descricao' => 'Conheça a história e a cultura da cidade através de um tour guiado pelos principais pontos históricos. Visite igrejas centenárias, museus, praças e construções que contam a história do Brasil.

O passeio inclui paradas para fotos, explicações detalhadas sobre cada local e tempo livre para explorar.

Nível de dificuldade: Fácil
Duração média: 4 horas
O que levar: Câmera fotográfica, protetor solar e calçado confortável.',
        'localizacao' => 'Centro Histórico - Curitiba',
        'duracao' => '4 horas',
        'dificuldade' => 'Fácil',
        'preco' => 95.00,
        'imagens' => [
            'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=800&h=600&fit=crop',
        ]
    ],
    4 => [
        'id' => 4,
        'titulo' => 'Passeio de Barco',
        'descricao_curta' => 'Navegue pelas águas cristalinas e descubra paisagens deslumbrantes.',
        'descricao' => 'Embarque nesta aventura náutica e descubra as belezas naturais vistas de um ângulo único. O passeio de barco oferece vistas panorâmicas da costa, com paradas em praias isoladas e pontos de mergulho.

Durante o trajeto, você poderá observar a vida marinha, aves e, com sorte, golfinhos que frequentam a região.

Nível de dificuldade: Fácil
Duração média: 5 a 6 horas
O que levar: Protetor solar, chapéu, roupa de banho e câmera à prova d\'água.',
        'localizacao' => 'Baía de Paranaguá - PR',
        'duracao' => '5-6 horas',
        'dificuldade' => 'Fácil',
        'preco' => 180.00,
        'imagens' => [
            'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1476673160081-cf065607f449?w=800&h=600&fit=crop',
        ]
    ],
];

// Busca o passeio pelo ID (ou usa o primeiro se não encontrar)
$passeio = isset($todosPasseios[$passeioId]) ? $todosPasseios[$passeioId] : $todosPasseios[1];

// Guias disponíveis para este passeio (mockado)
$guias = [
    [
        'id' => 1,
        'nome' => 'Carlos Silva',
        'foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop',
        'especialidade' => 'Trilhas e Ecoturismo',
        'disponibilidade' => ['2025-12-05', '2025-12-06', '2025-12-07', '2025-12-10', '2025-12-12']
    ],
    [
        'id' => 2,
        'nome' => 'Ana Oliveira',
        'foto' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&h=150&fit=crop',
        'especialidade' => 'Aventura e Natureza',
        'disponibilidade' => ['2025-12-04', '2025-12-05', '2025-12-08', '2025-12-09', '2025-12-11']
    ],
    [
        'id' => 3,
        'nome' => 'Pedro Santos',
        'foto' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop',
        'especialidade' => 'Fotografia e Natureza',
        'disponibilidade' => ['2025-12-03', '2025-12-06', '2025-12-07', '2025-12-13', '2025-12-14']
    ]
];

// Horários disponíveis
$horarios = ['06:00', '07:00', '08:00', '14:00', '15:00'];
?>

<main class="passeio-page">
    <!-- Galeria de Imagens -->
    <section class="galeria">
        <div class="galeria__principal">
            <img id="imagem-principal" src="<?= $passeio['imagens'][0] ?>" alt="<?= $passeio['titulo'] ?>">
            <button class="galeria__nav galeria__nav--prev" onclick="mudarImagem(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="galeria__nav galeria__nav--next" onclick="mudarImagem(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <?php foreach ($passeio['imagens'] as $index => $imagem): ?>
            <?php include '../components/cards/guia-card.php'; ?>
        <?php endforeach; ?>
        
    </section>

    <!-- Informações do Passeio -->
    <section class="passeio-info">
        <div class="passeio-info__header">
            <div>
                <h1 class="passeio-info__titulo"><?= $passeio['titulo'] ?></h1>
                <p class="passeio-info__localizacao">
                    <i class="fas fa-map-marker-alt"></i> <?= $passeio['localizacao'] ?>
                </p>
            </div>
            <div class="passeio-info__preco">
                <span class="passeio-info__preco-valor">R$ <?= number_format($passeio['preco'], 2, ',', '.') ?></span>
                <span class="passeio-info__preco-pessoa">por pessoa</span>
            </div>
        </div>

        <div class="passeio-info__badges">
            <span class="badge">
                <?= $passeio['duracao'] ?>
            </span>
            <span class="badge">
                <?= $passeio['dificuldade'] ?>
            </span>
        </div>

        <div class="passeio-info__descricao">
            <h2>Sobre o passeio</h2>
            <p><?= nl2br(htmlspecialchars($passeio['descricao'])) ?></p>
        </div>
    </section>

    <!-- Seção de Agendamento -->
    <section class="agendamento">
        <h2 class="agendamento__titulo">
            <i class="fas fa-calendar-check"></i> Agendar Passeio
        </h2>

        <!-- Seleção de Guia -->
        <div class="agendamento__guias">
            <h3>Escolha seu guia</h3>
            <div class="guias-lista">
                <?php foreach ($guias as $guia): ?>
                    <div class="guia-card" data-guia-id="<?= $guia['id'] ?>" onclick="selecionarGuia(<?= $guia['id'] ?>)">
                        <img src="<?= $guia['foto'] ?>" alt="<?= $guia['nome'] ?>" class="guia-card__foto">
                        <div class="guia-card__info">
                            <h4 class="guia-card__nome"><?= $guia['nome'] ?></h4>
                            <p class="guia-card__especialidade"><?= $guia['especialidade'] ?></p>
                        </div>
                        <div class="guia-card__check">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Seleção de Data e Horário -->
        <div class="agendamento__datetime">
            <div class="agendamento__data">
                <h3>Escolha a data</h3>
                <input type="date" id="data-passeio" class="input-data" min="<?= date('Y-m-d') ?>">
            </div>

            <div class="agendamento__horario">
                <h3>Escolha o horário</h3>
                <div class="horarios-lista">
                    <?php foreach ($horarios as $horario): ?>
                        <button class="horario-btn" data-horario="<?= $horario ?>" onclick="selecionarHorario('<?= $horario ?>')">
                            <?= $horario ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Resumo e Botão de Confirmação -->
        <div class="agendamento__resumo">
            <div class="resumo-info">
                <div class="resumo-item">
                    <span class="resumo-label">Guia:</span>
                    <span id="resumo-guia" class="resumo-valor">Não selecionado</span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Data:</span>
                    <span id="resumo-data" class="resumo-valor">Não selecionada</span>
                </div>
                <div class="resumo-item">
                    <span class="resumo-label">Horário:</span>
                    <span id="resumo-horario" class="resumo-valor">Não selecionado</span>
                </div>
                <div class="resumo-item resumo-item--total">
                    <span class="resumo-label">Total:</span>
                    <span class="resumo-valor resumo-valor--preco">R$ <?= number_format($passeio['preco'], 2, ',', '.') ?></span>
                </div>
            </div>
            <button class="btn-agendar" onclick="confirmarAgendamento()">
                <i class="fas fa-check"></i> Confirmar Agendamento
            </button>
        </div>
    </section>
</main>

<script>
// Verifica se o usuário está logado
const usuarioLogado = <?= $usuarioLogado ? 'true' : 'false' ?>;

// Dados das imagens para a galeria
const imagens = <?= json_encode($passeio['imagens']) ?>;
let imagemAtual = 0;

// Dados dos guias
const guiasData = <?= json_encode($guias) ?>;

// Estado do agendamento
let agendamento = {
    guiaId: null,
    guiaNome: null,
    data: null,
    horario: null
};

// Funções da Galeria
function mudarImagem(direcao) {
    imagemAtual += direcao;
    if (imagemAtual < 0) imagemAtual = imagens.length - 1;
    if (imagemAtual >= imagens.length) imagemAtual = 0;
    atualizarGaleria();
}

function selecionarImagem(index) {
    imagemAtual = index;
    atualizarGaleria();
}

function atualizarGaleria() {
    document.getElementById('imagem-principal').src = imagens[imagemAtual];
    document.querySelectorAll('.galeria__thumb').forEach((thumb, index) => {
        thumb.classList.toggle('galeria__thumb--ativa', index === imagemAtual);
    });
}

// Funções de Agendamento
function selecionarGuia(guiaId) {
    const guia = guiasData.find(g => g.id === guiaId);
    agendamento.guiaId = guiaId;
    agendamento.guiaNome = guia.nome;

    document.querySelectorAll('.guia-card').forEach(card => {
        card.classList.toggle('guia-card--selecionado', parseInt(card.dataset.guiaId) === guiaId);
    });

    document.getElementById('resumo-guia').textContent = guia.nome;
}

function selecionarHorario(horario) {
    agendamento.horario = horario;

    document.querySelectorAll('.horario-btn').forEach(btn => {
        btn.classList.toggle('horario-btn--selecionado', btn.dataset.horario === horario);
    });

    document.getElementById('resumo-horario').textContent = horario;
}

// Atualizar data no resumo
document.getElementById('data-passeio').addEventListener('change', function() {
    agendamento.data = this.value;
    const dataFormatada = new Date(this.value + 'T00:00:00').toLocaleDateString('pt-BR');
    document.getElementById('resumo-data').textContent = dataFormatada;
});

function confirmarAgendamento() {
    // Verifica se o usuário está logado
    if (!usuarioLogado) {
        alert('Você precisa estar logado para fazer um agendamento.');
        window.location.href = 'login.php';
        return;
    }

    if (!agendamento.guiaId) {
        alert('Por favor, selecione um guia.');
        return;
    }
    if (!agendamento.data) {
        alert('Por favor, selecione uma data.');
        return;
    }
    if (!agendamento.horario) {
        alert('Por favor, selecione um horário.');
        return;
    }

    // Aqui você pode enviar para o backend via AJAX
    alert(`Agendamento confirmado!\n\nGuia: ${agendamento.guiaNome}\nData: ${document.getElementById('resumo-data').textContent}\nHorário: ${agendamento.horario}`);
}
</script>

<?php
$jsPagina = 'passeio.js';
require_once '../components/layout/base-fim.php';
?>

