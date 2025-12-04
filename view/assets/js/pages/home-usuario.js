/**
 * JavaScript da página Home do Usuário
 */

// ========================================
// FUNÇÕES DO MODAL DE GUIA
// ========================================

function abrirModalGuia() {
    document.getElementById('modal-guia').classList.add('modal--ativo');
    document.body.style.overflow = 'hidden';
}

function fecharModalGuia() {
    document.getElementById('modal-guia').classList.remove('modal--ativo');
    document.body.style.overflow = 'auto';
}

function enviarSolicitacaoGuia(event) {
    event.preventDefault();
    
    const mensagem = document.querySelector('textarea[name="mensagem"]').value;
    const config = window.usuarioConfig;
    
    const formData = new FormData();
    formData.append('acao', 'criar');
    formData.append('nome', config.nome);
    formData.append('email', config.email);
    formData.append('telefone', config.telefone);
    formData.append('mensagem', mensagem);

    fetch('../../controller/SolicitacaoGuiaController.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(text => {
        console.log('Resposta do servidor:', text);
        try {
            const data = JSON.parse(text);
            if (data.sucesso) {
                alert('Solicitação enviada com sucesso!\n\nO administrador irá analisar seu pedido.');
                fecharModalGuia();
            } else {
                alert(data.mensagem);
            }
        } catch(e) {
            alert('Erro no servidor: ' + text);
        }
    })
    .catch(err => {
        alert('Erro de conexão: ' + err.message);
        console.error(err);
    });
}

// ========================================
// INICIALIZAÇÃO
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    // Fechar modal ao clicar fora
    const modalGuia = document.getElementById('modal-guia');
    if (modalGuia) {
        modalGuia.addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalGuia();
            }
        });
    }

    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fecharModalGuia();
        }
    });
});
