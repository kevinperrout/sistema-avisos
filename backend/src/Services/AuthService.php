<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\UsuarioRepository;
use App\Utils\GerarHash;
use App\Utils\Config;

class AuthService
{
    public function __construct(
        private UsuarioRepository $usuarioRepository,
    ) {}

    /**
     * Faz login
     * @param string $email
     * @param string $senha
     * @return array<string, mixed>
     */
    public function login(string $email, string $senha): ?array
    {
        $usuario = $this->usuarioRepository->buscarPorEmail($email);

        if (!$usuario) {
            return null;
        }

        $salt = $usuario->salt;

        $hashSenha = GerarHash::gerarHash($senha, $salt, Config::getPepper());

        if (!hash_equals($usuario->senha, $hashSenha)) {
            return null;
        }

        return [
            'usuario' => $usuario
        ];
    }

    /**
     * Verifica permissões dos usuarios
     * Retorna uma Response 403 se proibido, ou null se permitido.
     * @param array<string> $cargosPermitidos Ex: ['admin', 'professor']
     */
    // protected function verificarPermissao(): ?json {
    //     $usuario = $sessao->obterDados('usuario_id');

    //     if (!isset($usuario)) {
    //         return $this->ApiController::json($response, ['erro' => 'Acesso negado.'], 403);
    //     }

    //     return null;
    // }
}
