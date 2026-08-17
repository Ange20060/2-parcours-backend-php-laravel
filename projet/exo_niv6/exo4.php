<?php
declare(strict_types=1);

function creerUser(PDO $pdo, string $nom, string $email): int
{
    // Requête PRÉPARÉE : les valeurs passent par des paramètres liés, jamais concaténées
    $stmt = $pdo->prepare("INSERT INTO users (nom, email) VALUES (:nom, :email)");
    $stmt->execute([':nom' => $nom, ':email' => $email]);
    return (int) $pdo->lastInsertId();
}

$pdo = new PDO('sqlite:blog.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
