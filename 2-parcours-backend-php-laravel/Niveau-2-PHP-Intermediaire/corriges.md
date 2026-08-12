# ✅ Niveau 2 — Corrigés

> ⚠️ Essaie d'abord. Code supposé précédé de `declare(strict_types=1);`.

---

## Exercice 1 — Le panier
```php
<?php
function ajouterAuPanier(array $panier, string $produit, float $prix, int $qte): array
{
    $panier[$produit] = ['prix' => $prix, 'qte' => $qte];
    return $panier;
}

function totalPanier(array $panier): float
{
    $total = 0.0;
    foreach ($panier as $ligne) {
        $total += $ligne['prix'] * $ligne['qte'];
    }
    return $total;
}

$panier = [];
$panier = ajouterAuPanier($panier, "Clavier", 25.0, 2);
$panier = ajouterAuPanier($panier, "Souris", 12.5, 3);

foreach ($panier as $produit => $ligne) {
    echo "$produit : {$ligne['qte']} x {$ligne['prix']} €" . PHP_EOL;
}
echo "Total : " . totalPanier($panier) . " €" . PHP_EOL;
```

## Exercice 2 — map, filter, reduce
```php
<?php
$nombres = [4, 7, 2, 9, 1, 8, 5];

$carres = array_map(fn(int $n): int => $n * $n, $nombres);
$pairs  = array_filter($nombres, fn(int $n): bool => $n % 2 === 0);
$somme  = array_reduce($nombres, fn(int $acc, int $n): int => $acc + $n, 0);

print_r($carres);
print_r(array_values($pairs));   // array_values : ré-indexe après filter
echo "Somme : $somme" . PHP_EOL;
```
> 💡 `array_filter` **conserve les clés** d'origine ; `array_values` les réinitialise si besoin.

## Exercice 3 — Trier
```php
<?php
$users = [['nom' => 'Sofia', 'age' => 25], ['nom' => 'Marie', 'age' => 30], ['nom' => 'Ali', 'age' => 25]];

usort($users, fn(array $a, array $b): int => $a['age'] <=> $b['age']);   // par âge
usort($users, fn(array $a, array $b): int => strcmp($a['nom'], $b['nom'])); // par nom
print_r($users);
```
> L'opérateur **spaceship** `<=>` retourne -1, 0 ou 1 : parfait pour `usort`.

## Exercice 4 — Slug
```php
<?php
function genererSlug(string $titre): string
{
    $slug = mb_strtolower(trim($titre));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);   // tout sauf lettres/chiffres → tiret
    return trim($slug, '-');
}
echo genererSlug("Mon Premier Article !");   // mon-premier-article
```
> Bonus accents : `iconv('UTF-8', 'ASCII//TRANSLIT', $slug)` avant le `preg_replace`.

## Exercice 5 — Exceptions personnalisées
```php
<?php
class SoldeInsuffisantException extends Exception {}

function retirer(float $solde, float $montant): float
{
    if ($montant > $solde) {
        throw new SoldeInsuffisantException("Solde insuffisant : $solde € < $montant €");
    }
    return $solde - $montant;
}

try {
    echo retirer(100, 30) . PHP_EOL;    // 70
    echo retirer(100, 500) . PHP_EOL;   // lève l'exception
} catch (SoldeInsuffisantException $e) {
    echo "Refusé : " . $e->getMessage() . PHP_EOL;
}
```

## Exercice 6 — Persistance JSON
```php
<?php
function sauvegarder(array $donnees, string $fichier): void
{
    $json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        throw new RuntimeException("Échec de l'encodage JSON.");
    }
    file_put_contents($fichier, $json);
}

function charger(string $fichier): array
{
    if (!file_exists($fichier)) {
        return [];
    }
    $donnees = json_decode(file_get_contents($fichier), true);
    if (!is_array($donnees)) {
        throw new RuntimeException("Fichier JSON corrompu.");
    }
    return $donnees;
}

sauvegarder(['Apprendre PHP', 'Réviser SQL'], 'taches.json');
print_r(charger('taches.json'));
```

## Exercice 7 — Dates
```php
<?php
function calculerAge(string $dateNaissance): int
{
    $naissance = new DateTimeImmutable($dateNaissance);
    return $naissance->diff(new DateTimeImmutable('today'))->y;
}

function joursAvant(string $dateFuture): int
{
    $cible = new DateTimeImmutable($dateFuture);
    return (int) (new DateTimeImmutable('today'))->diff($cible)->days;
}

echo calculerAge('2000-01-15') . PHP_EOL;
echo joursAvant('2026-12-31') . PHP_EOL;
```
> 💡 `DateTimeImmutable` (immuable) évite les bugs de `DateTime` qu'on modifie par erreur —
> c'est plus [explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md) et sûr.

## Exercice 8 — Pipeline
```php
<?php
$commandes = [
    ['montant' => 120.0, 'payee' => true],
    ['montant' => 80.0,  'payee' => false],
    ['montant' => 200.0, 'payee' => true],
];

$payees   = array_filter($commandes, fn(array $c): bool => $c['payee']);
$montants = array_map(fn(array $c): float => $c['montant'], $payees);
$ca       = array_reduce($montants, fn(float $acc, float $m): float => $acc + $m, 0.0);

echo "CA payé : $ca €" . PHP_EOL;   // 320 €
```
> Chaque étape a **un nom clair** et **une responsabilité** : filtre → extrait → additionne.

## Exercice 9 — Autoloading PSR-4
`composer.json` :
```json
{
    "autoload": { "psr-4": { "App\\": "src/" } }
}
```
`src/Calculatrice.php` :
```php
<?php
namespace App;

class Calculatrice
{
    public function additionner(int $a, int $b): int { return $a + $b; }
}
```
`index.php` :
```php
<?php
require 'vendor/autoload.php';

use App\Calculatrice;

echo (new Calculatrice())->additionner(3, 4);   // 7 — aucune inclusion manuelle !
```
Commande : `composer dump-autoload`, puis `php index.php`.

---

## 🎉 Bilan du Niveau 2
Tu manipules maintenant tableaux, chaînes, exceptions, fichiers/JSON et dates **proprement**,
et tu comprends l'autoloading — la porte d'entrée vers Laravel.
👉 [Niveau 3 : POO](../Niveau-3-POO/)
