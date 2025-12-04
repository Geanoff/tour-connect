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
            $query = "
                SELECT d.*, i.caminho AS imagem
                FROM {$this->tabela} d
                LEFT JOIN imagens i ON d.id_imagem = i.id
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $destinos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $destinos;

        } catch (PDOException $e) {
            return false;
        }
    }

    function buscarDestinoEspecifico($id)
    {
        try
        {
            $query = "
                SELECT d.*, i.caminho AS imagem
                FROM {$this->tabela} d
                LEFT JOIN imagens i ON d.id_imagem = i.id
                WHERE d.id = :id
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $destino = $stmt->fetch(PDO::FETCH_ASSOC);

            return $destino;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}
