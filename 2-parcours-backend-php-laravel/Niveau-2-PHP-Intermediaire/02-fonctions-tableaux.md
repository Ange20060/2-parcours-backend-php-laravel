# Leçon 2.2 — Les fonctions de tableaux (map / filter / reduce)

> 🎯 **Objectif** : transformer des tableaux de façon **déclarative** (dire *quoi* faire, pas
> *comment* boucler). Ces fonctions rendent le code plus court, plus lisible et plus **DRY**.

---

## 🧠 Style impératif vs déclaratif

```php
<?php
// Impératif : on décrit CHAQUE étape de la boucle
$carres = [];
foreach ([1, 2, 3, 4] as $n) {
    $carres[] = $n * $n;
}

// Déclaratif : on dit "transforme chaque élément en son carré"
$carres = array_map(fn(int $n): int => $n * $n, [1, 2, 3, 4]);
```

Les deux donnent `[1, 4, 9, 16]`. Le second est plus concis et **exprime l'intention**.

> 💡 Rappel : `fn($x) => ...` est une **fonction fléchée** (arrow function). Utile pour ces
> traitements courts.

---

## 🔧 `array_map` — transformer chaque élément

```php
<?php
$prixHT  = [100.0, 50.0, 20.0];
$prixTTC = array_map(fn(float $p): float => $p * 1.20, $prixHT);
// [120.0, 60.0, 24.0]
```

---

## 🔍 `array_filter` — garder certains éléments

```php
<?php
$nombres = [4, 7, 2, 9, 1, 8];
$pairs = array_filter($nombres, fn(int $n): bool => $n % 2 === 0);
// [4, 2, 8]  — MAIS les clés d'origine sont conservées (0 => 4, 2 => 2, 5 => 8)
```

> ⚠️ **Piège classique** : `array_filter` **conserve les clés**. Si tu as besoin d'une liste
> ré-indexée proprement (0, 1, 2…), enveloppe avec **`array_values(...)`** :
>
> ```php
> $pairs = array_values(array_filter($nombres, fn($n) => $n % 2 === 0));
> ```

---

## ➕ `array_reduce` — réduire à une seule valeur

Accumule tous les éléments en **un** résultat (une somme, un total, une concaténation) :

```php
<?php
$montants = [120.0, 80.0, 200.0];
$total = array_reduce(
    $montants,
    fn(float $acc, float $m): float => $acc + $m,
    0.0     // valeur de départ de l'accumulateur
);
// 400.0
```

- `$acc` = l'accumulateur (le résultat en cours de construction).
- Le 3ᵉ argument = la **valeur initiale** de l'accumulateur.

---

## 🔗 Composer les traitements (un « pipeline »)

La vraie puissance : **enchaîner** filter → map → reduce. Exemple — le CA des commandes payées :

```php
<?php
$commandes = [
    ["montant" => 120.0, "payee" => true],
    ["montant" => 80.0,  "payee" => false],
    ["montant" => 200.0, "payee" => true],
];

$payees   = array_filter($commandes, fn(array $c): bool => $c["payee"]);
$montants = array_map(fn(array $c): float => $c["montant"], $payees);
$ca       = array_reduce($montants, fn(float $acc, float $m): float => $acc + $m, 0.0);
// 320.0
```

Chaque étape a **un nom clair** et **une responsabilité** : filtrer → extraire → additionner.
C'est lisible « comme une phrase » — l'inverse d'une grosse boucle fourre-tout.

---

## 🔀 Trier avec `usort` et l'opérateur « spaceship »

Pour trier un tableau d'objets/associatifs selon un critère, `usort` prend une **fonction de
comparaison** qui doit renvoyer un négatif / zéro / positif. L'opérateur **`<=>`** (spaceship)
fait exactement ça :

```php
<?php
$users = [
    ["nom" => "Sofia", "age" => 25],
    ["nom" => "Marie", "age" => 30],
    ["nom" => "Ali",   "age" => 25],
];

// par âge croissant
usort($users, fn(array $a, array $b): int => $a["age"] <=> $b["age"]);

// par nom (alphabétique)
usort($users, fn(array $a, array $b): int => strcmp($a["nom"], $b["nom"]));
```

> 💡 `$a <=> $b` vaut `-1`, `0` ou `1`. Pour l'ordre **décroissant**, inverse : `$b["age"] <=> $a["age"]`.

---

## 🔎 À toi de chercher

> 1. `array_column($users, "nom")` : extraire une colonne d'une liste d'associatifs. Très pratique.
> 2. `array_sum`, `array_unique`, `array_slice`, `array_search` — que font-elles ?
> 3. Différence entre `usort`, `uasort` et `uksort`.

---

## 🎓 Ce qu'il faut retenir

- **`array_map`** transforme, **`array_filter`** sélectionne, **`array_reduce`** accumule.
- `array_filter` **garde les clés** → `array_values()` pour ré-indexer.
- **Enchaîne** ces fonctions (filter → map → reduce) pour un code déclaratif et **DRY**.
- **`usort` + `<=>`** pour trier des collections selon un critère.

👉 Leçon suivante : [Les chaînes de caractères](./03-chaines.md)
