<?php
require_once __DIR__ . "/../config/database.php";

class UsuarioModel
{
    private $tabela = 'usuarios';
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    function login($dados)
    {
        try {
            // Busca o usuário pelo e-mail
            $query = "SELECT * FROM {$this->tabela} WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':email', $dados['email']);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Se não encontrou usuário
            if (!$usuario) {
                return false;
            }

            // Verifica a senha hash
            if (!password_verify($dados['senha'], $usuario['senha'])) {
                return false;
            }

            return $usuario; // login OK

        } catch (PDOException $e) {
            return false;
        }
    }


    function cadastro($dados)
    {
        try {
            $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

            $query = "INSERT INTO $this->tabela (nome, email, cpf, telefone, senha)
                      VALUES (:nome, :email, :cpf, :telefone, :senha)";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':email', $dados['email']);
            $stmt->bindParam(':cpf', $dados['cpf']);
            $stmt->bindParam(':telefone', $dados['telefone']);
            $stmt->bindParam(':senha', $senhaHash);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // qualquer erro retorna false
        }
    }
}
