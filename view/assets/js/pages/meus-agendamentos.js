/**
 * JavaScript da página Meus Agendamentos
 */

// Função para excluir agendamento
function excluirAgendamento(id) {
    if (!confirm('Tem certeza que deseja excluir este agendamento?')) {
        return;
    }

    fetch('../../controller/AgendamentoController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `acao=excluir&id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.sucesso) {
            alert('Agendamento excluído com sucesso!');
            // Recarrega a página para atualizar a lista
            window.location.reload();
        } else {
            alert('Erro: ' + data.mensagem);
        }
    })
    .catch(err => {
        alert('Erro ao excluir agendamento');
        console.error(err);
    });
}
