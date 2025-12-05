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

    /**
     * Cria um novo destino/passeio
     */
    function criar($dados)
    {
        try {
            $query = "INSERT INTO {$this->tabela} 
                      (titulo, descricao_curta, descricao, localizacao, duracao, dificuldade, preco, id_imagem) 
                      VALUES (:titulo, :descricao_curta, :descricao, :localizacao, :duracao, :dificuldade, :preco, :id_imagem)";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':titulo', $dados['titulo']);
            $stmt->bindParam(':descricao_curta', $dados['descricao_curta']);
            $stmt->bindParam(':descricao', $dados['descricao']);
            $stmt->bindParam(':localizacao', $dados['localizacao']);
            $stmt->bindParam(':duracao', $dados['duracao']);
            $stmt->bindParam(':dificuldade', $dados['dificuldade']);
            $stmt->bindParam(':preco', $dados['preco']);
            $stmt->bindParam(':id_imagem', $dados['id_imagem'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                $idDestino = $this->pdo->lastInsertId();
                
                // Se tiver imagens adicionais, salva na tabela destino_imagens
                if (!empty($dados['imagens_ids']) && is_array($dados['imagens_ids'])) {
                    $this->salvarImagensDestino($idDestino, $dados['imagens_ids']);
                }
                
                return $idDestino;
            }
            return false;

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Salva múltiplas imagens para um destino
     */
    function salvarImagensDestino($idDestino, $imagensIds)
    {
        try {
            // Remove imagens antigas
            $queryDelete = "DELETE FROM destino_imagens WHERE id_destino = :id_destino";
            $stmtDelete = $this->pdo->prepare($queryDelete);
            $stmtDelete->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insere novas imagens
            $queryInsert = "INSERT INTO destino_imagens (id_destino, id_imagem, ordem) VALUES (:id_destino, :id_imagem, :ordem)";
            $stmtInsert = $this->pdo->prepare($queryInsert);

            foreach ($imagensIds as $ordem => $idImagem) {
                $stmtInsert->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
                $stmtInsert->bindParam(':id_imagem', $idImagem, PDO::PARAM_INT);
                $stmtInsert->bindParam(':ordem', $ordem, PDO::PARAM_INT);
                $stmtInsert->execute();
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Busca todas as imagens de um destino
     */
    function buscarImagensDestino($idDestino)
    {
        try {
            $query = "
                SELECT i.id, i.caminho
                FROM destino_imagens di
                JOIN imagens i ON di.id_imagem = i.id
                WHERE di.id_destino = :id_destino
                ORDER BY di.ordem ASC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Busca todos os destinos com suas imagens
     */
    function buscarDestinos()
    {
        try {
            $query = "
                SELECT d.*, i.caminho AS imagem
                FROM {$this->tabela} d
                LEFT JOIN imagens i ON d.id_imagem = i.id
                ORDER BY d.data_criacao DESC
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Busca destinos com limite (para paginação)
     */
    function buscarDestinosComLimite($limite = 6, $offset = 0)
    {
        try {
            $query = "
                SELECT d.*, i.caminho AS imagem
                FROM {$this->tabela} d
                LEFT JOIN imagens i ON d.id_imagem = i.id
                ORDER BY d.data_criacao DESC
                LIMIT :limite OFFSET :offset
            ";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Conta total de destinos (para paginação)
     */
    function contarDestinos()
    {
        try {
            $query = "SELECT COUNT(*) as total FROM {$this->tabela}";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];

        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Busca um destino específico pelo ID (com todas as imagens)
     */
    function buscarPorId($id)
    {
        try {
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
            
            if ($destino) {
                // Busca imagens adicionais
                $imagensAdicionais = $this->buscarImagensDestino($id);
                
                // Monta array de todas as imagens (principal + adicionais)
                $todasImagens = [];
                if ($destino['imagem']) {
                    $todasImagens[] = $destino['imagem'];
                }
                foreach ($imagensAdicionais as $img) {
                    $todasImagens[] = $img['caminho'];
                }
                $destino['imagens'] = $todasImagens;
            }

            return $destino;

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Busca destinos por estado (localização)
     */
    function buscarPorEstado($estado)
    {
        try {
            $query = "
                SELECT d.*, i.caminho AS imagem
                FROM {$this->tabela} d
                LEFT JOIN imagens i ON d.id_imagem = i.id
                WHERE d.localizacao LIKE :estado
                ORDER BY d.data_criacao DESC
            ";

            $stmt = $this->pdo->prepare($query);
            $busca = '%' . $estado . '%';
            $stmt->bindParam(':estado', $busca);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Busca destinos por termo (título, descrição ou localização)
     */
    function buscarPorTermo($termo)
    {
        try {
            $query = "
                SELECT d.*, i.caminho AS imagem
                FROM {$this->tabela} d
                LEFT JOIN imagens i ON d.id_imagem = i.id
                WHERE d.titulo LIKE :termo 
                   OR d.descricao LIKE :termo 
                   OR d.localizacao LIKE :termo
                ORDER BY d.data_criacao DESC
            ";

            $stmt = $this->pdo->prepare($query);
            $busca = '%' . $termo . '%';
            $stmt->bindParam(':termo', $busca);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Atualiza um destino
     */
    function atualizar($id, $dados)
    {
        try {
            $query = "UPDATE {$this->tabela} SET 
                      titulo = :titulo,
                      descricao_curta = :descricao_curta,
                      descricao = :descricao,
                      localizacao = :localizacao,
                      duracao = :duracao,
                      dificuldade = :dificuldade,
                      preco = :preco,
                      id_imagem = :id_imagem
                      WHERE id = :id";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $dados['titulo']);
            $stmt->bindParam(':descricao_curta', $dados['descricao_curta']);
            $stmt->bindParam(':descricao', $dados['descricao']);
            $stmt->bindParam(':localizacao', $dados['localizacao']);
            $stmt->bindParam(':duracao', $dados['duracao']);
            $stmt->bindParam(':dificuldade', $dados['dificuldade']);
            $stmt->bindParam(':preco', $dados['preco']);
            $stmt->bindParam(':id_imagem', $dados['id_imagem'], PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Exclui um destino
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

    /**
     * Salva os guias vinculados a um destino
     */
    function salvarGuiasDestino($idDestino, $guiasIds)
    {
        try {
            // Remove vínculos antigos
            $queryDelete = "DELETE FROM destino_guia WHERE id_destino = :id_destino";
            $stmtDelete = $this->pdo->prepare($queryDelete);
            $stmtDelete->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Insere novos vínculos
            if (!empty($guiasIds)) {
                $queryInsert = "INSERT INTO destino_guia (id_destino, id_guia) VALUES (:id_destino, :id_guia)";
                $stmtInsert = $this->pdo->prepare($queryInsert);

                foreach ($guiasIds as $idGuia) {
                    $stmtInsert->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':id_guia', $idGuia, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Busca os guias vinculados a um destino
     */
    function buscarGuiasDestino($idDestino)
    {
        try {
            $query = "
                SELECT g.*, i.caminho AS imagem
                FROM destino_guia dg
                JOIN guia g ON dg.id_guia = g.id
                LEFT JOIN imagens i ON g.id_imagem = i.id
                WHERE dg.id_destino = :id_destino
                ORDER BY g.nome ASC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Busca IDs dos guias vinculados a um destino
     */
    function buscarIdsGuiasDestino($idDestino)
    {
        try {
            $query = "SELECT id_guia FROM destino_guia WHERE id_destino = :id_destino";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            return [];
        }
    }
}
