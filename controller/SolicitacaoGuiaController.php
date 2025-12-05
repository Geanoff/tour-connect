<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/SolicitacaoGuiaModel.php';

$model = new SolicitacaoGuiaModel();

$acao = isset($_POST['acao']) ? $_POST['acao'] : (isset($_GET['acao']) ? $_GET['acao'] : '');

switch ($acao) {
    case 'criar':
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Usuario nao logado']);
            exit;
        }

        $dados = [
            'id_usuario' => $_SESSION['usuario_id'],
            'nome' => isset($_POST['nome']) ? $_POST['nome'] : '',
            'email' => isset($_POST['email']) ? $_POST['email'] : '',
            'telefone' => isset($_POST['telefone']) ? $_POST['telefone'] : '',
            'mensagem' => isset($_POST['mensagem']) ? $_POST['mensagem'] : ''
        ];

        if (empty($dados['nome']) || empty($dados['email'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Nome e email obrigatorios']);
            exit;
        }

        $id = $model->criar($dados);

        if ($id) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Solicitacao enviada!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro: ' . $model->ultimoErro]);
        }
        break;

    case 'listar':
        $solicitacoes = $model->listarTodas();
        $naoLidas = $model->contarNaoLidas();
        echo json_encode(['sucesso' => true, 'solicitacoes' => $solicitacoes, 'nao_lidas' => $naoLidas]);
        break;

    case 'marcar_lida':
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if ($model->marcarComoLida($id)) {
            echo json_encode(['sucesso' => true]);
        } else {
            echo json_encode(['sucesso' => false]);
        }
        break;

    case 'excluir':
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if ($model->excluir($id)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Excluido']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir']);
        }
        break;

    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Acao invalida']);
}
