<?php

declare(strict_types=1);

function sauvegarder(array $donnees, string $fichier): void
{
    $json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        throw new RuntimeException("Échec de l'encodage JSON.");
    }
    file_put_contents($fichier, $json);
}

function charger(string $fichier): array
{
    if (!file_exists($fichier)) {
        return [];
    }
    $donnees = json_decode(file_get_contents($fichier), true);
    if (!is_array($donnees)) {
        throw new RuntimeException("Fichier JSON corrompu.");
    }
    return $donnees;
}

sauvegarder(['Apprendre PHP', 'Réviser SQL'], 'taches.json');
print_r(charger('taches.json'));
