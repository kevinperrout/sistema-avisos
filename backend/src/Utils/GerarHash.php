<?php

namespace App\Utils;

class GerarHash
{
    public static function gerarSalt(): string
    {
        return bin2hex(random_bytes(16));
    }

    public static function gerarHash(string $senha, string $salt, string $pepper): string
    {
        return hash('sha512', $senha . $salt . $pepper);
    }
}
