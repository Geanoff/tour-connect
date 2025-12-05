<?php
require_once __DIR__ . "/../config/database.php";

class GuiaModel
{
    private $tabela = 'guia';
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public $ultimoErro = '';

    /**
     * Cria um novo guia
     */
    function criar($dados)
    {
        try {
            // Se não tem imagem, permite NULL
            $idImagem = !empty($dados['id_imagem']) ? $dados['id_imagem'] : null;
            
            $query = "INSERT INTO {$this->tabela} (nome, id_imagem, especialidade) 
                      VALUES (:nome, :id_imagem, :especialidade)";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':id_imagem', $idImagem, PDO::PARAM_INT);
            $stmt->bindParam(':especialidade', $dados['especialidade']);

            if ($stmt->execute()) {
                return $this->pdo->lastInsertId();
            }
            $this->ultimoErro = 'Erro ao executar query';
            return false;

        } catch (PDOException $e) {
            $this->ultimoErro = $e->getMessage();
            return false;
        }
    }

    /**
     * Busca um guia pelo ID
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
     * Lista todos os guias
     */
    function listarTodos()
    {
        try {
            $query = "
                SELECT g.*, i.caminho AS imagem
                FROM {$this->tabela} g
                LEFT JOIN imagens i ON g.id_imagem = i.id
                ORDER BY g.nome ASC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Atualiza um guia
     */
    function atualizar($id, $dados)
    {
        try {
            // Se tem nova imagem, atualiza com ela
            if (isset($dados['id_imagem']) && $dados['id_imagem']) {
                $query = "UPDATE {$this->tabela} 
                          SET nome = :nome, id_imagem = :id_imagem, especialidade = :especialidade 
                          WHERE id = :id";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':nome', $dados['nome']);
                $stmt->bindParam(':id_imagem', $dados['id_imagem'], PDO::PARAM_INT);
                $stmt->bindParam(':especialidade', $dados['especialidade']);
            } else {
                // Sem nova imagem, mantém a atual
                $query = "UPDATE {$this->tabela} 
                          SET nome = :nome, especialidade = :especialidade 
                          WHERE id = :id";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':nome', $dados['nome']);
                $stmt->bindParam(':especialidade', $dados['especialidade']);
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui um guia
     */
    function excluir($id)
    {
        try {
            $query = "DELETE FROM {$this->tabela} WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }
}

