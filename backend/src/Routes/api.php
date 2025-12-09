<?php

use Slim\App;

use App\Infraestructure\Sessao;

use App\Controllers\UsuarioController;
use App\Repositories\UsuarioRepositoryBDR;

use App\Controllers\AvisoController;
use App\Presenters\AvisoPresenter;
use App\Repositories\AvisoRepositoryBDR;
use App\Services\AvisoService;

use App\Controllers\PeriodoController;
use App\Repositories\PeriodoRepositoryBDR;
use App\Services\PeriodoService;

use App\Controllers\SetorController;
use App\Repositories\SetorRepositoryBDR;
use App\Services\SetorService;

use App\Utils\Config;

use App\Services\AuthService;

return function (App $app) {
    $pepper = Config::getPepper();

    // Sessao
    $sessao = new Sessao();

    // Repositórios
    $usuarioRepository = new UsuarioRepositoryBDR();
    $avisoRepository = new AvisoRepositoryBDR();
    $periodoRepository = new PeriodoRepositoryBDR();
    $setorRepository = new SetorRepositoryBDR();
    
    // Autenticação/Sessão
    $authService = new AuthService($usuarioRepository);

    // Configuração do Usuário
    $usuarioController = new UsuarioController($authService, $sessao);

    // Aviso
    $avisoService = new AvisoService($avisoRepository);
    $avisoPresenter = new AvisoPresenter();
    $avisoController = new AvisoController($avisoService, $avisoPresenter, $sessao);

    $periodoService = new PeriodoService($periodoRepository);
    $periodoController = new PeriodoController($periodoService);

    $setorService = new SetorService($setorRepository);
    $setorController = new SetorController($setorService);

    // Todas as rotas da API utilizam o prefixo /api.
    $app->group('/api', function ($group)
    use ($usuarioController, $avisoController, $setorController, $periodoController) {

        $group->get('/', function ($req, $res) {
            $msg = "API do Quiz funcionando. Vá para /api/cursos.";
            $res->getBody()->write($msg);
            return $res;
        });

        // ROTAS DE AUTH
        $group->post('/login', [$usuarioController, 'login']);
        $group->post('/logout', [$usuarioController, 'logout']);

        // ROTAS USUARIO
        $group->get('/usuarios', [$usuarioController, 'listar']);
        $group->post('/usuarios', [$usuarioController, 'cadastrar']);

        // ROTAS AVISOS
        $group->get('/avisos', [$avisoController, 'listar']);
        $group->post('/avisos', [$avisoController, 'cadastrar']);

        // ROTAS SETORES
        $group->get('/setores', [$setorController, 'listar']);

        // ROTAS PERIODOS
        $group->get('/periodos', [$periodoController, 'listar']);

    });
};
