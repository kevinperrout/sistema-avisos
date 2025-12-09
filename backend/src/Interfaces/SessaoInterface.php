<?php
namespace App\Interfaces;

interface SessaoInterface
{
    public function iniciar(): void;

    public function definir(string $chave, mixed $valor): void;

    public function obterDados(string $chave): mixed;

    public function destruir(): void;

    public function regenerarId(): void;

}