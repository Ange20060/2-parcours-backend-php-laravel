<?php

require_once __DIR__.'/../../app/Services/CompteService.php';

function calculerTTC(float $montant): float
{
    return $montant * 1.20;
}

it('calcule le TTC avec la TVA à 20%', function () {
    expect(calculerTTC(100.0))->toBe(120.0);
    expect(calculerTTC(0.0))->toBe(0.0);
});

it('refuse un retrait supérieur au solde', function () {
    retirer(600.0, 500.0);
})->throws(SoldeInsuffisantException::class);
