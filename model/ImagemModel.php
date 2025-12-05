<?php
require_once __DIR__ . "/../config/database.php";

class ImagemModel
{
    private $tabela = 'imagens';
    private $pdo;
    private $uploadDir = __DIR__ . '/../uploads/';

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Faz upload de uma imagem e salva o caminho no banco
     * @param array $arquivo - $_FILES['campo']
     * @param string $pasta - 'destinos' ou 'guias'
     * @return int|false - ID da imagem inserida ou false
     */
    public $ultimoErro = '';

    function uploadImagem($arquivo, $pasta = 'destinos')
    {
        try {
            // Verifica se o arquivo foi enviado
            if (empty($arquivo['name'])) {
                $this->ultimoErro = 'Nenhum arquivo enviado';
                return false;
            }

            // Validações
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            
            if (!in_array($extensao, $extensoesPermitidas)) {
                $this->ultimoErro = 'Extensão não permitida: ' . $extensao;
                return false;
            }

            // Tamanho máximo: 5MB
            if ($arquivo['size'] > 5 * 1024 * 1024) {
                $this->ultimoErro = 'Arquivo muito grande (máx 5MB)';
                return false;
            }

            // Gera nome único para o arquivo
            $nomeArquivo = uniqid() . '_' . time() . '.' . $extensao;
            $caminhoCompleto = $this->uploadDir . $pasta . '/' . $nomeArquivo;
            $caminhoRelativo = 'uploads/' . $pasta . '/' . $nomeArquivo;

            // Verifica se a pasta existe
            if (!is_dir($this->uploadDir . $pasta)) {
                mkdir($this->uploadDir . $pasta, 0777, true);
            }

            // Move o arquivo
            if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                $this->ultimoErro = 'Erro ao mover arquivo para: ' . $caminhoCompleto;
                return false;
            }

            // Salva no banco
            $query = "INSERT INTO {$this->tabela} (caminho) VALUES (:caminho)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':caminho', $caminhoRelativo);
            $stmt->execute();

            return $this->pdo->lastInsertId();

        } catch (Exception $e) {
            $this->ultimoErro = 'Exceção: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca uma imagem pelo ID
     */
    function buscarPorId($id)
    {
        try {
            $query = "SELECT * FROM {$this->tabela} WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui uma imagem (banco e arquivo)
     */
    function excluir($id)
    {
        try {
            // Busca o caminho da imagem
            $imagem = $this->buscarPorId($id);
            if (!$imagem) {
                return false;
            }

            // Exclui o arquivo físico
            $caminhoCompleto = __DIR__ . '/../' . $imagem['caminho'];
            if (file_exists($caminhoCompleto)) {
                unlink($caminhoCompleto);
            }

            // Exclui do banco
            $query = "DELETE FROM {$this->tabela} WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Lista todas as imagens
     */
    function listarTodas()
    {
        try {
            $query = "SELECT * FROM {$this->tabela} ORDER BY data_upload DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }
}

