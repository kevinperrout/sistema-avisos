<?php

use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

// Setup antes de cada teste
beforeEach(function () {
    $this->app = AppFactory::create();
    $this->app->addBodyParsingMiddleware();
    (require __DIR__ . '/../../src/routes/api.php')($this->app);
});

// Teste de Segurança
test('Deve bloquear cadastro se não estiver logado (Espera 403)', function () {
    // Tenta cadastrar sem nenhum cookie de sessão
    $body = [
        'titulo'          =>  'Teste deslogado',
        'texto'           =>  'O cadastro de teste serve para testar',
        'urgente'         =>  true,
        'dataValidade'    =>  '2026-05-28T22:48',
        'idSetor'         =>  5,
        'publicoAlvo'     =>  'Todos',
        'idsPeriodos'     =>  [1, 2, 3]
    ];

    $request = (new ServerRequestFactory())
        ->createServerRequest('POST', '/api/avisos')
        ->withHeader('Content-Type', 'application/json')
        ->withParsedBody($body);

    // Executa a requisição real na API
    $response = $this->app->handle($request);

    // Não vamos passar um ID pra cadastrar, logo vai dar erro no cadastro
    // (possivelmente nãoo estamoos logado ou fiz algo de errado)
    expect($response->getStatusCode())->toBe(403);

    // Verificar a mensagem de erro
    $json = json_decode((string)$response->getBody(), true);
    expect($json['erro'])->toContain('Recurso');
});

// Teste de Segurança
test('Deve permitir cadastro se estiver logado (Espera 201)', function () {
    $dadoLogin = [
    'email'          =>  'admin@acme.br',
    'senha'           =>  '123456'
    ];

    $loginRequest = (new ServerRequestFactory())
            ->createServerRequest('POST', '/api/login')
            ->withHeader('Content-Type', 'application/json')
            ->withParsedBody($dadoLogin);

    $this->app->handle($loginRequest);
    
    $body = [
        'titulo'          =>  'Teste logado',
        'texto'           =>  'O cadastro de teste serve para testar',
        'urgente'         =>  true,
        'dataValidade'    =>  '2026-05-28T22:48',
        'idSetor'         =>  5,
        'publicoAlvo'     =>  'Todos',
        'idsPeriodos'     =>  [1, 2, 3]
    ];

    $request = (new ServerRequestFactory())
        ->createServerRequest('POST', '/api/avisos')
        ->withHeader('Content-Type', 'application/json')
        ->withParsedBody($body);

    // Executa a requisição na API
    $response = $this->app->handle($request);

    expect($response->getStatusCode())->toBe(201);

    $json = json_decode((string)$response->getBody(), true);
    
    // Garante que voltou a mensagem de sucesso definida no Controller
    expect($json['mensagem'])->toContain('sucesso');
    
    // Garante que devolveu o ID do novo aviso
    expect($json)->toHaveKey('id_aviso');
});
