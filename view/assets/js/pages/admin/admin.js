/**
 * Painel Administrativo - Funções Base e Inicialização
 * Navegação, Toast, Modal, Helpers e Init
 */

// Navegação entre tabs
document.querySelectorAll('.sidebar nav a[data-tab]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const tab = link.dataset.tab;
        
        document.querySelectorAll('.sidebar nav a').forEach(l => l.classList.remove('ativo'));
        link.classList.add('ativo');
        
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('ativo'));
        document.getElementById('tab-' + tab).classList.add('ativo');
    });
});

// Toast de notificação
function mostrarToast(mensagem, tipo = 'sucesso') {
    const toast = document.getElementById('toast');
    toast.textContent = mensagem;
    toast.className = 'toast ' + tipo + ' ativo';
    setTimeout(() => toast.classList.remove('ativo'), 3000);
}

// Modal - Abrir
function abrirModal(id) {
    document.getElementById(id).classList.add('ativo');
}

// Modal - Fechar
function fecharModal(id) {
    document.getElementById(id).classList.remove('ativo');
}

// Helper para ajustar caminho de imagem (URL externa ou local)
function ajustarImagem(caminho, placeholder = 'https://via.placeholder.com/400x200') {
    if (!caminho) return placeholder;
    if (caminho.startsWith('http')) return caminho;
    return '../../../' + caminho;
}

// Fechar modal ao clicar fora
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) fecharModal(modal.id);
    });
});

// Carregar dados ao iniciar
document.addEventListener('DOMContentLoaded', function() {
    carregarPasseios();
    carregarGuias();
    carregarAgendamentos();
    carregarSolicitacoes();
});

