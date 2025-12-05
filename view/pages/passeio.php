<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuarioLogado = isset($_SESSION['usuario_id']);
$usuarioNome = $_SESSION['usuario_nome'] ?? '';

$tituloPagina = 'Detalhes do Passeio';
$cssPagina = 'passeio.css';
require_once '../components/layout/base-inicio.php';
require_once '../../model/DestinoModel.php';

$destinoId = $_GET['id'] ?? 1;

$modelDestino = new DestinoModel();

$destino = $modelDestino->buscarPorId($destinoId);

// Se não encontrou, redireciona
if (!$destino) {
    header('Location: inicio.php');
    exit;
}

// Ajusta caminho das imagens (adiciona ../../ se for local)
if (!empty($destino['imagem']) && strpos($destino['imagem'], 'http') !== 0) {
    $destino['imagem'] = '../../' . $destino['imagem'];
}

// Ajusta array de imagens para a galeria
if (!empty($destino['imagens'])) {
    $destino['imagens'] = array_map(function ($img) {
        if (strpos($img, 'http') !== 0) {
            return '../../' . $img;
        }
        return $img;
    }, $destino['imagens']);
} else {
    // Se não tem imagens adicionais, usa só a principal
    $destino['imagens'] = [$destino['imagem']];
}

// Busca apenas os guias vinculados a este passeio
$guiasDb = $modelDestino->buscarGuiasDestino($destinoId);

// Formata os guias para o formato esperado
$guias = array_map(function ($guia) {
    // Ajusta caminho da imagem
    $imagem = $guia['imagem'] ?? '';
    if ($imagem && strpos($imagem, 'http') !== 0) {
        $imagem = '../../' . $imagem;
    }

    return [
        'id' => $guia['id'],
        'nome' => $guia['nome'],
        'foto' => $imagem ?: 'https://via.placeholder.com/150x150?text=Guia',
        'especialidade' => $guia['especialidade'] ?? 'Guia Turístico'
    ];
}, $guiasDb);

$horarios = ['06:00', '07:00', '08:00', '14:00', '15:00'];
?>

