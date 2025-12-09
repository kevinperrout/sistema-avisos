<?php

require __DIR__ . '/../../vendor/autoload.php';

use App\Database;
use App\Utils\GerarHash;
use App\Utils\Config;

echo "Preenchendo banco." . PHP_EOL;

// Carrega variáveis de ambiente (necessário para a PIMENTA)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$pdo = Database::getConnection();

echo "Verificando setores." . PHP_EOL;
$setores = [
    ['1', 'Secretaria', '#007bff'], // Azul
    ['2', 'Biblioteca', '#28a745'], // Verde
    ['3', 'TI',         '#dc3545'], // Vermelho
    ['4', 'Diretoria',  '#8604cd'], // Amarelo
    ['5', 'Estágios',   '#17a2b8']  // Ciano
];

$sqlSetor = <<<SQL
    INSERT IGNORE INTO setores (idSetor, nome, cor) 
    VALUES (:id, :nome, :cor);
SQL;

$psSetor = $pdo->prepare($sqlSetor);

foreach ($setores as $setor) {
    $psSetor->execute([
        ':id' => $setor[0],
        ':nome' => $setor[1],
        ':cor' => $setor[2]
    ]);
}

echo "Inserindo períodos" . PHP_EOL;
$periodos = [
    ['1', 'Manha', '00:00:00', '12:59:59'],
    ['2', 'Tarde', '13:00:00', '17:59:59'],
    ['3', 'Noite', '18:00:00', '23:59:59']
];

$sqlPeriodo = <<<SQL
    INSERT IGNORE INTO periodos
    (idPeriodo, nome, horario_inicio, horario_fim)
    VALUES (:id, :nome, :inicio, :fim);
SQL;

$stmtPeriodo = $pdo->prepare($sqlPeriodo);

foreach ($periodos as $p) {
        $stmtPeriodo->execute([
            ':id'   => $p[0],
            ':nome'   => $p[1],
            ':inicio' => $p[2],
            ':fim'    => $p[3]
        ]);
}

echo "Inserindo admin." . PHP_EOL;

$email = 'admin@acme.br';
$senha = '123456'; 

try {
    $salt = GerarHash::gerarSalt();
    $pepper = Config::getPepper();
    $hash = GerarHash::gerarHash($senha, $salt, $pepper);

    $sqlUser = <<<SQL
        INSERT IGNORE INTO usuarios 
        (nome, email, senha, salt)
        VALUES (:nome, :email, :senha, :salt)
    SQL;
    
    $psUsuario = $pdo->prepare($sqlUser);
    
    $psUsuario->execute([
        ':nome'  => 'Administrador',
        ':email' => $email,
        ':senha' => $hash,
        ':salt'  => $salt
    ]);

} catch (Exception $e) {
    echo "Falha ao criar usuário: " . $e->getMessage() . PHP_EOL;
}

echo "Criando avisos." . PHP_EOL;
    $sqlAviso1 = <<<SQL
        INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
        VALUES (NULL, 'Dragon Ball Super - Final do torneio do poder', 'O final do Torneio do Poder em Dragon Ball Super acontece no 48º episódio,
        quando Goku e os demais universos lutam pela sobrevivência. Após uma batalha intensa, Goku e Frieza, do Universo 7, são os últimos sobreviventes.
        No combate final contra Jiren, do Universo 11, Goku atinge uma nova forma, o \"Ultra Instinct\" (Instinto Superior), mas acaba exausto.
        Frieza, com a ajuda de Goku, dá o golpe final, derrotando Jiren e garantindo a vitória para o Universo 7. No final, Zeno-sama apaga o Universo 11,
        mas o desejo das Super Esferas do Dragão ressuscita todos os universos eliminados.', '0',
        '2025-12-18 17:25:52.000000', '2', 'Todos', '1', current_timestamp());
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('1', '1'); 
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('1', '2');
    SQL;

    $sqlAviso2 = <<<SQL
        INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
        VALUES (NULL, 'Sousou no Frieren - T02', 'A história continua com a elfa maga Frieren,
        agora viajando com seus companheiros Fern e Stark, explorando regiões dominadas por demônios e enfrentando novos perigos.
        A nova temporada adapta o arco de “viagens ao norte” do mangá — a partir do capítulo 60 — prometendo batalhas intensas,
        crescimento dos personagens e mais sobre a jornada de Frieren após a derrota do Rei Demônio.
        A animação continua a cargo do estúdio Madhouse, com direção de Tomoya Kitagawa e supervisão de Keiichiro Saito.',
        '0', '2025-12-31 23:59:59', '4', 'Alunos', '1', current_timestamp());
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('2', '1');
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('2', '2');
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('2', '3');
    SQL;

    $sqlAviso3 = <<<SQL
        INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
        VALUES (NULL, 'Solicitação de matricula e trancamento 2026.1', 'As solicitações de matrícula e trancamento para o período 
        2026.1 deverão ser realizadas exclusivamente pelo Portal do Aluno (alunos.cefet-rj.br).
        O procedimento é obrigatório para todos os estudantes a partir do segundo período, 
        que devem acessar o sistema dentro dos prazos divulgados pela instituição para escolher 
        disciplinas, ajustar horários ou solicitar o trancamento do período letivo. 
        Os alunos ingressantes não precisam solicitar matrícula em disciplinas, pois suas inscrições são 
        efetuadas automaticamente pela instituição. Recomenda-se que cada estudante acompanhe regularmente 
        o portal para evitar pendências e garantir a efetivação correta de sua situação acadêmica para o 
        início do semestre.',
        '3', '2026-02-01 23:59:59', '1', 'Alunos', '1', current_timestamp());
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('3', '1');
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('3', '2');
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('3', '3');
    SQL;

    $sqlAviso4 = <<<SQL
        INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
        VALUES (NULL, 'Portal do Aluno - Manutenção', 'Informamos que, devido a uma manutenção emergencial no sistema acadêmico,
        o acesso ao portal do aluno poderá apresentar instabilidades ao longo do dia. Pedimos que tentem novamente após alguns minutos caso encontrem dificuldades. 
        A previsão é que tudo seja normalizado até o final da tarde. Agradecemos pela compreensão.',
        '1', '2026-02-02 23:59:59', '3', 'Alunos', '1', current_timestamp()); 
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('4', '1');
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('4', '2');
        INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('4', '3');
    SQL;
