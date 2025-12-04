/**
 * JavaScript da página de Passeio
 * 
 * Variáveis esperadas (definidas no PHP):
 * - window.passeioConfig.usuarioLogado
 * - window.passeioConfig.imagens
 * - window.passeioConfig.guias
 */

// Estado da galeria
let imagemAtual = 0;

// Estado do agendamento
let agendamento = {
    guiaId: null,
    guiaNome: null,
    data: null,
    horario: null
};

// ========================================
// FUNÇÕES DA GALERIA
// ========================================

function mudarImagem(direcao) {
    const imagens = window.passeioConfig.imagens;
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
    const imagens = window.passeioConfig.imagens;
    document.getElementById('imagem-principal').src = imagens[imagemAtual];
    document.querySelectorAll('.galeria__thumb').forEach((thumb, index) => {
        thumb.classList.toggle('galeria__thumb--ativa', index === imagemAtual);
    });
}

// ========================================
// FUNÇÕES DE AGENDAMENTO
// ========================================

function selecionarGuia(guiaId) {
    const guiasData = window.passeioConfig.guias;
    const guia = guiasData.find(g => g.id === guiaId);
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
}

function confirmarAgendamento() {
    // Verifica se o usuário está logado
    if (!window.passeioConfig.usuarioLogado) {
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

    // Aqui você pode enviar para o backend via AJAX
    alert(`Agendamento confirmado!\n\nGuia: ${agendamento.guiaNome}\nData: ${document.getElementById('resumo-data').textContent}\nHorário: ${agendamento.horario}`);
}

// ========================================
// INICIALIZAÇÃO
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    // Event listener para o campo de data
    const dataInput = document.getElementById('data-passeio');
    if (dataInput) {
        dataInput.addEventListener('change', function() {
            agendamento.data = this.value;
            const dataFormatada = new Date(this.value + 'T00:00:00').toLocaleDateString('pt-BR');
            document.getElementById('resumo-data').textContent = dataFormatada;
        });
    }
});

