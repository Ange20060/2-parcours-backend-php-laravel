<?php

declare(strict_types=1);

class CompteBancaire
{
    public function __construct(private float $solde = 0.0) {}   // promotion de propriété

    public function deposer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le dépôt doit être positif.");
        }
        $this->solde += $montant;
    }

    public function retirer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le retrait doit être positif.");
        }
        if ($montant > $this->solde) {
            throw new RuntimeException("Solde insuffisant.");
        }
        $this->solde -= $montant;
    }

    public function solde(): float
    {
        return $this->solde;
    }
}

$compte = new CompteBancaire(100);
$compte->deposer(50);
$compte->retirer(30);
echo $compte->solde() . PHP_EOL;
