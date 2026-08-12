<?php

declare(strict_types=1);

function creerCompte(string $email, string $motDePasse, int $age): array
{
    $erreurs = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Adresse e-mail invalide.";
    }

    if (strlen($motDePasse) < 8) {
        $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    if ($age < 13 || $age > 130) {
        $erreurs[] = "Vous devez avoir entre 13 et 130 ans pour créer un compte.";
    }

    return $erreurs;
}
$email='zohoungmail.com';
$mdp='12345';
$age= 16;
$resultat= creerCompte($email,$mdp,$age);
print_r($resultat);
