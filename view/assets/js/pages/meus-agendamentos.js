/**
 * JavaScript da página Meus Agendamentos
 */

// Estado do cancelamento
let agendamentoParaCancelar = null;

// ========================================
// FUNÇÕES DO MODAL DE CANCELAMENTO
// ========================================

function cancelarAgendamento(id) {
    agendamentoParaCancelar = id;
    document.getElementById('modal-cancelar').classList.add('modal--ativo');
    document.body.style.overflow = 'hidden';
}

function fecharModalCancelar() {
    document.getElementById('modal-cancelar').classList.remove('modal--ativo');
    document.body.style.overflow = 'auto';
    agendamentoParaCancelar = null;
}

function confirmarCancelamento() {
    if (agendamentoParaCancelar) {
        // Aqui você fará a chamada AJAX para o backend
        // Por enquanto, simula o cancelamento visual
        const card = document.querySelector(`.agendamento-card[data-id="${agendamentoParaCancelar}"]`);
        if (card) {
            const statusEl = card.querySelector('.agendamento-card__status');
            statusEl.className = 'agendamento-card__status status--cancelado';
            statusEl.innerHTML = '<i class="fas fa-times-circle"></i> Cancelado';
            
            const btnCancelar = card.querySelector('.btn-cancelar');
            if (btnCancelar) btnCancelar.remove();
        }
        
        fecharModalCancelar();
        
        // Mostra mensagem de sucesso (você pode usar seu componente de toast)
        alert('Agendamento cancelado com sucesso!');
    }
}

// ========================================
// INICIALIZAÇÃO
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    // Fechar modal ao clicar fora
    const modalCancelar = document.getElementById('modal-cancelar');
    if (modalCancelar) {
        modalCancelar.addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalCancelar();
            }
        });
    }

    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fecharModalCancelar();
        }
    });
});

