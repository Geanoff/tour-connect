/**
 * Gerenciamento de Guias - Painel Admin
 */

// ========== GUIAS ==========
function carregarGuias() {
    fetch('../../../controller/GuiaController.php?acao=listar')
        .then(res => res.json())
        .then(data => {
            guiasDisponiveis = data.guias || [];
            
            const container = document.getElementById('lista-guias');
            if (data.guias && data.guias.length > 0) {
                container.innerHTML = data.guias.map(g => `
                    <div class="card card-guia">
                        <img src="${ajustarImagem(g.imagem)}" alt="${g.nome}">
                        <div class="card-body">
                            <h3 class="card-title">${g.nome}</h3>
                            <p class="card-text">${g.especialidade}</p>
                            <div class="card-actions">
                                <button class="btn btn-primary btn-sm" onclick="editarGuia(${g.id})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="excluirGuia(${g.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-user-tie"></i>
                        <p>Nenhum guia cadastrado</p>
                    </div>
                `;
            }
        });
}

function abrirModalGuia() {
    document.getElementById('form-guia').reset();
    document.getElementById('guia-id').value = '';
    document.getElementById('guia-acao').value = 'criar';
    document.getElementById('modal-guia-titulo').textContent = 'Novo Guia';
    abrirModal('modal-guia');
}

function editarGuia(id) {
    fetch(`../../../controller/GuiaController.php?acao=buscar&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.guia) {
                const g = data.guia;
                document.getElementById('guia-id').value = g.id;
                document.getElementById('guia-acao').value = 'atualizar';
                document.getElementById('guia-nome').value = g.nome;
                document.getElementById('guia-especialidade').value = g.especialidade;
                document.getElementById('modal-guia-titulo').textContent = 'Editar Guia';
                abrirModal('modal-guia');
            }
        });
}

function excluirGuia(id) {
    if (confirm('Tem certeza que deseja excluir este guia?')) {
        fetch(`../../../controller/GuiaController.php?acao=excluir&id=${id}`)
            .then(res => res.json())
            .then(data => {
                mostrarToast(data.mensagem, data.sucesso ? 'sucesso' : 'erro');
                if (data.sucesso) carregarGuias();
            });
    }
}

// Submit do formulário de guia
document.getElementById('form-guia').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('../../../controller/GuiaController.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        mostrarToast(data.mensagem, data.sucesso ? 'sucesso' : 'erro');
        if (data.sucesso) {
            fecharModal('modal-guia');
            carregarGuias();
        }
    });
});

