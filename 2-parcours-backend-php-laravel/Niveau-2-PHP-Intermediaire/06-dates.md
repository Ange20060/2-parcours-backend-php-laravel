# Leçon 2.6 — Les dates

> 🎯 **Objectif** : manipuler les dates et durées correctement avec **`DateTimeImmutable`**, en
> évitant les pièges classiques (mutation surprise, comparaison de types).

---

## 📅 Créer une date

```php
<?php
declare(strict_types=1);

$maintenant = new DateTimeImmutable();                    // date/heure actuelle
$noel = new DateTimeImmutable("2026-12-25");
$precis = new DateTimeImmutable("2026-08-10 14:30:00");
$demain = new DateTimeImmutable("tomorrow");              // PHP comprend l'anglais courant
```

## 🖨️ Afficher une date : `format()`

```php
<?php
echo $noel->format("Y-m-d");        // 2026-12-25
echo $noel->format("d/m/Y");        // 25/12/2026
echo $noel->format("H:i");          // 00:00
```

|    Symbole    | Sens                     | Exemple |
| :-----------: | ------------------------ | ------- |
|     `Y`     | année (4 chiffres)      | 2026    |
| `m` / `d` | mois / jour (2 chiffres) | 08 / 10 |
| `H` / `i` | heures / minutes         | 14 / 30 |

> 🔎 La liste complète des symboles est dans la doc PHP (`date` format) — à garder sous la main.

---

## 🧊 Pourquoi `DateTimeImmutable` et pas `DateTime` ?

Il existe deux classes : `DateTime` (**mutable**) et `DateTimeImmutable` (**immuable**).
Avec `DateTime`, une méthode comme `->modify()` **change l'objet original** — source de bugs
sournois :

```php
<?php
// ❌ Piège avec DateTime (mutable)
$debut = new DateTime("2026-01-01");
$fin = $debut;
$fin->modify("+1 month");
echo $debut->format("Y-m-d");   // 2026-02-01 (!!) $debut a AUSSI changé

// ✅ DateTimeImmutable : chaque modification renvoie un NOUVEL objet
$debut = new DateTimeImmutable("2026-01-01");
$fin = $debut->modify("+1 month");
echo $debut->format("Y-m-d");   // 2026-01-01 (intact) ✅
echo $fin->format("Y-m-d");     // 2026-02-01
```

> 🧠 **Règle** : utilise **toujours `DateTimeImmutable`**. C'est plus **[explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)**
> et ça évite les effets de bord. (Laravel utilise Carbon, une version enrichie — même idée.)

---

## ⏳ Calculer une durée entre deux dates : `diff()`

```php
<?php
$naissance = new DateTimeImmutable("2000-01-15");
$aujourdhui = new DateTimeImmutable("today");

$intervalle = $naissance->diff($aujourdhui);   // un objet DateInterval

echo $intervalle->y;       // nombre d'années → l'âge
echo $intervalle->days;    // nombre TOTAL de jours entre les deux
```

Exemple concret — l'âge et « dans combien de jours » :

```php
<?php
function calculerAge(string $dateNaissance): int
{
    $naissance = new DateTimeImmutable($dateNaissance);
    return $naissance->diff(new DateTimeImmutable("today"))->y;
}

function joursAvant(string $dateFuture): int
{
    $cible = new DateTimeImmutable($dateFuture);
    return (int) (new DateTimeImmutable("today"))->diff($cible)->days;
}
```

---

## ➕ Ajouter / soustraire du temps

```php
<?php
$date = new DateTimeImmutable("2026-08-10");

$plusTard = $date->modify("+2 weeks");
$avant    = $date->modify("-3 days");
$echeance = $date->add(new DateInterval("P30D"));   // +30 jours (P30D = Period 30 Days)
```

---

## ⚠️ Le piège de la comparaison de types

Compare **toujours des dates avec des dates**, jamais une date avec une **chaîne** :

```php
<?php
$d = new DateTimeImmutable("2026-08-10");

// ❌ Comparer un objet date à une string ne fait pas ce qu'on croit
if ("2026-08-10" == $d) { /* faux / imprévisible */ }

// ✅ Comparer deux objets DateTimeImmutable (l'opérateur < > == fonctionne)
if ($d < new DateTimeImmutable("today")) {
    echo "Date passée";
}
```

> 💡 C'est un bug **très** fréquent chez les débutants : stocker une date en texte d'un côté et
> en objet de l'autre, puis les comparer → ça ne matche jamais. **Uniformise le type.**

---

## 🔎 À toi de chercher

> 1. `DateTimeImmutable::createFromFormat("d/m/Y", "25/12/2026")` : lire une date dans un format précis.
> 2. Les **fuseaux horaires** (`DateTimeZone`) : pourquoi c'est crucial pour une app en production.
> 3. En Laravel, cherche **Carbon** (`now()`, `->diffForHumans()`) — la lib de dates par défaut.

---

## 🎓 Ce qu'il faut retenir

- Crée avec `new DateTimeImmutable(...)`, affiche avec `->format("Y-m-d")`.
- **Utilise `DateTimeImmutable`** (immuable) — jamais `DateTime` mutable : évite les effets de bord.
- Durée entre deux dates : `->diff(...)` → `->y` (années), `->days` (jours totaux).
- Ajouter/retirer : `->modify("+2 weeks")`, `->add(new DateInterval("P30D"))`.
- **Compare des dates avec des dates** (même type), jamais avec des chaînes.

👉 Leçon suivante : [Composer, autoloading &amp; PSR](./07-composer-psr.md)
