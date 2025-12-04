<?php
require_once __DIR__ . "/../config/database.php";

class DestinoModel
{
    private $tabela = 'destino';
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    function buscarDestinos()
    {
        try {
            // Busca o usuário pelo e-mail
            $query = "SELECT * FROM {$this->tabela}";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $destino = $stmt->fetch(PDO::FETCH_ASSOC);

            return $destino;

        } catch (PDOException $e) {
            return false;
        }
    }
}
