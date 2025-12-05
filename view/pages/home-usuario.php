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

$tituloPagina = 'Início - Tour Connect';
$cssPagina = 'home-usuario.css';
require_once '../components/layout/base-inicio.php';
require_once '../../model/DestinoModel.php';

$usuario = [
    'id' => $_SESSION['usuario_id'],
    'nome' => $_SESSION['usuario_nome'] ?? 'Usuário',
    'email' => $_SESSION['usuario_email'] ?? '',
    'telefone' => $_SESSION['usuario_telefone'] ?? ''
];

$estados = [
    '' => 'Todos os estados',
    'AC' => 'Acre',
    'AL' => 'Alagoas',
    'AP' => 'Amapá',
    'AM' => 'Amazonas',
    'BA' => 'Bahia',
    'CE' => 'Ceará',
    'DF' => 'Distrito Federal',
    'ES' => 'Espírito Santo',
    'GO' => 'Goiás',
    'MA' => 'Maranhão',
    'MT' => 'Mato Grosso',
    'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais',
    'PA' => 'Pará',
    'PB' => 'Paraíba',
    'PR' => 'Paraná',
    'PE' => 'Pernambuco',
    'PI' => 'Piauí',
    'RJ' => 'Rio de Janeiro',
    'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul',
    'RO' => 'Rondônia',
    'RR' => 'Roraima',
    'SC' => 'Santa Catarina',
    'SP' => 'São Paulo',
    'SE' => 'Sergipe',
    'TO' => 'Tocantins',
];

$modelDestino = new DestinoModel();
$todosPasseios = $modelDestino->buscarDestinos();

$passeiosFiltrados = $todosPasseios;
$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$estadoFiltro = isset($_GET['estado']) ? $_GET['estado'] : '';

if (!empty($termoBusca)) {
    $passeiosFiltrados = array_filter($passeiosFiltrados, function($p) use ($termoBusca) {
        return stripos($p['titulo'], $termoBusca) !== false || 
               stripos($p['descricao'], $termoBusca) !== false ||
               stripos($p['localizacao'], $termoBusca) !== false;
    });
}

if (!empty($estadoFiltro)) {
    $passeiosFiltrados = array_filter($passeiosFiltrados, function($p) use ($estadoFiltro) {
        return stripos($p['localizacao'], $estadoFiltro) !== false ||
               stripos($p['localizacao'], '- ' . $estadoFiltro) !== false;
    });
}

$itensPorPagina = 6;
$totalItens = count($passeiosFiltrados);
$totalPaginas = ceil($totalItens / $itensPorPagina);
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$paginaAtual = min($paginaAtual, max(1, $totalPaginas));

$inicio = ($paginaAtual - 1) * $itensPorPagina;
$passeiosPaginados = array_slice($passeiosFiltrados, $inicio, $itensPorPagina);

function buildPaginationUrl($pagina) {
    $params = $_GET;
    $params['pagina'] = $pagina;
    return '?' . http_build_query($params);
}
?>

