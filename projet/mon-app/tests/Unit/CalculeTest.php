<?php
// tests/Unit/CalculTest.php  (Pest)
function calculerTTC(float $montant):float {
  return $montant*1.20;
}

it('calcule le TTC avec la TVA à 20%', function () {
    expect(calculerTTC(100.0))->toBe(120.0);
    expect(calculerTTC(0.0))->toBe(0.0);           // cas limite
});

it('refuse un retrait supérieur au solde', function () {
    retirer(100.0, 500.0);
})->throws(SoldeInsuffisantException::class);
