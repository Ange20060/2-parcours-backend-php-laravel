<?php

declare(strict_types=1);

$nombres = [4, 7, 2, 9, 1, 8, 5];
$carre=array_map(fn(int $nbr):int => $nbr*$nbr, $nombres);

echo "carré des nombres: " . implode(", ", $carre);

$paire = array_filter($nombres, fn(int $nbr):bool => $nbr % 2===0 );

echo "\npaire des nombres: " . implode(", ", $paire);

$somme = array_reduce($nombres, fn($carry, $nbr):int => $carry + $nbr, 0);

echo "\nSomme des nombres: " . $somme;
