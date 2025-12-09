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

    test('deve logar com dados corretos', function () {
        $body = [
            'email'     =>  'admin@acme.br',
            'senha'     =>  '123456'
        ];

        $loginRequest = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/login')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($body);

        $loginResponse = $this->app->handle($loginRequest);

        expect($loginResponse->getStatusCode())->toBe(200);
    });

    test('deve rejeitar login com senha errada', function () {
        $body = [
            'email' => 'teste@login.com',
            'senha' => 'senha_errada'
        ];

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/login')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($body);

        $response = $this->app->handle($request);

        expect($response->getStatusCode())->toBe(401);
    });

    test('deve rejeitar login com email errado', function () {
        $body = [
            'email' => 'teste@loginnn.com',
            'senha' => '123456'
        ];

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/login')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($body);

        $response = $this->app->handle($request);

        expect($response->getStatusCode())->toBe(401);
    });
});
