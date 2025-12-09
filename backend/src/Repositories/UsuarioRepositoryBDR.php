<?php
namespace App\Repositories;

use App\Database;
use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use PDO;

class UsuarioRepositoryBDR implements UsuarioRepository
{
    private PDO $pdo;

    public function __construct()
    {
        // Conecta ao banco de dados assim que a classe é instanciada
        $this->pdo = Database::getConnection();
    }

    public function buscarPorEmail(string $email): ?Usuario
    {
        $sql = <<<SQL
            SELECT idUsuario, nome, email, senha, salt, created_at
            FROM usuarios 
            WHERE email = ?
        SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dados) {
            return null;
        }

        return new Usuario(
            (int) $dados['idUsuario'],
            (string) $dados['nome'],
            (string) $dados['email'],
            (string) $dados['senha'],
            (string) $dados['salt'],
            (string) $dados['created_at'],
        );
    }
}
