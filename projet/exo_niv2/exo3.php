<?php

declare(strict_types=1);

$users = [
    ['nom' => 'Marie', 'age' => 30],
    ['nom' => 'Jean', 'age' => 25],
    ['nom' => 'Amina', 'age' => 22],
    ['nom' => 'Paul', 'age' => 35],
    ['nom' => 'Sophie', 'age' => 28],
];
usort($users, fn($a, $b) => $a['age'] <=> $b['age']);

print_r($users);

usort($users, fn($a, $b) => $a['nom'] <=> $b['nom']);
