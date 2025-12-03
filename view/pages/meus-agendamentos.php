<?php
// Inicia sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$tituloPagina = 'Meus Agendamentos - Tour Connect';
$cssPagina = 'meus-agendamentos.css';
require_once '../components/layout/base-inicio.php';

// Dados do usuário logado
$usuario = [
    'id' => $_SESSION['usuario_id'],
    'nome' => $_SESSION['usuario_nome'] ?? 'Usuário'
];

// Agendamentos mockados do usuário (futuramente virá do banco)
$agendamentos = [
    [
        'id' => 1,
        'passeio_id' => 1,
        'passeio_titulo' => 'Trilha da Serra',
        'passeio_imagem' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=400&h=300&fit=crop',
        'passeio_localizacao' => 'Serra do Mar - Paraná',
        'guia_nome' => 'Carlos Silva',
        'guia_foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop',
        'data' => '2025-12-10',
        'horario' => '07:00',
        'preco' => 75.00,
        'status' => 'confirmado'
    ],
    [
        'id' => 2,
        'passeio_id' => 4,
        'passeio_titulo' => 'Passeio de Barco',
        'passeio_imagem' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=300&fit=crop',
        'passeio_localizacao' => 'Baía de Paranaguá - PR',
        'guia_nome' => 'Ana Oliveira',
        'guia_foto' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80&h=80&fit=crop',
        'data' => '2025-12-15',
        'horario' => '08:00',
        'preco' => 180.00,
        'status' => 'confirmado'
    ],
    [
        'id' => 3,
        'passeio_id' => 2,
        'passeio_titulo' => 'Cachoeira Azul',
        'passeio_imagem' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=400&h=300&fit=crop',
        'passeio_localizacao' => 'Vale do Ribeira - SP',
        'guia_nome' => 'Pedro Santos',
        'guia_foto' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop',
        'data' => '2025-11-20',
        'horario' => '06:00',
        'preco' => 120.00,
        'status' => 'concluido'
    ],
    [
        'id' => 4,
        'passeio_id' => 3,
        'passeio_titulo' => 'City Tour Histórico',
        'passeio_imagem' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=400&h=300&fit=crop',
        'passeio_localizacao' => 'Centro Histórico - Curitiba',
        'guia_nome' => 'Carlos Silva',
        'guia_foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop',
        'data' => '2025-10-05',
        'horario' => '14:00',
        'preco' => 95.00,
        'status' => 'cancelado'
    ],
];

// Função para formatar status
function formatarStatus($status) {
    $statusMap = [
        'confirmado' => ['texto' => 'Confirmado', 'classe' => 'status--confirmado', 'icone' => 'fa-check-circle'],
        'concluido' => ['texto' => 'Concluído', 'classe' => 'status--concluido', 'icone' => 'fa-flag-checkered'],
        'cancelado' => ['texto' => 'Cancelado', 'classe' => 'status--cancelado', 'icone' => 'fa-times-circle'],
    ];
    return $statusMap[$status] ?? $statusMap['confirmado'];
}

// Filtrar por status se houver filtro
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : 'todos';
$agendamentosFiltrados = $agendamentos;

if ($filtroStatus !== 'todos') {
    $agendamentosFiltrados = array_filter($agendamentos, function($a) use ($filtroStatus) {
        return $a['status'] === $filtroStatus;
    });
}

// Contar por status
$contadores = [
    'todos' => count($agendamentos),
    'confirmado' => count(array_filter($agendamentos, fn($a) => $a['status'] === 'confirmado')),
    'concluido' => count(array_filter($agendamentos, fn($a) => $a['status'] === 'concluido')),
    'cancelado' => count(array_filter($agendamentos, fn($a) => $a['status'] === 'cancelado')),
];
?>

