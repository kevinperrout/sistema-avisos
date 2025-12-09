<?php

namespace App\Repositories;

use App\Database;
use PDO;

class PeriodoRepositoryBDR implements PeriodoRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function listarPeriodos(): array
    {
        $sql = <<<SQL
            SELECT idPeriodo, nome, horario_inicio, horario_fim, created_at
            FROM periodos
        SQL;
        $ps = $this->pdo->prepare($sql);
        $ps->execute();

        if (!$ps) {
            return [];
        }

        return $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}