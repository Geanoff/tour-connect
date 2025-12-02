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

            $query = "INSERT INTO $this->tabela (nome, cpf, data_nascimento, email, telefone, senha, cep, rua, numero, bairro, cidade, estado)
                  VALUES (:nome, :cpf, :data_nascimento, :email, :telefone, :senha, :cep, :rua, :numero, :bairro, :cidade, :estado)";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':cpf', $dados['cpf']);
            $stmt->bindParam(':data_nascimento', $dados['data_nascimento']);
            $stmt->bindParam(':email', $dados['email']);
            $stmt->bindParam(':telefone', $dados['telefone']);
            $stmt->bindParam(':senha', $senhaHash);
            $stmt->bindParam(':cep', $dados['cep']);
            $stmt->bindParam(':rua', $dados['rua']);
            $stmt->bindParam(':numero', $dados['numero']);
            $stmt->bindParam(':bairro', $dados['bairro']);
            $stmt->bindParam(':cidade', $dados['cidade']);
            $stmt->bindParam(':estado', $dados['estado']);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // qualquer erro retorna false
        }
    }
}
