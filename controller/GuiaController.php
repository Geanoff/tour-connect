<?php
/**
 * Controller de Guias
 * Gerencia CRUD de guias (para uso do admin)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/GuiaModel.php';
require_once __DIR__ . '/../model/ImagemModel.php';

$guiaModel = new GuiaModel();
$imagemModel = new ImagemModel();

$acao = $_POST['acao'] ?? $_GET['acao'] ?? null;

// Resposta padrão JSON
header('Content-Type: application/json');

switch ($acao) {
    case 'criar':
        // Faz upload da imagem se enviada
        $idImagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $idImagem = $imagemModel->uploadImagem($_FILES['imagem'], 'guias');
        }

        $dados = [
            'nome' => $_POST['nome'] ?? '',
            'id_imagem' => $idImagem,
            'especialidade' => $_POST['especialidade'] ?? ''
        ];

        $id = $guiaModel->criar($dados);

        if ($id) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Guia criado com sucesso', 'id' => $id]);
        } else {
            $erro = $guiaModel->ultimoErro ?: 'Erro desconhecido';
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao criar guia: ' . $erro]);
        }
        break;

    case 'listar':
        $guias = $guiaModel->listarTodos();
        echo json_encode(['sucesso' => true, 'guias' => $guias]);
        break;

    case 'buscar':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
            exit;
        }

        $guia = $guiaModel->buscarPorId($id);
        if ($guia) {
            echo json_encode(['sucesso' => true, 'guia' => $guia]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Guia não encontrado']);
        }
        break;

    case 'atualizar':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
            exit;
        }

        // Verifica se tem nova imagem
        $guiaAtual = $guiaModel->buscarPorId($id);
        $idImagemNova = null;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $idImagemNova = $imagemModel->uploadImagem($_FILES['imagem'], 'guias');
        }

        $dados = [
            'nome' => $_POST['nome'] ?? $guiaAtual['nome'],
            'id_imagem' => $idImagemNova,
            'especialidade' => $_POST['especialidade'] ?? $guiaAtual['especialidade']
        ];

        if ($guiaModel->atualizar($id, $dados)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Guia atualizado com sucesso']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar guia']);
        }
        break;

    case 'excluir':
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
            exit;
        }

        if ($guiaModel->excluir($id)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Guia excluído com sucesso']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir guia']);
        }
        break;

    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não especificada']);
        break;
}

