# ✅ Niveau 1 — Corrigés

> ⚠️ Essaie **d'abord**. Plusieurs solutions sont valables ; ici on privilégie le code
> **typé, explicite et simple**. Tout le code suppose `declare(strict_types=1);` en tête.

---

## Exercice 2 — Convertisseur de température
```php
<?php
declare(strict_types=1);

function celsiusVersFahrenheit(float $c): float
{
    return $c * 9 / 5 + 32;
}

echo celsiusVersFahrenheit(20) . PHP_EOL;   // 68
echo celsiusVersFahrenheit(37) . PHP_EOL;   // 98.6
```

## Exercice 3 — Comparaisons strictes
```
0 == "a"       → false (en PHP 8 ; était true en PHP 7 !)
"1" == 1       → true   (== convertit les types)
"1" === 1      → false  (types différents : string vs int)
null == false  → true
[] == false    → true
```
**Conclusion** : `==` applique des conversions implicites imprévisibles. `===` compare valeur
**et** type, sans surprise → **toujours `===`** (principe Explicite > Implicite).

## Exercice 4 — Appréciation avec `match`
```php
<?php
declare(strict_types=1);

function appreciation(int $note): string
{
    return match (true) {
        $note >= 16 => "Excellent",
        $note >= 12 => "Bien",
        $note >= 10 => "Passable",
        default     => "Insuffisant",
    };
}
echo appreciation(14);   // Bien
```

## Exercice 5 — FizzBuzz propre
```php
<?php
declare(strict_types=1);

function fizzbuzz(int $n): string
{
    return match (true) {
        $n % 15 === 0 => "FizzBuzz",   // multiple de 3 ET 5
        $n % 3 === 0  => "Fizz",
        $n % 5 === 0  => "Buzz",
        default       => (string) $n,
    };
}

for ($i = 1; $i <= 30; $i++) {
    echo fizzbuzz($i) . PHP_EOL;
}
```
> 💡 En isolant `fizzbuzz($n)` dans une fonction, on peut la **tester** indépendamment de la
> boucle (SoC) — indispensable dès qu'on fera des tests au Niveau 11.

## Exercice 6 — Statistiques
```php
<?php
declare(strict_types=1);

function sommeNotes(array $notes): int
{
    return array_sum($notes);
}

function moyenneNotes(array $notes): float
{
    if (count($notes) === 0) {
        throw new InvalidArgumentException("Impossible : liste de notes vide.");
    }
    return array_sum($notes) / count($notes);
}

function meilleureNote(array $notes): int
{
    if (count($notes) === 0) {
        throw new InvalidArgumentException("Impossible : liste de notes vide.");
    }
    return max($notes);
}

$notes = [12, 8, 15, 17, 9, 14];
echo sommeNotes($notes) . PHP_EOL;      // 75
echo moyenneNotes($notes) . PHP_EOL;    // 12.5
echo meilleureNote($notes) . PHP_EOL;   // 17
```

## Exercice 7 — Validation Fail Fast
```php
<?php
declare(strict_types=1);

function creerCompte(string $email, string $motDePasse, int $age): array
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Email invalide.");
    }
    if (strlen($motDePasse) < 8) {
        throw new InvalidArgumentException("Le mot de passe doit faire au moins 8 caractères.");
    }
    if ($age < 13 || $age > 130) {
        throw new InvalidArgumentException("Âge hors limites (13–130).");
    }
    return ['email' => $email, 'age' => $age];
}
```
> Les 3 clauses de garde échouent **vite et clairement**. Le « vrai » travail (le `return`)
> ne s'exécute que si **tout** est valide.

## Exercice 8 — Refactoring
**Principes appliqués** : **DRY** (le calcul et l'affichage étaient dupliqués 3 fois),
**KISS** + **Explicite** (`match` clair, typage), **SoC** (la fonction **calcule**, l'affichage
est séparé), **Fail Fast** (opérateur inconnu → exception).
```php
<?php
declare(strict_types=1);

function calculer(float $a, float $b, string $operateur): float
{
    return match ($operateur) {
        '+' => $a + $b,
        '-' => $a - $b,
        '*' => $a * $b,
        '/' => $b !== 0.0 ? $a / $b : throw new InvalidArgumentException("Division par zéro."),
        default => throw new InvalidArgumentException("Opérateur inconnu : $operateur"),
    };
}

// L'affichage est séparé du calcul :
echo "Résultat : " . calculer(6, 3, '*') . PHP_EOL;   // Résultat : 18
```

---

## 🎉 Bilan du Niveau 1

Tu sais maintenant :
- ✅ Installer et utiliser l'environnement PHP pro (PHP, Composer, VSCode, Git)
- ✅ Écrire du PHP **typé** avec `declare(strict_types=1)` et `===`
- ✅ Conditions (`match`), boucles (`foreach`), et **clauses de garde**
- ✅ Des fonctions **typées, courtes, à responsabilité unique**
- ✅ Et surtout : appliquer **KISS, DRY, SoC, Fail Fast, Explicite** dès les bases

👉 Prochaine étape : **[PHP intermédiaire](../Niveau-2-PHP-Intermediaire/)** (tableaux,
exceptions, fichiers, PSR). 🚀
