<?php

namespace App\Repositories;

use PDO;
use PDOException;

interface SetorRepository
{
    /**
     * Pega a lista de todos os setores
     * @return array<int, array<string, mixed>>
     */
    public function listarSetores(): array;
}