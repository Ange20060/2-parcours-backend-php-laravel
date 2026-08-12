# Leçon 1.3 — Structures de contrôle

> 🎯 **Objectif** : faire **décider** et **répéter** ton code avec les conditions et les
> boucles de PHP, en gardant un code plat et lisible.

---

## 🔀 Les conditions : `if / elseif / else`

```php
<?php
declare(strict_types=1);

$age = 20;

if ($age >= 18) {
    echo "Majeur";
} elseif ($age >= 16) {
    echo "Presque majeur";
} else {
    echo "Mineur";
}
```

- La condition est entre **parenthèses**, le bloc entre **accolades**.
- `elseif` en un seul mot.

### Les clauses de garde (plutôt que l'imbrication)

Applique [Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md) : traite les cas invalides
**d'abord** et sors tôt. Le code « normal » reste **non imbriqué** et lisible.

```php
<?php
function traiter(?string $email): void
{
    if ($email === null) {
        throw new InvalidArgumentException("Email manquant.");
    }
    if (!str_contains($email, '@')) {
        throw new InvalidArgumentException("Email invalide.");
    }
    // ... code principal ici, sans être noyé dans des if imbriqués
}
```

---

## 🎯 `match` — le choix moderne (PHP 8)

`match` remplace avantageusement de longs `switch`. Il utilise la comparaison **stricte
(`===`)** et **retourne** une valeur :

```php
<?php
$statut = 'payee';

$libelle = match ($statut) {
    'en_attente' => "En attente de paiement",
    'payee'      => "Payée",
    'expediee'   => "Expédiée",
    default      => "Statut inconnu",
};
echo $libelle;   // Payée
```

> 💡 `match` est plus sûr que `switch` (pas d'oubli de `break`, comparaison stricte) et plus
> [explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md). Préfère-le.

Comparaison avec l'ancien `switch` (que tu croiseras encore) :

```php
<?php
switch ($statut) {
    case 'payee':
        $libelle = "Payée";
        break;          // ⚠️ l'oubli du break est un bug classique
    default:
        $libelle = "Inconnu";
}
```

---

## 🔁 Les boucles

### `for` — nombre de tours connu

```php
<?php
for ($i = 1; $i <= 5; $i++) {
    echo $i . " ";       // 1 2 3 4 5
}
```

### `while` — tant qu'une condition est vraie

```php
<?php
$compteur = 0;
while ($compteur < 3) {
    echo $compteur;
    $compteur++;         // ⚠️ sinon, boucle infinie
}
```

### `foreach` — LA boucle du quotidien (parcourir un tableau)

```php
<?php
$fruits = ["pomme", "banane", "cerise"];

foreach ($fruits as $fruit) {
    echo $fruit . PHP_EOL;
}

// avec la clé (indice ou clé de tableau associatif) :
$notes = ["maths" => 15, "info" => 18];
foreach ($notes as $matiere => $note) {
    echo "$matiere : $note" . PHP_EOL;
}
```

> 💡 En backend, tu passes ton temps à parcourir des **collections** (résultats de base de
> données, listes d'objets…). `foreach` sera ta boucle la plus utilisée.

---

## 🧭 `break` et `continue`

```php
<?php
foreach ($nombres as $n) {
    if ($n < 0) {
        continue;   // saute cet élément, passe au suivant
    }
    if ($n > 100) {
        break;      // arrête complètement la boucle
    }
    echo $n;
}
```

---

## 🔎 À toi de chercher

> 1. Cherche l'**opérateur ternaire** `$x = condition ? a : b;` et l'opérateur `?:` (Elvis).
>    Quand améliorent-ils la lisibilité, quand la nuisent-ils ?
> 2. `match` peut regrouper plusieurs cas : `'a', 'b' => ...`. Teste-le.
> 3. Réécris une chaîne de `if/elseif` (ex : note → appréciation) avec un `match`. Lequel est
>    le plus lisible ?

---

## 🎓 Ce qu'il faut retenir

- `if/elseif/else` : condition entre `()`, bloc entre `{}`.
- **Clauses de garde** pour éviter l'imbrication (Fail Fast + KISS).
- **`match`** (PHP 8) : plus sûr et lisible que `switch` — comparaison stricte, retourne une valeur.
- Boucles : `for`, `while`, et surtout **`foreach`** pour les collections.

👉 Leçon suivante : [Les fonctions](./04-fonctions.md)
