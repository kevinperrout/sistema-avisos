<?php

namespace App\Services;

use App\Repositories\SetorRepository;

class SetorService
{
    private SetorRepository $repository;

    public function __construct(SetorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Busca todos os Setors do repositório, aplicando o filtro.
     * @param string|null $filtro
     * @return array<int, array<string, mixed>>
     */
    public function listarSetores(): array
    {
        return $this->repository->listarSetores();
    }
}