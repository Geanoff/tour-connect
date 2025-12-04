<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);

$tituloPagina = 'Detalhes do Passeio';
$cssPagina = 'passeio.css';
require_once '../components/layout/base-inicio.php';
require_once '../../model/DestinoModel.php';

$destinoId = $_GET['id'];

$modelDestino = new DestinoModel();

$destino = (array) $modelDestino->buscarDestinoEspecifico($destinoId);

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

$horarios = ['06:00', '07:00', '08:00', '14:00', '15:00'];
?>

<main class="passeio-page">
    <section class="galeria">
        <div class="galeria__principal">
            <img id="imagem-principal" src="<?= htmlspecialchars($destino['imagem']) ?>" alt="<?= htmlspecialchars($destino['titulo']) ?>">
            <button class="galeria__nav galeria__nav--prev" onclick="mudarImagem(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="galeria__nav galeria__nav--next" onclick="mudarImagem(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="galeria__miniaturas">
            <!-- carrossel imagens -->
        </div>
        
    </section>
    <section class="passeio-info">
        <div class="passeio-info__header">
            <div>
                <h1 class="passeio-info__titulo"><?= htmlspecialchars($destino['titulo']) ?></h1>
                <p class="passeio-info__localizacao">
                    <i class="fas fa-map-marker-alt"></i> <?= $destino['localizacao'] ?>
                </p>
            </div>
            <div class="passeio-info__preco">
                <span class="passeio-info__preco-valor">R$ <?= number_format($destino['preco'], 2, ',', '.') ?></span>
                <span class="passeio-info__preco-pessoa">por pessoa</span>
            </div>
        </div>

        <div class="passeio-info__badges">
            <span class="badge">
                <i class="fas fa-clock"></i> <?= $destino['duracao'] ?>
            </span>
            <span class="badge">
                <i class="fas fa-signal"></i> <?= $destino['dificuldade'] ?>
            </span>
        </div>

        <div class="passeio-info__descricao">
            <h2>Sobre o passeio</h2>
            <p><?= nl2br(htmlspecialchars($destino['descricao'])) ?></p>
        </div>
    </section>

    <section class="agendamento">
        <h2 class="agendamento__titulo">
            <i class="fas fa-calendar-check"></i> Agendar Passeio
        </h2>

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
                    <span class="resumo-valor resumo-valor--preco">R$ <?= number_format($destino['preco'], 2, ',', '.') ?></span>
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

