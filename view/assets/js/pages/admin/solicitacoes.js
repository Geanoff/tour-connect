/**
 * Gerenciamento de Solicitações de Guia - Painel Admin
 */

// ========== SOLICITAÇÕES ==========
function carregarSolicitacoes() {
    fetch('../../../controller/SolicitacaoGuiaController.php?acao=listar')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('lista-solicitacoes');
            const badge = document.getElementById('badge-solicitacoes');
            
            if (data.nao_lidas > 0) {
                badge.textContent = data.nao_lidas;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
            
            if (data.solicitacoes && data.solicitacoes.length > 0) {
                container.innerHTML = data.solicitacoes.map(s => {
                    const dataFormatada = new Date(s.data_criacao).toLocaleString('pt-BR');
                    const classe = s.lida == 0 ? 'nao-lida' : 'lida';
                    return `
                    <div class="solicitacao-card ${classe}" data-id="${s.id}">
                        <div class="solicitacao-info">
                            <h4>${s.nome} ${s.lida == 0 ? '<span class="nova">Nova</span>' : ''}</h4>
                            <p><i class="fas fa-envelope"></i> ${s.email}</p>
                            <p><i class="fas fa-phone"></i> ${s.telefone || 'Não informado'}</p>
                            ${s.mensagem ? `<div class="mensagem">"${s.mensagem}"</div>` : ''}
                        </div>
                        <div>
                            <p class="solicitacao-data"><i class="fas fa-clock"></i> ${dataFormatada}</p>
                            <div class="solicitacao-acoes">
                                ${s.lida == 0 ? `<button class="btn btn-primary btn-sm" onclick="marcarLida(${s.id})"><i class="fas fa-check"></i> Marcar lida</button>` : ''}
                                <button class="btn btn-danger btn-sm" onclick="excluirSolicitacao(${s.id})"><i class="fas fa-trash"></i> Excluir</button>
                            </div>
                        </div>
                    </div>
                `}).join('');
            } else {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Nenhuma solicitação</p>
                    </div>
                `;
            }
        });
}

function marcarLida(id) {
    fetch('../../../controller/SolicitacaoGuiaController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `acao=marcar_lida&id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.sucesso) carregarSolicitacoes();
    });
}

function excluirSolicitacao(id) {
    if (!confirm('Excluir esta solicitação?')) return;
    
    fetch('../../../controller/SolicitacaoGuiaController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `acao=excluir&id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        mostrarToast(data.mensagem, data.sucesso ? 'sucesso' : 'erro');
        if (data.sucesso) carregarSolicitacoes();
    });
}

