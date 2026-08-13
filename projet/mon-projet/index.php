<?php

require __DIR__ . '/vendor/autoload.php';

use App\Calculatrice;

$calculatrice = new Calculatrice();

$resultat = $calculatrice->additionner(10, 5);
$multiplication = $calculatrice->mutiplier(10, 5);
echo "Résultat : " . $resultat;
echo "\nMultiplication : " . $multiplication;
