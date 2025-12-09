<?php

namespace App\Repositories;

use App\Database;
use PDO;
use PDOException;

class AvisoRepositoryBDR implements AvisoRepository
{
    private PDO $pdo;

    public function __construct()
    {
        // Conecta ao banco de dados assim que a classe é instanciada
        $this->pdo = Database::getConnection();
    }

    public function listar(): array
    {
        $sql = <<<SQL
            SELECT a.idAviso, a.titulo, a.texto, a.urgente, a.datahora_validade,
            s.nome as setor, s.cor as setor_cor, a.publico_alvo,
            GROUP_CONCAT(p.nome SEPARATOR ', ') as periodos_string,
            u.nome as criado_por, a.created_at
            FROM avisos a
            JOIN setores s ON s.idSetor = a.idSetor
            JOIN usuarios u ON u.idUsuario = a.criado_por
            JOIN avisos_periodos ap ON a.idAviso = ap.idAviso
            JOIN periodos p ON p.idPeriodo = ap.idPeriodo
            GROUP BY a.idAviso
            ORDER BY a.idAviso ASC
        SQL;
        $ps = $this->pdo->prepare($sql);
        $ps->execute();

        if (!$ps) {
            return [];
        }

        return $ps->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvarAviso(
        string $titulo,
        string $texto,
        int $urgente,
        string $datahora_validade,
        int $setor,
        string $publicoAlvo,
        array $periodos,
        int $criadoPor
    ): int {

        $sqlAviso = <<<SQL
            INSERT INTO avisos (titulo, texto, urgente, datahora_validade,
            idSetor, publico_alvo, criado_por, created_at)
            VALUES (:titulo, :texto, :urgente, :datahora_validade,
            :idSetor, :publico_alvo, :criado_por, NOW()) 
        SQL;

        $sqlAvisoPeriodo = <<<SQL
            INSERT INTO avisos_periodos (idAviso, idPeriodo)
            VALUES (:idAviso, :idPeriodo) 
        SQL;

        try {

            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sqlAviso);
            $stmt->execute([
                'titulo'             => $titulo,
                'texto'              => $texto,
                'urgente'            => $urgente,
                'datahora_validade'  => $datahora_validade,
                'idSetor'            => $setor,
                'publico_alvo'       => $publicoAlvo,
                'criado_por'         => $criadoPor
            ]);

            (int)$idAviso = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare($sqlAvisoPeriodo);
            foreach ($periodos as $periodo) {

                $stmt->execute([
                    'idAviso' => $idAviso,
                    'idPeriodo' => $periodo
                ]);
            }
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new PDOException("Erro ao salvar no banco: " . $e->getMessage());
        }
        return (int)$idAviso;
    }
}
