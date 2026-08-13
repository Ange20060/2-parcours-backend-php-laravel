<?php

declare(strict_types=1);
$x='';
$y='';
['produit' => ['prix' => $x, 'qte' => $y], ];

function ajouterAuPanier(array $panier, string $produit, float $prix, int $qte): array
{
    if ($qte <= 0 || $prix < 0) {
        return $panier;
    }

    if (!isset($panier[$produit])) {
        $panier[$produit] = ['prix' => $prix, 'qte' => $qte];
    } else {
        $panier[$produit]['qte'] += $qte;
        $panier[$produit]['prix'] = $prix;
    }

    return $panier;
}

function totalPanier(array $panier): float
{
    $total = 0.0;

    foreach ($panier as $item) {
        if (!is_array($item)) {
            continue;
        }

        $prix = $item['prix'] ?? 0;
        $qte = $item['qte'] ?? 0;

        if (!is_numeric($prix) || !is_numeric($qte)) {
            continue;
        }

        $total += (float) $prix * (int) $qte;
    }

    return $total;
}
$pani=['a'=>['prix'=>100, 'qte'=>20],];

$panier = ajouterAuPanier($pani, 'Z', 500, 222);

var_dump($panier);


$tota=totalPanier($panier);
echo $tota;
