<?php
class SoldeInsuffisantException extends Exception {}

function retirer(float $solde, float $montant): float
{
    if ($montant > $solde) {
        throw new SoldeInsuffisantException("Solde insuffisant : $solde  < $montant ");
    }
    return $solde - $montant;
}

try {
    echo retirer(100, 30) . PHP_EOL;    // 70
    echo retirer(100, 500) . PHP_EOL;   // lève l'exception
} catch (SoldeInsuffisantException $e) {
    echo "Refusé : " . $e->getMessage() . PHP_EOL;
}
