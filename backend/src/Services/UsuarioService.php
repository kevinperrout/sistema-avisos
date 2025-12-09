<?php

namespace App\Services;

use App\Repositories\UsuarioRepository;

class UsuarioService
{
    public function __construct(
        private UsuarioRepository $repo,
    ) {}
}
