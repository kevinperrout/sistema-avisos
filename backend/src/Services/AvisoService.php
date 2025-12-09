<?php

namespace App\Services;

use App\Repositories\AvisoRepository;

class AvisoService
{
    

    public function __construct(
        private AvisoRepository $repository
    ){}

    /**
     * Busca todos os avisos do repositório, aplicando o filtro.
     * @param string|null $filtro
     * @return array<int, array<string, mixed>>
     */
    public function listarTodosAvisos(): array
    {
        return $this->repository->listar();
    }

    /**
     * Retorna a lista de assuntos de um aviso pelo seu ID.
     * @param int $idAviso
     * @return array
     * @return array<int, array<string, mixed>>
     */
    public function criarAviso($dados, $id): int
    {
        // $idAviso = $this->repository->salvarAviso($dados);
        
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

        return $idAviso;
    }
}