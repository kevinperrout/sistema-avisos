<?php

use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

// Setup antes de cada teste
beforeEach(function () {
    $this->app = AppFactory::create();
    $this->app->addBodyParsingMiddleware();
    (require __DIR__ . '/../../src/routes/api.php')($this->app);
});

describe('API de Login', function () {

    test('deve deslogar e invalidar a sessão', function () {
        $body = [
            'email'     =>  'admin@acme.br',
            'senha'     =>  '123456'
        ];

        $loginRequest = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/login')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($body);

        $this->app->handle($loginRequest);

        $logoutRequest = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/logout');
            
        $logoutResponse = $this->app->handle($logoutRequest);

        expect($logoutResponse->getStatusCode())->toBe(200);

        // Tento enviar um aviso pra testar que estou deslogado (vai dar erro por não ter id do usuario)

        $avisoBody = [
            'titulo'       => 'Teste pós logout',
            'texto'        => 'Não deveria passar',
            'urgente'      => false,
            'dataValidade' => '2026-01-01T12:00',
            'idSetor'      => 5,
            'publicoAlvo'  => 'Todos',
            'idsPeriodos'  => [1]
        ];

        $avisoRequest = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/avisos')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($avisoBody);

        $avisoResponse = $this->app->handle($avisoRequest);

        expect($avisoResponse->getStatusCode())->toBe(403);
    });
});
