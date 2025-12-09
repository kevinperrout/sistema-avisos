<?php

namespace App\Utils;

use RuntimeException;

class Config
{
    /**
     * Obtém pepper
     * @return string $pepper
     */
    public static function getPepper(): string
    {
        // Pega pepper no .env
        // precisei do vlucas/phpdotenv por conta do seed que não lia a .env
        $pepper = $_ENV['ACME_PEPPER'] ?? $_SERVER['ACME_PEPPER'] ?? getenv('ACME_PEPPER');

        // Se não existir para a aplicação.
        if ($pepper === false) {
            throw new RuntimeException('ACME_PEPPER não está definida.');
        }

        return (string) $pepper;
    }
}