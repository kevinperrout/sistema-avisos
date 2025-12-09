<?php

namespace App\Services;

use App\Repositories\PeriodoRepository;

class PeriodoService
{
    private PeriodoRepository $repository;

    public function __construct(PeriodoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Busca todos os Periodos do repositório, aplicando o filtro.
     * @param string|null $filtro
     * @return array<int, array<string, mixed>>
     */
    public function listarPeriodos(): array
    {
        return $this->repository->listarPeriodos();
    }
}