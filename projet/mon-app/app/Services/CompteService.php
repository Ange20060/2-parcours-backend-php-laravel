<?php

class SoldeInsuffisantException extends Exception {}

function retirer(float $montant, float $solde): float
{
    if ($montant > $solde) {
        throw new SoldeInsuffisantException(
            'Solde insuffisant'
        );
    }

    return $solde - $montant;
}
