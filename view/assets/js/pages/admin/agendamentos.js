/**
 * Gerenciamento de Agendamentos - Painel Admin
 */

// ========== AGENDAMENTOS ==========
function carregarAgendamentos() {
    fetch('../../../controller/AgendamentoController.php?acao=listar_todos')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('lista-agendamentos');
            if (data.agendamentos && data.agendamentos.length > 0) {
                container.innerHTML = data.agendamentos.map(a => {
                    const dataFormatada = new Date(a.data_passeio + 'T00:00:00').toLocaleDateString('pt-BR');
                    return `
                    <div class="agendamento-admin">
                        <div class="agendamento-admin__info">
                            <span class="agendamento-admin__passeio">${a.passeio_titulo}</span>
                            <div class="agendamento-admin__detalhes">
                                <span><i class="fas fa-map-marker-alt"></i> ${a.passeio_localizacao}</span>
                                <span><i class="fas fa-calendar"></i> ${dataFormatada}</span>
                                <span><i class="fas fa-clock"></i> ${a.horario}</span>
                                <span><i class="fas fa-users"></i> ${a.quantidade_pessoas || 1} pessoa(s)</span>
                                <span><i class="fas fa-user-tie"></i> ${a.guia_nome}</span>
                            </div>
                        </div>
                        <div class="agendamento-admin__usuario">
                            <strong>${a.usuario_nome}</strong>
                            <small><i class="fas fa-phone"></i> ${a.usuario_telefone || 'Sem telefone'}</small>
                        </div>
                        <span class="agendamento-admin__status status-${a.status}">${a.status === 'agendado' ? 'Agendado' : 'Concluído'}</span>
                        <div class="agendamento-admin__acoes">
                            ${a.status === 'agendado' ? `<button class="btn btn-primary btn-sm" onclick="concluirAgendamento(${a.id})"><i class="fas fa-check"></i> Concluir</button>` : ''}
                        </div>
                    </div>
                `}).join('');
            } else {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>Nenhum agendamento</p>
                    </div>
                `;
            }
        });
}

function concluirAgendamento(id) {
    if (!confirm('Marcar este agendamento como concluído?')) return;
    
    fetch('../../../controller/AgendamentoController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `acao=atualizar_status&id=${id}&status=concluido`
    })
    .then(res => res.json())
    .then(data => {
        mostrarToast(data.mensagem, data.sucesso ? 'sucesso' : 'erro');
        if (data.sucesso) carregarAgendamentos();
    });
}

