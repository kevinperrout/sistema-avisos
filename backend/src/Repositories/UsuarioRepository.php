<?php

namespace App\Repositories;

use App\Models\Usuario;

interface UsuarioRepository
{
    /**
     * Busca os dados de login e pontuação de um usuario pelo e-mail.
     * @param string $email
     * @return Usuario|null
     */
    public function buscarPorEmail(string $email): ?Usuario;

}
