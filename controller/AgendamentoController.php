<?php
/**
 * Controller de Agendamentos
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/AgendamentoModel.php';

header('Content-Type: application/json');

$agendamentoModel = new AgendamentoModel();

$acao = $_POST['acao'] ?? $_GET['acao'] ?? null;

switch ($acao) {
    case 'criar':
        // Verifica se usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não logado']);
            exit;
        }

        $quantidadePessoas = intval($_POST['quantidade_pessoas'] ?? 1);
        
        // Pega os participantes do JSON
        $participantes = [];
        if (!empty($_POST['participantes'])) {
            $participantes = json_decode($_POST['participantes'], true) ?: [];
        }

        $dados = [
            'id_usuario' => $_SESSION['usuario_id'],
            'id_destino' => $_POST['id_destino'] ?? null,
            'id_guia' => $_POST['id_guia'] ?? null,
            'data_passeio' => $_POST['data_passeio'] ?? null,
            'horario' => $_POST['horario'] ?? null,
            'quantidade_pessoas' => $quantidadePessoas,
            'participantes' => $participantes
        ];

        // Validações
        if (!$dados['id_destino'] || !$dados['id_guia'] || !$dados['data_passeio'] || !$dados['horario']) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos']);
            exit;
        }

        // Verifica vagas disponíveis
        $vagas = $agendamentoModel->verificarVagasDisponiveis($dados['id_destino'], $dados['data_passeio'], $dados['horario']);
        if ($quantidadePessoas > $vagas['disponiveis']) {
            echo json_encode([
                'sucesso' => false, 
                'mensagem' => "Não há vagas suficientes. Disponíveis: {$vagas['disponiveis']} pessoa(s)"
            ]);
            exit;
        }

        $id = $agendamentoModel->criar($dados);

        if ($id) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Agendamento realizado com sucesso!', 'id' => $id]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao criar agendamento']);
        }
        break;

    case 'verificar_vagas':
        $idDestino = $_GET['id_destino'] ?? null;
        $data = $_GET['data'] ?? null;
        $horario = $_GET['horario'] ?? null;

        if (!$idDestino || !$data || !$horario) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos']);
            exit;
        }

        $vagas = $agendamentoModel->verificarVagasDisponiveis($idDestino, $data, $horario);
        echo json_encode(['sucesso' => true, 'vagas' => $vagas]);
        break;

    case 'listar':
        // Lista agendamentos do usuário logado
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não logado']);
            exit;
        }

        $agendamentos = $agendamentoModel->buscarPorUsuario($_SESSION['usuario_id']);
        echo json_encode(['sucesso' => true, 'agendamentos' => $agendamentos]);
        break;

    case 'listar_todos':
        // Lista todos os agendamentos (para admin)
        $agendamentos = $agendamentoModel->listarTodos();
        echo json_encode(['sucesso' => true, 'agendamentos' => $agendamentos]);
        break;

    case 'atualizar_status':
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos']);
            exit;
        }

        if ($agendamentoModel->atualizarStatus($id, $status)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Status atualizado']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar status']);
        }
        break;

    case 'excluir':
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não logado']);
            exit;
        }

        $id = $_POST['id'] ?? $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
            exit;
        }

        // Exclui apenas se o agendamento pertencer ao usuário
        if ($agendamentoModel->excluir($id, $_SESSION['usuario_id'])) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Agendamento excluído']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir agendamento']);
        }
        break;

    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não especificada']);
        break;
}

