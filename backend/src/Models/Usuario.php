<?php

namespace App\Models;

use App\Exceptions\ModelException;

class Usuario
{
    public readonly int $id;
    public readonly string $nome;
    public readonly string $email;
    public readonly string $senha;
    public readonly string $salt;

    public readonly string $created_at;

    public function __construct(
        string|int $id,
        string $nome,
        string $email,
        string $senha,
        ?string $salt,
        string $created_at,
    ) {
        $this->validar($nome, $email);

        $this->id = (int) $id;
        $this->nome = htmlspecialchars(trim($nome));
        $this->email = trim($email);
        $this->senha = $senha;
        $this->salt = (string) ($salt);
        $this->created_at = (string) ($created_at);
    }

    private function validar(string $nome, string $email): void
    {
        if (mb_strlen(trim($nome)) < 3 || mb_strlen($nome) > 100) {
            throw new ModelException('O nome deve ter entre 3 e 100 caracteres.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ModelException('Insira um email válido.');
        }

        if (!str_ends_with($email, '@acme.br')) {
            throw new ModelException('O login deve ser possuir e-mail institucional (@acme.br).');
        }
    }
}
