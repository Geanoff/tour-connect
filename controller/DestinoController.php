<?php
/**
 * Controller de Destinos/Passeios
 * Gerencia CRUD de destinos (para uso do admin)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/DestinoModel.php';
require_once __DIR__ . '/../model/ImagemModel.php';

$destinoModel = new DestinoModel();
$imagemModel = new ImagemModel();

$acao = $_POST['acao'] ?? $_GET['acao'] ?? null;

// Resposta padrão JSON
header('Content-Type: application/json');

switch ($acao) {
    case 'criar':
        // Faz upload das imagens (até 5)
        $idImagemPrincipal = null;
        $imagensIds = [];

        // Verifica se veio múltiplas imagens
        if (isset($_FILES['imagens'])) {
            $totalImagens = count($_FILES['imagens']['name']);
            
            for ($i = 0; $i < min($totalImagens, 5); $i++) {
                if ($_FILES['imagens']['error'][$i] === 0) {
                    $arquivo = [
                        'name' => $_FILES['imagens']['name'][$i],
                        'type' => $_FILES['imagens']['type'][$i],
                        'tmp_name' => $_FILES['imagens']['tmp_name'][$i],
                        'error' => $_FILES['imagens']['error'][$i],
                        'size' => $_FILES['imagens']['size'][$i]
                    ];
                    
                    $idImg = $imagemModel->uploadImagem($arquivo, 'destinos');
                    if ($idImg) {
                        if ($i === 0) {
                            $idImagemPrincipal = $idImg; // Primeira é a principal
                        } else {
                            $imagensIds[] = $idImg;
                        }
                    }
                }
            }
        }
        // Fallback para upload único (campo 'imagem')
        elseif (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $idImagemPrincipal = $imagemModel->uploadImagem($_FILES['imagem'], 'destinos');
        }

        if (!$idImagemPrincipal) {
            $erro = $imagemModel->ultimoErro ?? 'Erro desconhecido';
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao fazer upload: ' . $erro]);
            exit;
        }

        $dados = [
            'titulo' => $_POST['titulo'] ?? '',
            'descricao_curta' => $_POST['descricao_curta'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'localizacao' => $_POST['localizacao'] ?? '',
            'duracao' => $_POST['duracao'] ?? '',
            'dificuldade' => $_POST['dificuldade'] ?? '',
            'preco' => $_POST['preco'] ?? 0,
            'id_imagem' => $idImagemPrincipal,
            'imagens_ids' => $imagensIds
        ];

        $id = $destinoModel->criar($dados);

        if ($id) {
            // Salva os guias vinculados
            if (!empty($_POST['guias'])) {
                $guiasIds = json_decode($_POST['guias'], true) ?: [];
                $destinoModel->salvarGuiasDestino($id, $guiasIds);
            }
            
            echo json_encode(['sucesso' => true, 'mensagem' => 'Passeio criado com sucesso', 'id' => $id]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao criar passeio']);
        }
        break;

    case 'listar':
        $destinos = $destinoModel->buscarDestinos();
        echo json_encode(['sucesso' => true, 'destinos' => $destinos]);
        break;

    case 'buscar':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
            exit;
        }

        $destino = $destinoModel->buscarPorId($id);
        if ($destino) {
            // Adiciona os IDs dos guias vinculados
            $destino['guias_ids'] = $destinoModel->buscarIdsGuiasDestino($id);
            echo json_encode(['sucesso' => true, 'destino' => $destino]);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Passeio não encontrado']);
        }
        break;

    case 'atualizar':
        $id = $_POST['id'] ?? null;
        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
            exit;
        }

        // Busca destino atual
        $destinoAtual = $destinoModel->buscarPorId($id);
        $idImagem = $destinoAtual['id_imagem'];
        $imagensAdicionaisIds = [];

        // Verifica se veio múltiplas imagens novas
        if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
            $totalImagens = count($_FILES['imagens']['name']);
            
            for ($i = 0; $i < min($totalImagens, 5); $i++) {
                if ($_FILES['imagens']['error'][$i] === 0) {
                    $arquivo = [
                        'name' => $_FILES['imagens']['name'][$i],
                        'type' => $_FILES['imagens']['type'][$i],
                        'tmp_name' => $_FILES['imagens']['tmp_name'][$i],
                        'error' => $_FILES['imagens']['error'][$i],
                        'size' => $_FILES['imagens']['size'][$i]
                    ];
                    
                    $idImg = $imagemModel->uploadImagem($arquivo, 'destinos');
                    if ($idImg) {
                        if ($i === 0) {
                            $idImagem = $idImg; // Primeira é a principal
                        } else {
                            $imagensAdicionaisIds[] = $idImg;
                        }
                    }
                }
            }
        }
        // Fallback para upload único (campo 'imagem')
        elseif (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $novoIdImagem = $imagemModel->uploadImagem($_FILES['imagem'], 'destinos');
            if ($novoIdImagem) {
                $idImagem = $novoIdImagem;
            }
        }

        $dados = [
            'titulo' => $_POST['titulo'] ?? $destinoAtual['titulo'],
            'descricao_curta' => $_POST['descricao_curta'] ?? $destinoAtual['descricao_curta'],
            'descricao' => $_POST['descricao'] ?? $destinoAtual['descricao'],
            'localizacao' => $_POST['localizacao'] ?? $destinoAtual['localizacao'],
            'duracao' => $_POST['duracao'] ?? $destinoAtual['duracao'],
            'dificuldade' => $_POST['dificuldade'] ?? $destinoAtual['dificuldade'],
            'preco' => $_POST['preco'] ?? $destinoAtual['preco'],
            'id_imagem' => $idImagem
        ];

        if ($destinoModel->atualizar($id, $dados)) {
            // Salva imagens adicionais se houver novas
            if (!empty($imagensAdicionaisIds)) {
                $destinoModel->salvarImagensDestino($id, $imagensAdicionaisIds);
            }
            
            // Atualiza os guias vinculados
            if (isset($_POST['guias'])) {
                $guiasIds = json_decode($_POST['guias'], true) ?: [];
                $destinoModel->salvarGuiasDestino($id, $guiasIds);
            }
            
            echo json_encode(['sucesso' => true, 'mensagem' => 'Passeio atualizado com sucesso']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar passeio']);
        }
        break;

    case 'excluir':
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'ID não informado']);
            exit;
        }

        if ($destinoModel->excluir($id)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Passeio excluído com sucesso']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir passeio']);
        }
        break;

    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não especificada']);
        break;
}

