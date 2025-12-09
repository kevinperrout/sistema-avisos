<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Interfaces\SessaoInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsuarioController extends ApiController
{
    public function __construct(
        private AuthService $authService,
        private SessaoInterface $sessao
    ) {}

    public function login(Request $request, Response $response): Response
    {
        $dados = (array)$request->getParsedBody();

        $email = $dados['email'] ?? '';
        $senha = $dados['senha'] ?? '';

        $resultado = $this->authService->login($email, $senha);

        if (!$resultado) {
            return $this->json($response, ['erro' => 'Credenciais inválidas'], 401);
        }

        $usuario = $resultado['usuario'];

        $this->sessao->iniciar();
        $this->sessao->regenerarId();
        $this->sessao->definir('usuario_id', $usuario->id);

        $resposta = [
            'idUsuario' => $usuario->id,
            'nome' => $usuario->nome,
            'email' => $usuario->email,
        ];

        return $this->json($response, $resposta);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->sessao->destruir();

        return $this->json($response, ['msg' => 'Logout realizado com sucesso.'], 200);
    }
}
