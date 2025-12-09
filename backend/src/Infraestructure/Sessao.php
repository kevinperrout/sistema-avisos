<?php

namespace App\Infraestructure;

use App\Interfaces\SessaoInterface;

class Sessao implements SessaoInterface
{
    public function iniciar(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            
            session_name('sid');

            session_set_cookie_params([
                'lifetime' => 3600,
                'path'     => '/',
                'domain'   => '', 
                'secure'   => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            
            session_start();
        }
    }

    public function definir(string $chave, mixed $valor): void
    {
        // Garante que a sessão está ativa antes de escrever
        $this->iniciar();
        $_SESSION[$chave] = $valor;
    }

    public function obterDados(string $chave): mixed
    {
        $this->iniciar();
        return $_SESSION[$chave] ?? null;
    }

    public function destruir(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];

            // mata o cookie no navegador
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    (string)session_name(), 
                    '', 
                    time() - 42000,
                    $params["path"], 
                    $params["domain"],
                    $params["secure"], 
                    $params["httponly"]
                );
            }

            // destrói a sessão no servidor
            session_destroy();
        }
    }

    public function regenerarId(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true); 
        }
    }
}