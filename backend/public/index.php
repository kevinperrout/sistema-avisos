<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

// Carrega dependências
require __DIR__ . '/../vendor/autoload.php';

// Carrega as variáveis de ambiente do arquivo .env na raiz do backend
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Cria a aplicação Slim
$app = AppFactory::create();

// Define o caminho base para os arquivos de configuração/middleware
$basePath = __DIR__ . '/../src/';

// CORS Middleware (Importante para o frontend)
(require $basePath . 'middleware/cors.php')($app);

// Middlewares básicos do Slim
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Middleware de erro
$displayErrors = true;
$logErrors = true;
$logErrorDetails = true;

// Configura o tratamento de erros para mostrar detalhes no ambiente de dev
$app->addErrorMiddleware($displayErrors, $logErrors, $logErrorDetails);

// Carrega todas as rotas
(require $basePath . 'routes/api.php')($app);

$app->run();