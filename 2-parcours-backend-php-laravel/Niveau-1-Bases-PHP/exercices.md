


    # 📝 Niveau 1 — Exercices

Un fichier `.php` par exercice, avec `declare(strict_types=1);` en tête. Exécute chaque
programme avec `php exoN.php`. Chaque exercice indique son **🎯 But**. Cherche 🔎 avant les
[corrigés](./corriges.md).

> 🎯 **Exigence d'ingénieur** : un exercice n'est fini que si le code est **typé**, **lisible**
> et **simple**. À chaque fois, demande-toi quel principe s'applique.

---

## Exercice 1 — Environnement & premier script ⚙️

> 🎯 **But** : valider l'installation et exécuter du PHP en CLI.

1. Vérifie `php --version` et `composer --version`.
2. Écris `profil.php` qui déclare tes infos (nom, âge, langage préféré) dans des variables
   **typées et nommées** puis les affiche joliment.
3. Initialise un dépôt Git et commite.

---

## Exercice 2 — Le convertisseur de température 🌡️

> 🎯 **But** : variables typées, opérateurs, `float`.

Écris une fonction `celsiusVersFahrenheit(float $c): float` (formule `F = C × 9/5 + 32`),
puis affiche quelques conversions. Le résultat doit être **retourné**, pas affiché dans la
fonction (SoC).

---

## Exercice 3 — Comparaisons strictes 🎯

> 🎯 **But** : comprendre `==` vs `===` (piège n°1 de PHP).

Prédis le résultat de chaque ligne **avant** de tester, puis vérifie avec `var_dump` :

```php
0 == "a"        // ?
"1" == 1        // ?
"1" === 1       // ?
null == false   // ?
[] == false     // ?
```

Rédige une phrase de conclusion : pourquoi utiliser **toujours** `===` ?

---

## Exercice 4 — Appréciation avec `match` 📊

> 🎯 **But** : utiliser `match` plutôt qu'une cascade de `if`.

Écris `appreciation(int $note): string` qui retourne : `Excellent` (≥16), `Bien` (≥12),
`Passable` (≥10), `Insuffisant` (<10). Utilise `match(true)`.

> 🔎 Indice : `match(true) { $note >= 16 => ..., ... }`.

---

## Exercice 5 — FizzBuzz propre 🐝

> 🎯 **But** : boucle + conditions, avec du code lisible (KISS).

Affiche 1 à 30 : `Fizz` (multiple de 3), `Buzz` (multiple de 5), `FizzBuzz` (les deux).
Écris-le proprement, dans une fonction `fizzbuzz(int $n): string` testable séparément.

---

## Exercice 6 — Statistiques d'un tableau 📈

> 🎯 **But** : `foreach`, accumulation, fonctions à responsabilité unique.

À partir de `$notes = [12, 8, 15, 17, 9, 14];`, écris **trois fonctions distinctes** :
`sommeNotes(array $notes): int`, `moyenneNotes(array $notes): float`,
`meilleureNote(array $notes): int`. Chacune fait **une seule chose**.

> ⚠️ Gère le cas d'un tableau **vide** (Fail Fast : lève une exception).

---

## Exercice 7 — Validation Fail Fast 🛡️

> 🎯 **But** : appliquer les **clauses de garde** et le typage strict.

Écris `creerCompte(string $email, string $motDePasse, int $age): array` qui :

- refuse un email sans `@` ;
- refuse un mot de passe de moins de 8 caractères ;
- refuse un âge hors de [13, 130] ;
- sinon retourne un tableau des données.
  Chaque refus lève une `InvalidArgumentException` avec un **message clair**.

---

## Exercice 8 — Refactoring 🌟

> 🎯 **But** : appliquer **KISS**, **DRY**, **SoC**, **Explicite** sur du code « sale ».

Voici du code de débutant. Réécris-le proprement (typage, nommage, pas de duplication,
séparation calcul/affichage) :

```php
<?php
function f($a,$b,$op){
if($op=="+"){$r=$a+$b;echo "resultat: ".$r;}
if($op=="-"){$r=$a-$b;echo "resultat: ".$r;}
if($op=="*"){$r=$a*$b;echo "resultat: ".$r;}
}
```

Cite **explicitement** les principes que tu appliques.

---

👉 Correction : [corriges.md](./corriges.md)
