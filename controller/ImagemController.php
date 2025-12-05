<?php
/**
 * Controller de Imagens
 * Gerencia upload e exclusão de imagens
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/ImagemModel.php';

$imagemModel = new ImagemModel();
$acao = $_POST['acao'] ?? $_GET['acao'] ?? null;

// Resposta padrão JSON
header('Content-Type: application/json');

switch ($acao) {
    case 'upload':
        if (!isset($_FILES['imagem'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhuma imagem enviada']);
            exit;
        }

        $pasta = $_POST['pasta'] ?? 'destinos';
        $idImagem = $imagemModel->uploadImagem($_FILES['imagem'], $pasta);

        if ($idImagem) {
            $imagem = $imagemModel->buscarPorId($idImagem);
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Imagem enviada com sucesso',
                'id' => $idImagem,
                'caminho' => $imagem['caminho']
            ]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao fazer upload da imagem']);
        }
        break;

    case 'excluir':
        $id = $_POST['id'] ?? $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID da imagem não informado']);
            exit;
        }

        if ($imagemModel->excluir($id)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Imagem excluída com sucesso']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir imagem']);
        }
        break;

    case 'listar':
        $imagens = $imagemModel->listarTodas();
        echo json_encode(['sucesso' => true, 'imagens' => $imagens]);
        break;

    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não especificada']);
        break;
}

