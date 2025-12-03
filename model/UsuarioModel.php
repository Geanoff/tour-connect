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

    function login($email, $senha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tabela} WHERE email = :email AND senha = :senha");
        $stmt->execute(['email' => $email, 'senha' => $senha]);
        return $stmt->fetch();
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
