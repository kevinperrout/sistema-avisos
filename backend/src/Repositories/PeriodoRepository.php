<?php

namespace App\Repositories;

use PDO;
use PDOException;

interface PeriodoRepository
{
    /**
     * Pega a lista de todos os setores
     * @return array<int, array<string, mixed>>
     */
    public function listarPeriodos(): array;
}