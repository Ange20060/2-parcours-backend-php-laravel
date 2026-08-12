# Leçon 1.2 — Syntaxe, variables et types

> 🎯 **Objectif** : maîtriser les variables, les **types** de PHP et les opérateurs, en
> écrivant du code **explicite** dès le départ.

---

## 💲 Les variables

En PHP, une variable commence **toujours** par `$` :

```php
<?php
declare(strict_types=1);

$prenom = "Marie";
$age = 25;
$estActif = true;

echo $prenom;   // Marie
```
- Nommage : **`$camelCase`** est la convention PHP (`$montantTotal`, `$estConnecte`).
- Sensible à la casse : `$age` ≠ `$Age`.
- Noms **parlants** ([Explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)) :
  `$dateNaissance`, pas `$d`.

---

## 🧬 Les types de base

| Type | Nom PHP | Exemple |
|---|---|---|
| Chaîne | `string` | `"Bonjour"`, `'Marie'` |
| Entier | `int` | `42`, `-7` |
| Décimal | `float` | `3.14`, `19.99` |
| Booléen | `bool` | `true`, `false` |
| Tableau | `array` | `[1, 2, 3]` (Niveau 2) |
| Null | `null` | absence de valeur |

Connaître/vérifier le type :
```php
<?php
var_dump($age);          // int(25)  — var_dump affiche type + valeur (outil de débogage n°1)
echo gettype($prenom);   // string
```

> 💡 `var_dump()` est ton meilleur ami pour déboguer : il montre le **type exact** et la valeur.

---

## 🔤 Les chaînes de caractères

Deux syntaxes, une différence **importante** :

```php
<?php
$prenom = "Marie";

// Guillemets DOUBLES : les variables sont interprétées
echo "Bonjour $prenom";          // Bonjour Marie
echo "Bonjour {$prenom} !";      // accolades = plus sûr et lisible

// Guillemets SIMPLES : texte brut, PAS d'interprétation
echo 'Bonjour $prenom';          // Bonjour $prenom  (littéralement)
```

Concaténation avec le point `.` :
```php
<?php
$message = "Bonjour " . $prenom . ", tu as " . $age . " ans.";
```

> 🧠 **Bonne pratique** : préfère l'**interpolation** `"...{$prenom}..."` à de longues chaînes
> de `.` : c'est plus lisible (KISS).

---

## ➗ Les opérateurs

**Arithmétiques** : `+ - * / % **`
```php
<?php
echo 10 % 3;    // 1   (modulo : le reste)
echo 2 ** 8;    // 256 (puissance)
```

**Comparaison — attention, point crucial en PHP :**
```php
<?php
var_dump(5 == "5");    // true  ❗ (== compare la valeur, avec conversion de type)
var_dump(5 === "5");   // false ✅ (=== compare valeur ET type)
```
> ⚠️ **Utilise TOUJOURS `===` et `!==`** (comparaison stricte). Le `==` fait des conversions
> implicites qui causent des bugs sournois — c'est l'inverse de
> [Explicite > Implicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md). Retiens
> cette règle : c'est une des plus importantes en PHP.

**Logiques** : `&&` (et), `||` (ou), `!` (non).

**Opérateurs pratiques :**
```php
<?php
$nom = $nomUtilisateur ?? "Anonyme";   // null coalescing : "Anonyme" si null/inexistant
$total += 5;                            // raccourci de $total = $total + 5
```

---

## 🧯 Le typage strict en action

Avec `declare(strict_types=1);`, PHP fait respecter les types — un filet de sécurité précieux :

```php
<?php
declare(strict_types=1);

function doubler(int $n): int
{
    return $n * 2;
}

echo doubler(5);      // 10
echo doubler("5");    // 💥 TypeError : "5" (string) refusé là où un int est attendu
```
Sans `strict_types`, PHP aurait **silencieusement** converti `"5"` en `5`. Le mode strict te
fait **échouer vite et clairement** — c'est ce qu'on veut.

---

## 🔄 Conversion de type (casting)

```php
<?php
$texte = "42";
$nombre = (int) $texte;       // 42 (int)
$prix = (float) "19.99";      // 19.99
$chaine = (string) 42;        // "42"
```

---

## 🔎 À toi de chercher

> 1. Teste `var_dump(0 == "a")` en PHP 7 vs PHP 8. Le comportement du `==` a **changé** —
>    cherche pourquoi. (Raison de plus d'utiliser `===` !)
> 2. Cherche la différence entre `null`, `""` (chaîne vide) et `0`. Quand `empty()` les
>    considère-t-elle toutes comme « vides » ?
> 3. Cherche les **constantes** : `const TVA = 0.20;` vs `define()`. Lien avec
>    [SSOT](../Principes-Genie-Logiciel/05-SSOT.md).

---

## 🎓 Ce qu'il faut retenir

- Variables en `$camelCase`, noms **parlants**, sensibles à la casse.
- Types : `string`, `int`, `float`, `bool`, `array`, `null`.
- **`===` / `!==`** (stricts) **toujours**, jamais `==`.
- Interpolation `"{$var}"` pour les chaînes ; `var_dump()` pour déboguer.
- Le **typage strict** transforme les erreurs silencieuses en erreurs claires.

👉 Leçon suivante : [Structures de contrôle](./03-structures-controle.md)
