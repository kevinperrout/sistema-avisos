<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use App\Interfaces\SessaoInterface;

abstract class ApiController
{
    /**
     * Monta a resposta em JSON e define o status HTTP.
     * @param Response $response
     * @param mixed $data Dados para o JSON.
     * @param int $status Código HTTP
     */
    protected function json(Response $response, mixed $data, int $status = 200): Response
    {
        $response->getBody()->write((string)json_encode($data));

        return $response->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }
}
