<?php
require __DIR__ . '/../../vendor/autoload.php';

use App\Database;

echo "Estabelecendo conexão" . PHP_EOL;

$pdo = Database::getConnection();

echo "Tabela setores". PHP_EOL;

$sqlSetores = <<<SQL
CREATE TABLE IF NOT EXISTS setores (
    idSetor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cor VARCHAR(7) NOT NULL DEFAULT '#ffffff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

echo "Tabela períodos". PHP_EOL;

$sqlPeriodos = <<<SQL
CREATE TABLE IF NOT EXISTS periodos (
    idPeriodo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(20) NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

echo "Tabela usuarios". PHP_EOL;

$sqlUsuarios = <<<SQL
CREATE TABLE IF NOT EXISTS usuarios (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(128) NOT NULL,
    salt VARCHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

echo "Tabela avisos". PHP_EOL;

$sqlAvisos = <<<SQL
CREATE TABLE IF NOT EXISTS avisos (
    idAviso INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    texto TEXT NOT NULL,
    urgente TINYINT(1) NOT NULL DEFAULT 0,
    datahora_validade DATETIME NOT NULL,
    idSetor INT NOT NULL,
    publico_alvo VARCHAR(100) NOT NULL,
    criado_por INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (criado_por) REFERENCES usuarios(idUsuario),
    FOREIGN KEY (idSetor) REFERENCES setores(idSetor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

echo "Tabela avisos_periodos". PHP_EOL;

$sqlAvisosPeriodos = <<<SQL
CREATE TABLE IF NOT EXISTS avisos_periodos (
    idAviso INT NOT NULL,
    idPeriodo INT NOT NULL,
    PRIMARY KEY (idAviso, idPeriodo),
    FOREIGN KEY (idAviso) REFERENCES avisos(idAviso) ON DELETE CASCADE,
    FOREIGN KEY (idPeriodo) REFERENCES periodos(idPeriodo) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

echo "Tentando criar tabelas" . PHP_EOL;
try {
    $pdo->exec($sqlSetores);
    echo "Tabela setores criada". PHP_EOL;

    $pdo->exec($sqlPeriodos);
    echo "Tabela periodos criada." . PHP_EOL;

    $pdo->exec($sqlUsuarios);
    echo "Tabela usuarios criada". PHP_EOL;
    
    $pdo->exec($sqlAvisos);
    echo "Tabela avisos criada" . PHP_EOL;

    $pdo->exec($sqlAvisosPeriodos);
    echo "Tabela avisos_periodos criada" . PHP_EOL;
} catch (PDOException $e) {
    die("Falha ao criar tabelas: " . $e->getMessage() . PHP_EOL);
}