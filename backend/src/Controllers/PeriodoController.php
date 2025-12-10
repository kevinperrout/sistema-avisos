<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\PeriodoService;
use App\Presenters\PeriodoPresenter;
use App\Interfaces\SessaoInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PeriodoController extends ApiController
{
    public function __construct(
        private PeriodoService $PeriodoService,
    ) {}

    public function listar(Request $request, Response $response): Response
    {
        $listaPeriodos = $this->PeriodoService->listarPeriodos();

        return $this->json($response, $listaPeriodos);
    }
}