try {
    echo "Inserindo aviso 1" . PHP_EOL;
    $psAviso = $pdo->prepare($sqlAviso1);
    $psAviso->execute();

    echo "Inserindo aviso 2" . PHP_EOL;
    $psAviso = $pdo->prepare($sqlAviso2);
    $psAviso->execute();

    echo "Inserindo aviso 3" . PHP_EOL;
    $psAviso = $pdo->prepare($sqlAviso3);
    $psAviso->execute();

    echo "Inserindo aviso 4" . PHP_EOL;
    $psAviso = $pdo->prepare($sqlAviso4);
    $psAviso->execute();
    
} catch (Exception $e) {
    echo "Falha ao criar avisos: " . $e->getMessage() . PHP_EOL;
}

/* INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
VALUES (NULL, 'Dragon Ball Super - Final do torneio do poder', 'O final do Torneio do Poder em Dragon Ball Super acontece no 48º episódio,
quando Goku e os demais universos lutam pela sobrevivência. Após uma batalha intensa, Goku e Frieza, do Universo 7, são os últimos sobreviventes. No combate final contra Jiren, do Universo 11, Goku atinge uma nova forma, o \"Ultra Instinct\" (Instinto Superior), mas acaba exausto. Frieza, com a ajuda de Goku, dá o golpe final, derrotando Jiren e garantindo a vitória para o Universo 7. No final, Zeno-sama apaga o Universo 11, mas o desejo das Super Esferas do Dragão ressuscita todos os universos eliminados.', '0', '2025-12-18 17:25:52.000000', '2', 'Todos', '1', current_timestamp());

INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('1', '1');
INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('1', '2');
*/

/* INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
VALUES (NULL, 'Sousou no Frieren - T02', 'A história continua com a elfa maga Frieren, agora viajando com seus companheiros Fern e Stark, explorando regiões dominadas por demônios e enfrentando novos perigos. A nova temporada adapta o arco de “viagens ao norte” do mangá — a partir do capítulo 60 — prometendo batalhas intensas, crescimento dos personagens e mais sobre a jornada de Frieren após a derrota do Rei Demônio. A animação continua a cargo do estúdio Madhouse, com direção de Tomoya Kitagawa e supervisão de Keiichiro Saito.', '0', '2025-12-31 23:59:59', '4', 'Alunos', '1', current_timestamp());

INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('2', '1');
INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('2', '2');
INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('2', '3');
*/

/* INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
VALUES (NULL, 'Solicitação de matricula e trancamento 2026.1', 'As solicitações de matrícula e trancamento para o período 2026.1 deverão ser realizadas exclusivamente pelo Portal do Aluno (alunos.cefet-rj.br).
O procedimento é obrigatório para todos os estudantes a partir do segundo período, que devem acessar o sistema dentro dos prazos divulgados pela instituição para escolher disciplinas, ajustar horários ou solicitar o trancamento do período letivo. Os alunos ingressantes não precisam solicitar matrícula em disciplinas, pois suas inscrições são efetuadas automaticamente pela instituição. Recomenda-se que cada estudante acompanhe regularmente o portal para evitar pendências e garantir a efetivação correta de sua situação acadêmica para o início do semestre.', '3', '2026-02-01 23:59:59', '1', 'Alunos', '1', current_timestamp());

INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('3', '1');
INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('3', '2');
INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('3', '3');
*/

/* INSERT INTO avisos (idAviso, titulo, texto, urgente, datahora_validade, idSetor, publico_alvo, criado_por, created_at)
VALUES (NULL, 'Portal do Aluno - Manutenção', 'Informamos que, devido a uma manutenção emergencial no sistema acadêmico, o acesso ao portal do aluno poderá apresentar instabilidades ao longo do dia. Pedimos que tentem novamente após alguns minutos caso encontrem dificuldades. A previsão é que tudo seja normalizado até o final da tarde. Agradecemos pela compreensão.', '1', '2026-02-02 23:59:59', '3', 'Alunos', '1', current_timestamp()); 

INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('4', '1');
INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('4', '2');
INSERT INTO avisos_periodos (idAviso, idPeriodo) VALUES ('4', '3');
*/