<main class="passeio-page">
    <header class="bg-white !p-3 !mb-6 rounded-md flex justify-between">
        <a href="home-usuario.php"
            class="flex items-center gap-2 bg-slate-100 !p-3 rounded-lg hover:bg-slate-200 font-semibold text-sm">
            <i class="fa-solid fa-arrow-left"></i>
            VOLTAR
        </a>
        <a href="../../controller/LogoutController.php"
            class="!p-3 bg-slate-100 hover:bg-red-500 transition rounded-lg">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </header>
    <section class="galeria">
        <div class="galeria__principal">
            <img id="imagem-principal" src="<?= htmlspecialchars($destino['imagem']) ?>"
                alt="<?= htmlspecialchars($destino['titulo']) ?>">
            <button class="galeria__nav galeria__nav--prev" onclick="mudarImagem(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="galeria__nav galeria__nav--next" onclick="mudarImagem(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="galeria__miniaturas">
            <?php foreach ($destino['imagens'] as $index => $imagem): ?>
                <img src="<?= htmlspecialchars($imagem) ?>" alt="Miniatura <?= $index + 1 ?>"
                    class="galeria__thumb <?= $index === 0 ? 'galeria__thumb--ativa' : '' ?>"
                    onclick="selecionarImagem(<?= $index ?>)">
            <?php endforeach; ?>
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
            <?php if (empty($guias)): ?>
                <div class="sem-guias">
                    <i class="fas fa-user-slash"></i>
                    <p>Nenhum guia disponível no momento.</p>
                    <span>Em breve teremos guias disponíveis para este passeio!</span>
                </div>
            <?php else: ?>
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
            <?php endif; ?>
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
                        <button class="horario-btn" data-horario="<?= $horario ?>"
                            onclick="selecionarHorario('<?= $horario ?>')">
                            <?= $horario ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <p id="vagas-info" class="vagas-info" style="display: none;"></p>
            </div>
        </div>

        <div class="agendamento__participantes">
            <h3><i class="fas fa-users"></i> Participantes</h3>
            <div class="participantes-header">
                <label>Quantidade de pessoas:</label>
                <div class="quantidade-controle">
                    <button type="button" class="btn-qtd" onclick="alterarQuantidade(-1)">-</button>
                    <input type="number" id="quantidade-pessoas" value="1" min="1" max="10" readonly>
                    <button type="button" class="btn-qtd" onclick="alterarQuantidade(1)">+</button>
                </div>
            </div>
            <div id="lista-participantes" class="lista-participantes">
                <div class="participante-item" data-index="0">
                    <input type="text" value="<?= htmlspecialchars($usuarioNome) ?>" class="input-nome" readonly
                        style="background: #e9ecef;">
                    <input type="number" placeholder="Sua idade" class="input-idade" min="1" max="120" required>
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
                    <span class="resumo-valor resumo-valor--preco">R$
                        <?= number_format($destino['preco'], 2, ',', '.') ?></span>
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
    const imagens = <?= json_encode($destino['imagens']) ?>;
    let imagemAtual = 0;

    // Dados dos guias
    const guiasData = <?= json_encode($guias) ?>;

    // Estado do agendamento
    let agendamento = {
        guiaId: null,
        guiaNome: null,
        data: null,
        horario: null,
        quantidadePessoas: 1
    };

    // Preço do passeio
    const precoPorPessoa = <?= $destino['preco'] ?>;

    // Funções de Quantidade/Participantes
    function alterarQuantidade(delta) {
        const input = document.getElementById('quantidade-pessoas');
        let valor = parseInt(input.value) + delta;
        if (valor < 1) valor = 1; // Mínimo 1 (o próprio usuário)
        if (valor > 10) valor = 10;
        input.value = valor;
        agendamento.quantidadePessoas = valor;
        atualizarListaParticipantes(valor);
        atualizarPrecoTotal();
        verificarVagas();
    }

    // Nome do usuário para o primeiro participante
    const nomeUsuario = '<?= addslashes($usuarioNome) ?>';

    function atualizarListaParticipantes(quantidade) {
        const container = document.getElementById('lista-participantes');
        const atual = container.querySelectorAll('.participante-item').length;

        if (quantidade > atual) {
            for (let i = atual; i < quantidade; i++) {
                const div = document.createElement('div');
                div.className = 'participante-item';
                div.dataset.index = i;
                div.innerHTML = `
                <input type="text" placeholder="Nome do acompanhante ${i}" class="input-nome" required>
                <input type="number" placeholder="Idade" class="input-idade" min="1" max="120" required>
            `;
                container.appendChild(div);
            }
        } else if (quantidade < atual) {
            const items = container.querySelectorAll('.participante-item');
            // Não remove o primeiro (usuário logado)
            for (let i = atual - 1; i >= quantidade && i > 0; i--) {
                items[i].remove();
            }
        }
    }

    function atualizarPrecoTotal() {
        const total = precoPorPessoa * agendamento.quantidadePessoas;
        document.querySelector('.resumo-valor--preco').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
    }

    function getParticipantes() {
        const participantes = [];
        document.querySelectorAll('.participante-item').forEach(item => {
            const nome = item.querySelector('.input-nome').value.trim();
            const idade = parseInt(item.querySelector('.input-idade').value) || 0;
            if (nome && idade > 0) {
                participantes.push({ nome, idade });
            }
        });
        return participantes;
    }

    function verificarVagas() {
        if (!agendamento.data || !agendamento.horario) return;

        fetch(`../../controller/AgendamentoController.php?acao=verificar_vagas&id_destino=<?= $destinoId ?>&data=${agendamento.data}&horario=${agendamento.horario}`)
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    const info = document.getElementById('vagas-info');
                    info.style.display = 'block';
                    if (data.vagas.disponiveis <= 0) {
                        info.innerHTML = '<i class="fas fa-times-circle"></i> Esgotado para este horário';
                        info.className = 'vagas-info vagas-esgotado';
                    } else {
                        info.innerHTML = `<i class="fas fa-check-circle"></i> ${data.vagas.disponiveis} vaga(s) disponível(is)`;
                        info.className = 'vagas-info vagas-disponiveis';
                    }
                }
            });
    }

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
        // Converte para número para comparação
        guiaId = parseInt(guiaId);
        const guia = guiasData.find(g => parseInt(g.id) === guiaId);

        if (!guia) {
            console.error('Guia não encontrado:', guiaId);
            return;
        }

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
        verificarVagas();
    }

    // Atualizar data no resumo
    document.getElementById('data-passeio').addEventListener('change', function () {
        agendamento.data = this.value;
        const dataFormatada = new Date(this.value + 'T00:00:00').toLocaleDateString('pt-BR');
        document.getElementById('resumo-data').textContent = dataFormatada;
        verificarVagas();
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

        // Valida participantes
        const participantes = getParticipantes();
        if (participantes.length !== agendamento.quantidadePessoas) {
            alert('Por favor, preencha o nome e idade de todos os participantes.');
            return;
        }

        // Envia para o backend
        const formData = new FormData();
        formData.append('acao', 'criar');
        formData.append('id_destino', <?= $destinoId ?>);
        formData.append('id_guia', agendamento.guiaId);
        formData.append('data_passeio', agendamento.data);
        formData.append('horario', agendamento.horario);
        formData.append('quantidade_pessoas', agendamento.quantidadePessoas);
        formData.append('participantes', JSON.stringify(participantes));

        fetch('../../controller/AgendamentoController.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Agendamento confirmado com sucesso!');
                    window.location.href = 'home-usuario.php';
                } else {
                    alert('Erro: ' + data.mensagem);
                }
            })
            .catch(err => {
                alert('Erro ao processar agendamento');
                console.error(err);
            });
    }
</script>

<?php
$jsPagina = 'passeio.js';
require_once '../components/layout/base-fim.php';
?>