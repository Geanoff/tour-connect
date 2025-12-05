<?php
require_once __DIR__ . '/../config/database.php';

class SolicitacaoGuiaModel
{
    private $pdo;
    private $tabela = 'solicitacao_guia';
    public $ultimoErro = '';

    function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Cria nova solicitação
     */
    function criar($dados)
    {
        try {
            $query = "INSERT INTO {$this->tabela} (id_usuario, nome, email, telefone, mensagem) 
                      VALUES (:id_usuario, :nome, :email, :telefone, :mensagem)";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_usuario', $dados['id_usuario'], PDO::PARAM_INT);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':email', $dados['email']);
            $stmt->bindParam(':telefone', $dados['telefone']);
            $stmt->bindParam(':mensagem', $dados['mensagem']);

            if ($stmt->execute()) {
                return $this->pdo->lastInsertId();
            }
            return false;

        } catch (PDOException $e) {
            $this->ultimoErro = $e->getMessage();
            return false;
        }
    }

    /**
     * Lista todas as solicitações
     */
    function listarTodas()
    {
        try {
            $query = "SELECT * FROM {$this->tabela} ORDER BY lida ASC, data_criacao DESC";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Conta solicitações não lidas
     */
    function contarNaoLidas()
    {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->tabela} WHERE lida = 0";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['total'] ?? 0;

        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Marca como lida
     */
    function marcarComoLida($id)
    {
        try {
            $query = "UPDATE {$this->tabela} SET lida = 1 WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui solicitação
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
