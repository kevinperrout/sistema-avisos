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
        // if ($retornoPermissao = $this->verificarPermissao($response, $this->sessao, ['admin'])) {
        //     return $retornoPermissao;
        // }
        $dados = (array)$request->getParsedBody();

        // var_dump($dados);

        // $titulo             = $dados['titulo'] ?? '';
        // $texto              = $dados['texto'] ?? '';
        // $urgente            = $dados['urgente'] ?? '';
        // $datahora_validade  = $dados['dataValidade'] ?? '';
        // $setor              = $dados['idSetor'] ?? '';
        // $publicoAlvo        = $dados['publicoAlvo'] ?? '';
        // $periodo            = $dados['periodo'] ?? '';
        // $criadoPor          = $dados['criado_por'] ?? '';

        $id = $this->sessao->obterDados('usuario_id');
        $resultado = $this->avisoService
            ->criarAviso($dados, $id);

        $status = isset($resultado) ? 201 : 400;

        return $this->json($response, $resultado, $status);
    }

    public function listar(Request $request, Response $response): Response
    {
        // if ($retornoPermissao = $this->verificarPermissao($response, $this->sessao, ['admin', 'professor'])) {
        //     return $retornoPermissao;
        // }

        $avisosBrutos = $this->avisoService->listarTodosAvisos();

        $listaAvisos = $this->avisoPresenter->formatarAvisos($avisosBrutos);

        return $this->json($response, $listaAvisos);
    }
}
