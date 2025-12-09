<?php

namespace App\Repositories;

interface AvisoRepository
{
    /**
     * Pega a lista de todos os avisos
     * @return array<int, array<string, mixed>>
     */
    public function listar(): array;

    // /**
    //  * Lista todos os assuntos que pertencem a um aviso.
    //  * @param int $idAviso
    //  * @return array<int, array<string, mixed>>
    //  */
    // public function listarAssuntosPorAviso(int $idAviso): array;

    public function salvarAviso(
        string $titulo,
        string $texto,
        int $urgente,
        string $datahora_validade,
        int $setor,
        string $publicoAlvo,
        array $periodos,
        int $criadoPor
    ): int;
}
