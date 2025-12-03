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

// Dados do usuário logado (vem da sessão)
$usuario = [
    'id' => $_SESSION['usuario_id'],
    'nome' => $_SESSION['usuario_nome'] ?? 'Usuário',
    'email' => $_SESSION['usuario_email'] ?? '',
    'telefone' => $_SESSION['usuario_telefone'] ?? ''
];

// Estados disponíveis para filtro (todos os estados do Brasil)
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

// Dados mockados dos passeios (futuramente virá do banco)
$todosPasseios = [
    [
        'id' => 1,
        'imagem' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=400&h=300&fit=crop',
        'titulo' => 'Trilha da Serra',
        'descricao' => 'Aventura em meio à natureza com vistas incríveis.',
        'localizacao' => 'Serra do Mar - PR',
        'preco' => 75.00
    ],
    [
        'id' => 2,
        'imagem' => 'https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=400&h=300&fit=crop',
        'titulo' => 'Cachoeira Azul',
        'descricao' => 'Um dos destinos mais procurados para quem ama água e natureza.',
        'localizacao' => 'Vale do Ribeira - SP',
        'preco' => 120.00
    ],
    [
        'id' => 3,
        'imagem' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?w=400&h=300&fit=crop',
        'titulo' => 'City Tour Histórico',
        'descricao' => 'Explore os pontos turísticos e históricos da cidade.',
        'localizacao' => 'Curitiba - PR',
        'preco' => 95.00
    ],
    [
        'id' => 4,
        'imagem' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=300&fit=crop',
        'titulo' => 'Passeio de Barco',
        'descricao' => 'Navegue pelas águas cristalinas e descubra paisagens deslumbrantes.',
        'localizacao' => 'Paranaguá - PR',
        'preco' => 180.00
    ],
    [
        'id' => 5,
        'imagem' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop',
        'titulo' => 'Mirante das Nuvens',
        'descricao' => 'Vista panorâmica de tirar o fôlego no topo da montanha.',
        'localizacao' => 'Campos Gerais - PR',
        'preco' => 90.00
    ],
    [
        'id' => 6,
        'imagem' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&h=300&fit=crop',
        'titulo' => 'Praias do Sul',
        'descricao' => 'Conheça as praias mais bonitas do litoral catarinense.',
        'localizacao' => 'Florianópolis - SC',
        'preco' => 150.00
    ],
    [
        'id' => 7,
        'imagem' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&h=300&fit=crop',
        'titulo' => 'Vale dos Dinossauros',
        'descricao' => 'Explore o sítio paleontológico mais famoso da região.',
        'localizacao' => 'Ponta Grossa - PR',
        'preco' => 85.00
    ],
    [
        'id' => 8,
        'imagem' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=400&h=300&fit=crop',
        'titulo' => 'Pôr do Sol na Montanha',
        'descricao' => 'Assista ao pôr do sol mais bonito da região serrana.',
        'localizacao' => 'Morretes - PR',
        'preco' => 65.00
    ],
    [
        'id' => 9,
        'imagem' => 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=400&h=300&fit=crop',
        'titulo' => 'Mergulho em Ilha',
        'descricao' => 'Mergulhe nas águas cristalinas e veja a vida marinha.',
        'localizacao' => 'Bombinhas - SC',
        'preco' => 220.00
    ],
    [
        'id' => 10,
        'imagem' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=300&fit=crop',
        'titulo' => 'Floresta Encantada',
        'descricao' => 'Caminhe por trilhas cercadas de araucárias centenárias.',
        'localizacao' => 'Lapa - PR',
        'preco' => 70.00
    ],
];

// Filtrar passeios se houver busca ou filtro de estado
$passeiiosFiltrados = $todosPasseios;
$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$estadoFiltro = isset($_GET['estado']) ? $_GET['estado'] : '';

if (!empty($termoBusca)) {
    $passeiiosFiltrados = array_filter($passeiiosFiltrados, function($p) use ($termoBusca) {
        return stripos($p['titulo'], $termoBusca) !== false || 
               stripos($p['descricao'], $termoBusca) !== false ||
               stripos($p['localizacao'], $termoBusca) !== false;
    });
}

if (!empty($estadoFiltro)) {
    $passeiiosFiltrados = array_filter($passeiiosFiltrados, function($p) use ($estadoFiltro) {
        // Busca a sigla do estado na localização (ex: "PR" em "Serra do Mar - Paraná")
        return stripos($p['localizacao'], $estadoFiltro) !== false ||
               stripos($p['localizacao'], '- ' . $estadoFiltro) !== false;
    });
}

// Reindexar array após filtros
$passeiiosFiltrados = array_values($passeiiosFiltrados);

// Paginação
$itensPorPagina = 6;
$totalItens = count($passeiiosFiltrados);
$totalPaginas = ceil($totalItens / $itensPorPagina);
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$paginaAtual = min($paginaAtual, max(1, $totalPaginas)); // Não permite página maior que o total

// Pegar apenas os itens da página atual
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$passeiosPaginados = array_slice($passeiiosFiltrados, $inicio, $itensPorPagina);

// Função para manter os parâmetros de busca na paginação
function buildPaginationUrl($pagina) {
    $params = $_GET;
    $params['pagina'] = $pagina;
    return '?' . http_build_query($params);
}
?>

<main class="home-usuario">
    <!-- Header do Usuário -->
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
            <a href="#" onclick="abrirModalGuia()" class="btn-ser-guia">
                <i class="fas fa-user-tie"></i>
                <span>Quero ser Guia</span>
            </a>
            <a href="../../controller/LogoutController.php" class="btn-sair">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </section>

    <!-- Busca e Filtros -->
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

    <!-- Resultados -->
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
                    <div class="passeio-card">
                        <div class="passeio-card__imagem">
                            <img src="<?= htmlspecialchars($passeio['imagem']) ?>" alt="<?= htmlspecialchars($passeio['titulo']) ?>">
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
        telefone: '<?= addslashes($usuario['telefone']) ?>'
    };
</script>

<?php
$jsPagina = 'home-usuario.js';
require_once '../components/layout/base-fim.php';
?>

