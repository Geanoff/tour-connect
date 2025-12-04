<?php
require_once __DIR__ . "/../config/database.php";

class AgendamentoModel
{
    private $tabela = 'agendamento';
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    /**
     * Cria um novo agendamento
     */
    function criar($dados)
    {
        try {
            $qtdPessoas = $dados['quantidade_pessoas'] ?? 1;
            
            $query = "INSERT INTO {$this->tabela} (id_usuario, id_destino, id_guia, data_passeio, horario, quantidade_pessoas, status) 
                      VALUES (:id_usuario, :id_destino, :id_guia, :data_passeio, :horario, :quantidade_pessoas, 'agendado')";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_usuario', $dados['id_usuario'], PDO::PARAM_INT);
            $stmt->bindParam(':id_destino', $dados['id_destino'], PDO::PARAM_INT);
            $stmt->bindParam(':id_guia', $dados['id_guia'], PDO::PARAM_INT);
            $stmt->bindParam(':data_passeio', $dados['data_passeio']);
            $stmt->bindParam(':horario', $dados['horario']);
            $stmt->bindParam(':quantidade_pessoas', $qtdPessoas, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $idAgendamento = $this->pdo->lastInsertId();
                
                // Salva os participantes
                if (!empty($dados['participantes'])) {
                    $this->salvarParticipantes($idAgendamento, $dados['participantes']);
                }
                
                return $idAgendamento;
            }
            return false;

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Salva os participantes do agendamento
     */
    function salvarParticipantes($idAgendamento, $participantes)
    {
        try {
            $query = "INSERT INTO agendamento_participantes (id_agendamento, nome, idade) VALUES (:id_agendamento, :nome, :idade)";
            $stmt = $this->pdo->prepare($query);

            foreach ($participantes as $p) {
                $stmt->bindParam(':id_agendamento', $idAgendamento, PDO::PARAM_INT);
                $stmt->bindParam(':nome', $p['nome']);
                $stmt->bindParam(':idade', $p['idade'], PDO::PARAM_INT);
                $stmt->execute();
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Verifica vagas disponíveis para um passeio em determinada data/horário
     */
    function verificarVagasDisponiveis($idDestino, $data, $horario)
    {
        try {
            // Busca o limite do passeio
            $queryLimite = "SELECT limite_pessoas FROM destino WHERE id = :id";
            $stmtLimite = $this->pdo->prepare($queryLimite);
            $stmtLimite->bindParam(':id', $idDestino, PDO::PARAM_INT);
            $stmtLimite->execute();
            $destino = $stmtLimite->fetch(PDO::FETCH_ASSOC);
            $limite = $destino['limite_pessoas'] ?? 10;

            // Conta quantas pessoas já agendaram
            $queryAgendados = "
                SELECT COALESCE(SUM(quantidade_pessoas), 0) as total
                FROM {$this->tabela}
                WHERE id_destino = :id_destino 
                AND data_passeio = :data 
                AND horario = :horario
                AND status = 'agendado'
            ";
            $stmtAgendados = $this->pdo->prepare($queryAgendados);
            $stmtAgendados->bindParam(':id_destino', $idDestino, PDO::PARAM_INT);
            $stmtAgendados->bindParam(':data', $data);
            $stmtAgendados->bindParam(':horario', $horario);
            $stmtAgendados->execute();
            $result = $stmtAgendados->fetch(PDO::FETCH_ASSOC);
            $agendados = $result['total'];

            return [
                'limite' => $limite,
                'agendados' => $agendados,
                'disponiveis' => $limite - $agendados
            ];

        } catch (PDOException $e) {
            return ['limite' => 10, 'agendados' => 0, 'disponiveis' => 10];
        }
    }

    /**
     * Busca participantes de um agendamento
     */
    function buscarParticipantes($idAgendamento)
    {
        try {
            $query = "SELECT * FROM agendamento_participantes WHERE id_agendamento = :id ORDER BY id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $idAgendamento, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Busca agendamentos de um usuário
     */
    function buscarPorUsuario($idUsuario)
    {
        try {
            $query = "
                SELECT a.*, 
                       d.titulo AS passeio_titulo, 
                       d.localizacao AS passeio_localizacao,
                       d.preco AS passeio_preco,
                       i.caminho AS passeio_imagem,
                       g.nome AS guia_nome,
                       ig.caminho AS guia_foto
                FROM {$this->tabela} a
                JOIN destino d ON a.id_destino = d.id
                LEFT JOIN imagens i ON d.id_imagem = i.id
                JOIN guia g ON a.id_guia = g.id
                LEFT JOIN imagens ig ON g.id_imagem = ig.id
                WHERE a.id_usuario = :id_usuario
                ORDER BY a.data_passeio DESC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Lista todos os agendamentos (para admin)
     */
    function listarTodos()
    {
        try {
            $query = "
                SELECT a.*, 
                       u.nome AS usuario_nome,
                       u.email AS usuario_email,
                       u.telefone AS usuario_telefone,
                       d.titulo AS passeio_titulo, 
                       d.localizacao AS passeio_localizacao,
                       d.preco AS passeio_preco,
                       g.nome AS guia_nome
                FROM {$this->tabela} a
                JOIN usuarios u ON a.id_usuario = u.id
                JOIN destino d ON a.id_destino = d.id
                JOIN guia g ON a.id_guia = g.id
                ORDER BY a.status ASC, a.data_passeio ASC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Atualiza status do agendamento
     */
    function atualizarStatus($id, $status)
    {
        try {
            $query = "UPDATE {$this->tabela} SET status = :status WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Busca um agendamento pelo ID
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
     * Exclui um agendamento
     */
    function excluir($id, $idUsuario = null)
    {
        try {
            // Se passou id do usuário, verifica se o agendamento pertence a ele
            if ($idUsuario) {
                $query = "DELETE FROM {$this->tabela} WHERE id = :id AND id_usuario = :id_usuario";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            } else {
                $query = "DELETE FROM {$this->tabela} WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }
}

