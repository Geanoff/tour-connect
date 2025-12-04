<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);

$tituloPagina = 'Detalhes do Passeio';
$cssPagina = 'passeio.css';
require_once '../components/layout/base-inicio.php';

$passeioId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

$passeio = isset($todosPasseios[$passeioId]) ? $todosPasseios[$passeioId] : $todosPasseios[1];

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
            <img id="imagem-principal" src="<?= $passeio['imagens'][0] ?>" alt="<?= $passeio['titulo'] ?>">
            <button class="galeria__nav galeria__nav--prev" onclick="mudarImagem(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="galeria__nav galeria__nav--next" onclick="mudarImagem(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="galeria__miniaturas">
            <?php foreach ($passeio['imagens'] as $index => $imagem): ?>
                <img 
                    src="<?= $imagem ?>" 
                    alt="Miniatura <?= $index + 1 ?>" 
                    class="galeria__thumb <?= $index === 0 ? 'galeria__thumb--ativa' : '' ?>"
                    onclick="selecionarImagem(<?= $index ?>)"
                >
            <?php endforeach; ?>
        </div>
    </section>

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
                <i class="fas fa-clock"></i> <?= $passeio['duracao'] ?>
            </span>
            <span class="badge">
                <i class="fas fa-signal"></i> <?= $passeio['dificuldade'] ?>
            </span>
        </div>

        <div class="passeio-info__descricao">
            <h2>Sobre o passeio</h2>
            <p><?= nl2br(htmlspecialchars($passeio['descricao'])) ?></p>
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
    window.passeioConfig = {
        usuarioLogado: <?= $usuarioLogado ? 'true' : 'false' ?>,
        imagens: <?= json_encode($passeio['imagens']) ?>,
        guias: <?= json_encode($guias) ?>
    };
</script>

<?php
$jsPagina = 'passeio.js';
require_once '../components/layout/base-fim.php';
?>

