<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\SetorService;
use App\Presenters\SetorPresenter;
use App\Interfaces\SessaoInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SetorController extends ApiController
{
    public function __construct(
        private SetorService $SetorService,
    ) {}

    public function listar(Request $request, Response $response): Response
    {
        // if ($retornoPermissao = $this->verificarPermissao($response, $this->sessao, ['admin', 'professor'])) {
        //     return $retornoPermissao;
        // }
        $listaSetors = $this->SetorService->listarSetores();

        return $this->json($response, $listaSetors);
    }
}
