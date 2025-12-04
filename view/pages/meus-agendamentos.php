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
require_once '../../model/AgendamentoModel.php';

// Dados do usuário logado
$usuario = [
    'id' => $_SESSION['usuario_id'],
    'nome' => $_SESSION['usuario_nome'] ?? 'Usuário'
];

// Busca agendamentos do usuário no banco
$agendamentoModel = new AgendamentoModel();
$agendamentosDb = $agendamentoModel->buscarPorUsuario($_SESSION['usuario_id']);

// Formata os agendamentos para o formato esperado pelo template
$agendamentos = array_map(function($a) {
    // Ajusta caminho da imagem
    $imagem = $a['passeio_imagem'] ?? '';
    if ($imagem && strpos($imagem, 'http') !== 0) {
        $imagem = '../../' . $imagem;
    }
    $guiaFoto = $a['guia_foto'] ?? '';
    if ($guiaFoto && strpos($guiaFoto, 'http') !== 0) {
        $guiaFoto = '../../' . $guiaFoto;
    }
    
    return [
        'id' => $a['id'],
        'passeio_id' => $a['id_destino'],
        'passeio_titulo' => $a['passeio_titulo'],
        'passeio_imagem' => $imagem ?: 'https://via.placeholder.com/400x300',
        'passeio_localizacao' => $a['passeio_localizacao'],
        'guia_nome' => $a['guia_nome'],
        'guia_foto' => $guiaFoto ?: 'https://via.placeholder.com/80',
        'data' => $a['data_passeio'],
        'horario' => $a['horario'],
        'preco' => $a['passeio_preco'] ?? 0,
        'quantidade_pessoas' => $a['quantidade_pessoas'] ?? 1,
        'status' => $a['status']
    ];
}, $agendamentosDb);

// Função para formatar status
function formatarStatus($status) {
    $statusMap = [
        'agendado' => ['texto' => 'Agendado', 'classe' => 'status--agendado', 'icone' => 'fa-calendar-check'],
        'concluido' => ['texto' => 'Concluído', 'classe' => 'status--concluido', 'icone' => 'fa-flag-checkered'],
    ];
    return $statusMap[$status] ?? $statusMap['agendado'];
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
    'agendado' => count(array_filter($agendamentos, fn($a) => $a['status'] === 'agendado')),
    'concluido' => count(array_filter($agendamentos, fn($a) => $a['status'] === 'concluido')),
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
        <a href="?status=agendado" class="filtro-btn filtro-btn--agendado <?= $filtroStatus === 'agendado' ? 'filtro-btn--ativo' : '' ?>">
            <i class="fas fa-calendar-check"></i>
            <span class="filtro-btn__texto">Agendados</span>
            <span class="filtro-btn__count"><?= $contadores['agendado'] ?></span>
        </a>
        <a href="?status=concluido" class="filtro-btn filtro-btn--concluido <?= $filtroStatus === 'concluido' ? 'filtro-btn--ativo' : '' ?>">
            <i class="fas fa-flag-checkered"></i>
            <span class="filtro-btn__texto">Concluídos</span>
            <span class="filtro-btn__count"><?= $contadores['concluido'] ?></span>
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
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <span class="info-item__label">Pessoas</span>
                                        <span class="info-item__valor"><?= $agendamento['quantidade_pessoas'] ?? 1 ?></span>
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
                                <button class="btn-excluir" onclick="excluirAgendamento(<?= $agendamento['id'] ?>)" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<?php
$jsPagina = 'meus-agendamentos.js';
require_once '../components/layout/base-fim.php';
?>

