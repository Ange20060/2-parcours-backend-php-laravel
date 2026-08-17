<?php
namespace App\Services;

class SalutationService
{
    public function saluer(string $nom): string
    {
        return "Bonjour $nom, bienvenue !";
    }
}