<main class="home-usuario">
    <section class="usuario-header">
        <div class="usuario-header__bemvindo">
            <h1>Olá, <span><?= htmlspecialchars($usuario['nome']) ?></span>! 👋</h1>
            <p>Encontre o passeio perfeito para sua próxima aventura</p>
        </div>
        <div class="usuario-header__acoes">
            <a href="meus-agendamentos.php" class="btn-agendamentos">
                <i class="fas fa-calendar-alt"></i>
                <span>Meus Agendamentos</span>
            </a>
            <?php if($usuario['email'] === 'administrador@example.com'): ?>
                <a href="admin/index.php" class="btn-ser-guia">
                    <i class="fas fa-user-tie"></i>
                    <span>Voltar ao menu admin</span>
                </a>    
            <?php else: ?>
                <a href="#" onclick="abrirModalGuia()" class="btn-ser-guia">
                    <i class="fas fa-user-tie"></i>
                    <span>Quero ser Guia</span>
                </a>
            <?php endif ?>
            <a href="../../controller/LogoutController.php" class="btn-sair">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </section>

    <section class="busca-filtros">
        <form method="GET" class="busca-form">
            <div class="busca-input-wrapper">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    name="busca" 
                    placeholder="Buscar passeios, trilhas, cachoeiras..." 
                    value="<?= htmlspecialchars($termoBusca) ?>"
                    class="busca-input"
                >
            </div>
            <div class="filtro-regiao">
                <i class="fas fa-map-marker-alt"></i>
                <select name="estado" class="select-regiao" onchange="this.form.submit()">
                    <?php foreach ($estados as $sigla => $nomeEstado): ?>
                        <option value="<?= $sigla ?>" <?= $estadoFiltro === $sigla ? 'selected' : '' ?>>
                            <?= $nomeEstado ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-buscar">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
    </section>
    <section class="passeios-resultado">
        <div class="resultado-header">
            <h2>
                <?php if (!empty($termoBusca) || !empty($estadoFiltro)): ?>
                    <i class="fas fa-filter"></i> Resultados da busca
                <?php else: ?>
                    <i class="fas fa-compass"></i> Passeios Disponíveis
                <?php endif; ?>
            </h2>
            <span class="resultado-count"><?= $totalItens ?> passeio(s) encontrado(s)</span>
        </div>

        <?php if (empty($passeiosPaginados)): ?>
            <div class="sem-resultados">
                <i class="fas fa-search"></i>
                <h3>Nenhum passeio encontrado</h3>
                <p>Tente buscar por outro termo ou altere os filtros.</p>
                <a href="home-usuario.php" class="btn-limpar">Limpar filtros</a>
            </div>
        <?php else: ?>
            <div class="lista-passeios">
                <?php foreach ($passeiosPaginados as $passeio): ?>
                    <?php 
                    $imgPasseio = $passeio['imagem'] ?? '';
                    if ($imgPasseio && strpos($imgPasseio, 'http') !== 0) {
                        $imgPasseio = '../../' . $imgPasseio;
                    }
                    ?>
                    <div class="passeio-card">
                        <div class="passeio-card__imagem">
                            <img src="<?= htmlspecialchars($imgPasseio) ?>" alt="<?= htmlspecialchars($passeio['titulo']) ?>">
                            <span class="passeio-card__localizacao">
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($passeio['localizacao']) ?>
                            </span>
                        </div>
                        <div class="passeio-card__conteudo">
                            <h3 class="passeio-card__titulo"><?= htmlspecialchars($passeio['titulo']) ?></h3>
                            <p class="passeio-card__descricao"><?= htmlspecialchars($passeio['descricao']) ?></p>
                            <div class="passeio-card__footer">
                                <span class="passeio-card__preco">R$ <?= number_format($passeio['preco'], 2, ',', '.') ?></span>
                                <a href="passeio.php?id=<?= $passeio['id'] ?>" class="passeio-card__btn">Ver detalhes</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Paginação -->
            <?php if ($totalPaginas > 1): ?>
                <nav class="paginacao">
                    <?php if ($paginaAtual > 1): ?>
                        <a href="<?= buildPaginationUrl($paginaAtual - 1) ?>" class="paginacao__btn paginacao__btn--nav">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="<?= buildPaginationUrl($i) ?>" class="paginacao__btn <?= $i === $paginaAtual ? 'paginacao__btn--ativo' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($paginaAtual < $totalPaginas): ?>
                        <a href="<?= buildPaginationUrl($paginaAtual + 1) ?>" class="paginacao__btn paginacao__btn--nav">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>

<!-- Modal Solicitação para ser Guia -->
<div id="modal-guia" class="modal">
    <div class="modal__conteudo">
        <button class="modal__fechar" onclick="fecharModalGuia()">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal__header">
            <i class="fas fa-user-tie"></i>
            <h2>Quero ser um Guia</h2>
            <p>Preencha o formulário abaixo para enviar sua solicitação</p>
        </div>
        <form id="form-guia" class="modal__form" onsubmit="enviarSolicitacaoGuia(event)">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nome</label>
                <input type="text" value="<?= htmlspecialchars($usuario['nome']) ?>" readonly class="input-readonly">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" value="<?= htmlspecialchars($usuario['email']) ?>" readonly class="input-readonly">
            </div>
            <div class="form-group">
                <label><i class="fas fa-phone"></i> Telefone</label>
                <input type="text" value="<?= htmlspecialchars($usuario['telefone']) ?>" readonly class="input-readonly">
            </div>
            <div class="form-group">
                <label><i class="fas fa-comment"></i> Mensagem (opcional)</label>
                <textarea name="mensagem" placeholder="Conte um pouco sobre sua experiência e por que deseja ser um guia..." rows="4"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-cancelar" onclick="fecharModalGuia()">Cancelar</button>
                <button type="submit" class="btn-enviar">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitação
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Configuração de dados do PHP para o JavaScript -->
<script>
    window.usuarioConfig = {
        id: <?= $usuario['id'] ?>,
        nome: '<?= addslashes($usuario['nome']) ?>',
        email: '<?= addslashes($usuario['email']) ?>',
        telefone: '<?= addslashes($usuario['telefone'] ?? '') ?>'
    };
</script>

<?php
$jsPagina = 'home-usuario.js';
require_once '../components/layout/base-fim.php';
?>

