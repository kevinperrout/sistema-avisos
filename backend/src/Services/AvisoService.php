<?php

namespace App\Services;

use App\Repositories\AvisoRepository;

class AvisoService
{
    public function __construct(
        private AvisoRepository $repository
    ) {}

    /**
     * Busca todos os avisos do repositório
     * @return array<int, array<string, mixed>>
     */
    public function listarTodosAvisos(): array
    {
        return $this->repository->listar();
    }

    /**
     * Envia o aviso ao repositório para ser salvo.
     * @param array<string, mixed> $dados
     * @param int $id
     * @return int|null
     */
    public function criarAviso($dados, $id): int|null
    {
        $idAviso = NULL;
        if ($id) {
            $idAviso = $this->repository->salvarAviso(
                $dados['titulo'],
                $dados['texto'],
                (int)$dados['urgente'],
                $dados['dataValidade'],
                (int)$dados['idSetor'],
                $dados['publicoAlvo'],
                $dados['idsPeriodos'],
                (int)$id
            );
        }
        return $idAviso;
    }
}