<main class="meus-agendamentos">
    <!-- Header -->
    <section class="pagina-header">
        <div class="pagina-header__info">
            <a href="home-usuario.php" class="btn-voltar">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1><i class="fas fa-calendar-alt"></i> Meus Agendamentos</h1>
                <p>Gerencie seus passeios agendados</p>
            </div>
        </div>
        <a href="home-usuario.php" class="btn-novo-passeio">
            <i class="fas fa-plus"></i> Agendar Novo Passeio
        </a>
    </section>

    <!-- Filtros por Status -->
    <section class="filtros-status">
        <a href="?status=todos" class="filtro-btn <?= $filtroStatus === 'todos' ? 'filtro-btn--ativo' : '' ?>">
            <span class="filtro-btn__texto">Todos</span>
            <span class="filtro-btn__count"><?= $contadores['todos'] ?></span>
        </a>
        <a href="?status=confirmado" class="filtro-btn filtro-btn--confirmado <?= $filtroStatus === 'confirmado' ? 'filtro-btn--ativo' : '' ?>">
            <i class="fas fa-check-circle"></i>
            <span class="filtro-btn__texto">Confirmados</span>
            <span class="filtro-btn__count"><?= $contadores['confirmado'] ?></span>
        </a>
        <a href="?status=concluido" class="filtro-btn filtro-btn--concluido <?= $filtroStatus === 'concluido' ? 'filtro-btn--ativo' : '' ?>">
            <i class="fas fa-flag-checkered"></i>
            <span class="filtro-btn__texto">Concluídos</span>
            <span class="filtro-btn__count"><?= $contadores['concluido'] ?></span>
        </a>
        <a href="?status=cancelado" class="filtro-btn filtro-btn--cancelado <?= $filtroStatus === 'cancelado' ? 'filtro-btn--ativo' : '' ?>">
            <i class="fas fa-times-circle"></i>
            <span class="filtro-btn__texto">Cancelados</span>
            <span class="filtro-btn__count"><?= $contadores['cancelado'] ?></span>
        </a>
    </section>

    <!-- Lista de Agendamentos -->
    <section class="agendamentos-lista">
        <?php if (empty($agendamentosFiltrados)): ?>
            <div class="sem-agendamentos">
                <i class="fas fa-calendar-times"></i>
                <h3>Nenhum agendamento encontrado</h3>
                <p>
                    <?php if ($filtroStatus !== 'todos'): ?>
                        Você não possui agendamentos com este status.
                    <?php else: ?>
                        Você ainda não fez nenhum agendamento.
                    <?php endif; ?>
                </p>
                <a href="home-usuario.php" class="btn-explorar">
                    <i class="fas fa-compass"></i> Explorar Passeios
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($agendamentosFiltrados as $agendamento): ?>
                <?php $statusInfo = formatarStatus($agendamento['status']); ?>
                <div class="agendamento-card" data-id="<?= $agendamento['id'] ?>">
                    <div class="agendamento-card__imagem">
                        <img src="<?= $agendamento['passeio_imagem'] ?>" alt="<?= $agendamento['passeio_titulo'] ?>">
                        <span class="agendamento-card__status <?= $statusInfo['classe'] ?>">
                            <i class="fas <?= $statusInfo['icone'] ?>"></i>
                            <?= $statusInfo['texto'] ?>
                        </span>
                    </div>
                    
                    <div class="agendamento-card__conteudo">
                        <div class="agendamento-card__principal">
                            <h3 class="agendamento-card__titulo"><?= $agendamento['passeio_titulo'] ?></h3>
                            <p class="agendamento-card__localizacao">
                                <i class="fas fa-map-marker-alt"></i> <?= $agendamento['passeio_localizacao'] ?>
                            </p>
                            
                            <div class="agendamento-card__info-grid">
                                <div class="info-item">
                                    <i class="fas fa-calendar"></i>
                                    <div>
                                        <span class="info-item__label">Data</span>
                                        <span class="info-item__valor"><?= date('d/m/Y', strtotime($agendamento['data'])) ?></span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <span class="info-item__label">Horário</span>
                                        <span class="info-item__valor"><?= $agendamento['horario'] ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="agendamento-card__guia">
                                <img src="<?= $agendamento['guia_foto'] ?>" alt="<?= $agendamento['guia_nome'] ?>">
                                <div>
                                    <span class="guia-label">Guia</span>
                                    <span class="guia-nome"><?= $agendamento['guia_nome'] ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="agendamento-card__lateral">
                            <div class="agendamento-card__preco">
                                <span class="preco-label">Valor</span>
                                <span class="preco-valor">R$ <?= number_format($agendamento['preco'], 2, ',', '.') ?></span>
                            </div>
                            
                            <div class="agendamento-card__acoes">
                                <a href="passeio.php?id=<?= $agendamento['passeio_id'] ?>" class="btn-ver" title="Ver passeio">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($agendamento['status'] === 'confirmado'): ?>
                                    <button class="btn-cancelar" onclick="cancelarAgendamento(<?= $agendamento['id'] ?>)" title="Cancelar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<!-- Modal de Confirmação de Cancelamento -->
<div id="modal-cancelar" class="modal">
    <div class="modal__conteudo modal__conteudo--pequeno">
        <div class="modal__header modal__header--aviso">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>Cancelar Agendamento</h2>
            <p>Tem certeza que deseja cancelar este agendamento?</p>
        </div>
        <div class="modal__acoes">
            <button class="btn-modal-cancelar" onclick="fecharModalCancelar()">Não, manter</button>
            <button class="btn-modal-confirmar" onclick="confirmarCancelamento()">Sim, cancelar</button>
        </div>
    </div>
</div>

<?php
$jsPagina = 'meus-agendamentos.js';
require_once '../components/layout/base-fim.php';
?>

