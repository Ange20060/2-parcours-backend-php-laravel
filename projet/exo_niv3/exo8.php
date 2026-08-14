<?php

declare(strict_types=1);

enum Devise: string { case EUR = 'EUR'; case USD = 'USD'; }

interface MoyenPaiement
{
    public function payer(float $montant): string;
}

class CarteBancaire implements MoyenPaiement
{
    public function payer(float $montant): string
    {
        return "Payé $montant € par carte bancaire.";
    }
}

class Paypal implements MoyenPaiement
{
    public function payer(float $montant): string
    {
        return "Payé $montant € via PayPal.";
    }
}

class Caisse
{
    public function __construct(private MoyenPaiement $moyen) {}   // composition

    public function encaisser(float $montant): string
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Montant invalide.");   // Fail Fast
        }
        return $this->moyen->payer($montant);
    }
}

echo (new Caisse(new CarteBancaire()))->encaisser(49.99) . PHP_EOL;
echo (new Caisse(new Paypal()))->encaisser(120.0) . PHP_EOL;
