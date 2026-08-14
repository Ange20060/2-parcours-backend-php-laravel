<?php

declare(strict_types=1);



enum StatutCommande: string
{
    case EnAttente = 'en_attente';
    case Payee     = 'payee';
    case Expediee  = 'expediee';
    case Annulee   = 'annulee';

    public function libelle(): string
    {
        return match ($this) {
            StatutCommande::EnAttente => "En attente de paiement",
            StatutCommande::Payee     => "Payée",
            StatutCommande::Expediee  => "Expédiée",
            StatutCommande::Annulee   => "Annulée",
        };
    }
}

echo StatutCommande::Payee->libelle() . PHP_EOL;   // Payée

