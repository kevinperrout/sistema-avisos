<?php

namespace App\Presenters;

class AvisoPresenter
{
    public function __construct() {}

    /**
     * Formata a lista de avisos para o frontend
     * @param array<int, array<string, mixed>> $avisos
     * @return array<int, array<string, mixed>>
     */
    public function formatarAvisos(array $avisos): array
    {
        return array_map(function ($aviso) {

            $stringPeriodos = $aviso['periodos_string'] ?? '';
            $arrayPeriodos = $stringPeriodos ? explode(', ', $stringPeriodos) : [];

            return [
                'idAviso'           => $aviso['idAviso'],
                'titulo'            => $aviso['titulo'],
                'texto'             => $aviso['texto'],
                'urgente'           => (bool)$aviso['urgente'],
                'dataHoraValidade'  => $aviso['datahora_validade'],
                'nomeSetor'         => $aviso['setor'],
                'corSetor'          => $aviso['setor_cor'],
                'periodos'          => $arrayPeriodos,
                'publicoAlvo'       => $aviso['publico_alvo'],
                'nomeAutor'         => $aviso['criado_por'],
                'dataCriacao'       => $aviso['created_at'],
            ];
        }, $avisos);
    }
}
