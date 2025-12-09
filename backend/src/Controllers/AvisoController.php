<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\AvisoService;
use App\Presenters\AvisoPresenter;
use App\Interfaces\SessaoInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AvisoController extends ApiController
{
    public function __construct(
        private AvisoService $avisoService,
        private AvisoPresenter $avisoPresenter,
        private SessaoInterface $sessao
    ) {}

    public function cadastrar(Request $request, Response $response): Response
    {
        $dados = (array)$request->getParsedBody();

        $id = $this->sessao->obterDados('usuario_id');
        $resultado = $this->avisoService
            ->criarAviso($dados, $id);

        $status = !empty($resultado) ? 201 : 403;

        $payload = !empty($resultado)
            ? ['mensagem' => "Aviso inserido com sucesso", 'id_aviso' => $resultado]
            : ['erro' => "Recurso indisponível. Você precisa estar logado"];

        return $this->json($response, $payload, $status);
    }

    public function listar(Request $request, Response $response): Response
    {
        $avisosBrutos = $this->avisoService->listarTodosAvisos();

        $listaAvisos = $this->avisoPresenter->formatarAvisos($avisosBrutos);

        return $this->json($response, $listaAvisos);
    }
}
