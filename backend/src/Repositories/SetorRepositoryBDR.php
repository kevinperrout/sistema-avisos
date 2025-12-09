<?php

namespace App\Repositories;

use App\Database;
use PDO;

class SetorRepositoryBDR implements SetorRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function listarSetores(): array
    {
        $sql = <<<SQL
            SELECT idSetor, nome, cor, created_at
            FROM setores
        SQL;
        $ps = $this->pdo->prepare($sql);
        $ps->execute();

        if (!$ps) {
            return [];
        }

        return $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}