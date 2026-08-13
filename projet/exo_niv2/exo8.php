<?php

declare(strict_types=1);

//1 tableau
$commandes = [
  ['id'=>1, 'status'=>'payée','montant'=>150.50],
  ['id'=>2, 'status'=>'en attente','montant'=>450.50],
  ['id'=>3, 'status'=>'payée','montant'=>550.50],
  ['id'=>4, 'status'=>'annuler','montant'=>15.50],
  ['id'=>5, 'status'=>'payée','montant'=>159.50]
];

//2 FILTRE
$filtrer = array_filter($commandes, function($commande) {
  return $commande['status'] === 'payée';
});

//3 MAP

$map= array_map(function($commande){
  return $commande['montant'];
}, $filtrer);

//4 SOMMES

$sum = array_reduce($map, function($total, $montant){
  return $total + $montant;
}, 0);
