/**
 * Sistema de busca e seleção de guias por tags
 */

// Lista de guias disponíveis (carregada uma vez)
let guiasDisponiveis = [];
let guiasSelecionadosAtual = [];

// Renderiza as tags dos guias selecionados
function renderizarGuiasTags() {
    const container = document.getElementById('guias-selecionados');
    container.innerHTML = guiasSelecionadosAtual.map(g => `
        <span class="guia-tag" data-id="${g.id}">
            <img src="${ajustarImagem(g.imagem, 'https://via.placeholder.com/24')}" alt="${g.nome}">
            ${g.nome}
            <span class="remover-guia" data-guia-id="${g.id}">×</span>
        </span>
    `).join('');
    
    // Adiciona eventos de clique para remover
    container.querySelectorAll('.remover-guia').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = parseInt(this.dataset.guiaId);
            removerGuia(id);
        });
    });
}

// Adiciona um guia à seleção
function adicionarGuia(guia) {
    const guiaId = parseInt(guia.id);
    if (!guiasSelecionadosAtual.find(g => parseInt(g.id) === guiaId)) {
        guiasSelecionadosAtual.push({
            id: guiaId,
            nome: guia.nome,
            imagem: guia.imagem
        });
        renderizarGuiasTags();
    }
    document.getElementById('busca-guia').value = '';
    document.getElementById('guias-sugestoes').classList.remove('ativo');
}

// Remove um guia da seleção
function removerGuia(id) {
    id = parseInt(id);
    guiasSelecionadosAtual = guiasSelecionadosAtual.filter(g => parseInt(g.id) !== id);
    renderizarGuiasTags();
}

// Filtra e mostra sugestões
function filtrarGuias(termo) {
    const dropdown = document.getElementById('guias-sugestoes');
    
    if (!termo.trim()) {
        dropdown.classList.remove('ativo');
        return;
    }
    
    const termoLower = termo.toLowerCase();
    const resultados = guiasDisponiveis.filter(g => 
        g.nome.toLowerCase().includes(termoLower)
    );
    
    if (resultados.length === 0) {
        dropdown.innerHTML = '<div class="sem-resultado">Nenhum guia encontrado</div>';
    } else {
        dropdown.innerHTML = resultados.map(g => {
            const jaSelecionado = guiasSelecionadosAtual.find(gs => gs.id === g.id);
            return `
                <div class="guia-opcao ${jaSelecionado ? 'ja-selecionado' : ''}" onclick="adicionarGuia({id: ${g.id}, nome: '${g.nome.replace(/'/g, "\\'")}', imagem: '${g.imagem || ''}'})">
                    <img src="${ajustarImagem(g.imagem, 'https://via.placeholder.com/35')}" alt="${g.nome}">
                    <div class="guia-opcao-info">
                        <div class="guia-opcao-nome">${g.nome}</div>
                        <div class="guia-opcao-espec">${g.especialidade || 'Guia'}</div>
                    </div>
                    ${jaSelecionado ? '<span style="color: #00d9ff; font-size: 0.8rem;">✓ Adicionado</span>' : ''}
                </div>
            `;
        }).join('');
    }
    
    dropdown.classList.add('ativo');
}

// Pega os IDs dos guias selecionados
function getGuiasSelecionados() {
    return guiasSelecionadosAtual.map(g => g.id);
}

// Carrega guias selecionados ao editar
function carregarGuiasSelecionados(ids) {
    const idsNumericos = ids.map(id => parseInt(id));
    guiasSelecionadosAtual = guiasDisponiveis.filter(g => idsNumericos.includes(parseInt(g.id)));
    renderizarGuiasTags();
}

// Evento de busca
document.getElementById('busca-guia').addEventListener('input', function() {
    filtrarGuias(this.value);
});

// Fecha dropdown ao clicar fora
document.addEventListener('click', function(e) {
    if (!e.target.closest('.guias-search-container')) {
        document.getElementById('guias-sugestoes').classList.remove('ativo');
    }
});

