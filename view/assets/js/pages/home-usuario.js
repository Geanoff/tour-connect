/**
 * JavaScript da página Home do Usuário
 * 
 * Variáveis esperadas (definidas no PHP):
 * - window.usuarioConfig.id
 * - window.usuarioConfig.nome
 * - window.usuarioConfig.email
 * - window.usuarioConfig.telefone
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
    
    // Dados que serão enviados ao backend
    const dados = {
        usuario_id: config.id,
        nome: config.nome,
        email: config.email,
        telefone: config.telefone,
        mensagem: mensagem
    };
    
    // Aqui você fará a chamada AJAX para o backend
    // Por enquanto, apenas simula o envio
    console.log('Dados da solicitação:', dados);
    
    // Simula sucesso
    alert('Solicitação enviada com sucesso!\n\nO administrador irá analisar seu pedido e entrar em contato em breve.');
    fecharModalGuia();
    
    // Futuramente:
    // fetch('../../controller/SolicitacaoGuiaController.php', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify(dados)
    // }).then(response => response.json())
    //   .then(data => { ... });
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

