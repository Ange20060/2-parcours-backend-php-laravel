# Leçon 2.3 — Les chaînes de caractères

> 🎯 **Objectif** : manipuler le texte avec les fonctions `str_*` et `mb_*`. En backend, on
> nettoie, valide et transforme du texte en permanence (emails, slugs, saisies utilisateur…).

---

## ✂️ Les fonctions de base

```php
<?php
declare(strict_types=1);

$texte = "  Bonjour le Monde  ";

echo strlen($texte);            // longueur (en octets)
echo trim($texte);              // enlève les espaces au début/fin → "Bonjour le Monde"
echo strtolower($texte);        // minuscules
echo strtoupper($texte);        // MAJUSCULES
echo ucfirst("marie");          // "Marie" (1re lettre en majuscule)
echo str_replace("Monde", "PHP", $texte);   // remplacer
```

---

## 🔎 Chercher dans une chaîne

```php
<?php
$email = "marie@exemple.fr";

var_dump(str_contains($email, "@"));    // true — contient ?
var_dump(str_starts_with($email, "m")); // true — commence par ?
var_dump(str_ends_with($email, ".fr")); // true — finit par ?
echo strpos($email, "@");               // 5 — position (ou false si absent)
```
> 💡 `str_contains`, `str_starts_with`, `str_ends_with` (PHP 8) rendent le code **explicite** et
> lisible — préfère-les à des `strpos(...) !== false` obscurs.

---

## 🔗 Découper et assembler

```php
<?php
// Découper une chaîne en tableau
$csv = "pomme,banane,cerise";
$fruits = explode(",", $csv);        // ["pomme", "banane", "cerise"]

// Assembler un tableau en chaîne
$ligne = implode(" | ", $fruits);    // "pomme | banane | cerise"

// Extraire une portion
echo substr("Bonjour", 0, 3);        // "Bon"
```

---

## 🌍 Accents et UTF-8 : les fonctions `mb_*`

⚠️ Les fonctions classiques comptent les **octets**, pas les **caractères**. Avec des accents
(UTF-8), un « é » fait 2 octets → `strlen("café")` renvoie **5**, pas 4 ! Pour du texte
international, utilise les versions **`mb_`** (*multibyte*) :

```php
<?php
echo strlen("café");        // 5  ❌ (octets)
echo mb_strlen("café");     // 4  ✅ (caractères)
echo mb_strtolower("ÉCOLE"); // "école" (gère les accents)
```
> 🧠 **Règle backend** : dès qu'il y a du texte utilisateur (donc des accents possibles),
> pense **`mb_*`**. C'est un réflexe de pro.

---

## 🧩 Exemple concret : générer un « slug »

Un **slug** transforme un titre en identifiant d'URL (`"Mon Article !"` → `"mon-article"`) :

```php
<?php
function genererSlug(string $titre): string
{
    $slug = mb_strtolower(trim($titre));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);  // tout sauf lettres/chiffres → tiret
    return trim($slug, '-');                            // enlève les tirets aux extrémités
}

echo genererSlug("Mon Premier Article !");  // mon-premier-article
```
> 🔎 `preg_replace` utilise une **expression régulière** (regex). On les découvre ici en douceur ;
> retiens juste que `[^a-z0-9]+` signifie « un ou plusieurs caractères qui ne sont **pas** une
> lettre minuscule ou un chiffre ».

---

## 🔤 Rappel : interpolation & concaténation

```php
<?php
$nom = "Marie";
echo "Bonjour {$nom} !";          // interpolation (guillemets doubles) — préféré
echo 'Bonjour ' . $nom . ' !';    // concaténation avec le point
```

---

## 🔎 À toi de chercher

> 1. `sprintf()` / `number_format()` : formater proprement un nombre/prix (`number_format(1234.5, 2)` → `"1 234,50"`).
> 2. `filter_var($email, FILTER_VALIDATE_EMAIL)` : **valider** un email (bien plus fiable qu'une regex maison).
> 3. Cherche ce qu'est une **regex** (expression régulière) et joue avec un testeur en ligne (regex101).

---

## 🎓 Ce qu'il faut retenir

- Base : `trim`, `strtolower/upper`, `str_replace`, `substr`.
- Chercher : `str_contains`, `str_starts_with`, `str_ends_with` (lisibles et explicites).
- Découper/assembler : `explode` / `implode`.
- **Texte avec accents → fonctions `mb_*`** (réflexe backend).
- Valider un email avec **`filter_var(..., FILTER_VALIDATE_EMAIL)`**.

👉 Leçon suivante : [La gestion d'erreurs : les exceptions](./04-exceptions.md)
