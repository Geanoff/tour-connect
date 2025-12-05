/**
 * Gerenciamento de Passeios - Painel Admin
 */

// ========== PASSEIOS ==========
function carregarPasseios() {
    fetch('../../../controller/DestinoController.php?acao=listar')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('lista-passeios');
            if (data.destinos && data.destinos.length > 0) {
                container.innerHTML = data.destinos.map(p => `
                    <div class="card">
                        <img src="${ajustarImagem(p.imagem)}" alt="${p.titulo}">
                        <div class="card-body">
                            <h3 class="card-title">${p.titulo}</h3>
                            <p class="card-text">${p.descricao_curta || p.localizacao}</p>
                            <span class="card-price">R$ ${parseFloat(p.preco).toFixed(2).replace('.', ',')}</span>
                            <div class="card-actions">
                                <button class="btn btn-primary btn-sm" onclick="editarPasseio(${p.id})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="excluirPasseio(${p.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-mountain"></i>
                        <p>Nenhum passeio cadastrado</p>
                    </div>
                `;
            }
        });
}

function abrirModalPasseio() {
    document.getElementById('form-passeio').reset();
    document.getElementById('passeio-id').value = '';
    document.getElementById('passeio-acao').value = 'criar';
    document.getElementById('modal-passeio-titulo').textContent = 'Novo Passeio';
    document.getElementById('preview-imagens').innerHTML = '';
    document.getElementById('busca-guia').value = '';
    guiasSelecionadosAtual = [];
    renderizarGuiasTags();
    abrirModal('modal-passeio');
}

function editarPasseio(id) {
    const carregarEEditar = () => {
        fetch(`../../../controller/DestinoController.php?acao=buscar&id=${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.destino) {
                    const p = data.destino;
                    document.getElementById('passeio-id').value = p.id;
                    document.getElementById('passeio-acao').value = 'atualizar';
                    document.getElementById('passeio-titulo').value = p.titulo;
                    document.getElementById('passeio-descricao-curta').value = p.descricao_curta || '';
                    document.getElementById('passeio-descricao').value = p.descricao || '';
                    document.getElementById('passeio-localizacao').value = p.localizacao || '';
                    document.getElementById('passeio-preco').value = p.preco || 0;
                    document.getElementById('passeio-duracao').value = p.duracao || '';
                    document.getElementById('passeio-dificuldade').value = p.dificuldade || 'Fácil';
                    document.getElementById('modal-passeio-titulo').textContent = 'Editar Passeio';
                    document.getElementById('preview-imagens').innerHTML = '';
                    document.getElementById('busca-guia').value = '';
                    
                    const guiasIds = (p.guias_ids || []).map(id => parseInt(id));
                    carregarGuiasSelecionados(guiasIds);
                    
                    abrirModal('modal-passeio');
                }
            });
    };
    
    if (guiasDisponiveis.length === 0) {
        fetch('../../../controller/GuiaController.php?acao=listar')
            .then(res => res.json())
            .then(data => {
                guiasDisponiveis = data.guias || [];
                carregarEEditar();
            });
    } else {
        carregarEEditar();
    }
}

function excluirPasseio(id) {
    if (confirm('Tem certeza que deseja excluir este passeio?')) {
        fetch(`../../../controller/DestinoController.php?acao=excluir&id=${id}`)
            .then(res => res.json())
            .then(data => {
                mostrarToast(data.mensagem, data.sucesso ? 'sucesso' : 'erro');
                if (data.sucesso) carregarPasseios();
            });
    }
}

// Submit do formulário de passeio
document.getElementById('form-passeio').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    const guiasSelecionados = getGuiasSelecionados();
    formData.append('guias', JSON.stringify(guiasSelecionados));
    
    fetch('../../../controller/DestinoController.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        mostrarToast(data.mensagem, data.sucesso ? 'sucesso' : 'erro');
        if (data.sucesso) {
            fecharModal('modal-passeio');
            carregarPasseios();
        }
    });
});

// Preview de imagens
document.getElementById('passeio-imagens').addEventListener('change', function(e) {
    const preview = document.getElementById('preview-imagens');
    preview.innerHTML = '';
    
    const files = Array.from(this.files).slice(0, 5);
    
    if (this.files.length > 5) {
        mostrarToast('Máximo 5 imagens! Apenas as 5 primeiras serão usadas.', 'erro');
    }
    
    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.style.cssText = 'position: relative; width: 80px; height: 80px;';
            div.innerHTML = `
                <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px; border: 2px solid ${index === 0 ? '#00d9ff' : '#0f3460'};">
                ${index === 0 ? '<span style="position: absolute; top: -8px; right: -8px; background: #00d9ff; color: #1a1a2e; font-size: 10px; padding: 2px 6px; border-radius: 10px;">Principal</span>' : ''}
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});

