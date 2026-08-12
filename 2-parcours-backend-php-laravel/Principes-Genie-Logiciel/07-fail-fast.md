# 7 — Fail Fast (Échouer vite)

> 🎯 **Le principe** : détecte et **rejette immédiatement** — et **bruyamment** — une entrée
> ou un état invalide. Mieux vaut planter tout de suite, clairement, que continuer en douce.
>
> 💥 **Ce que ça tue** : la **corruption silencieuse** — un bug qui pourrit les données et
> qu'on ne découvre que des semaines plus tard, très loin de sa cause.

---

## Le problème (❌)

Le code « avale » les problèmes et continue avec des valeurs absurdes.

```php
<?php
function calculerPrixUnitaire(float $total, int $quantite): float
{
    // Si quantite = 0, division par zéro "silencieuse" → INF, puis données corrompues
    return $total / $quantite;
}

function appliquerRemise(float $prix, $remise): float
{
    // $remise peut être n'importe quoi (négatif, > 100, une string...) : on ne vérifie rien
    return $prix - ($prix * $remise / 100);
}
```

Le programme **continue**, enregistre un prix aberrant en base, et le bug est découvert
3 semaines plus tard par un client furieux. La cause ? Introuvable.

---

## La solution (✅) : valider **à l'entrée**, échouer clairement

```php
<?php
function calculerPrixUnitaire(float $total, int $quantite): float
{
    if ($quantite <= 0) {
        throw new InvalidArgumentException("La quantité doit être strictement positive.");
    }
    return $total / $quantite;
}

function appliquerRemise(float $prix, float $remise): float
{
    if ($remise < 0 || $remise > 100) {
        throw new InvalidArgumentException("La remise doit être comprise entre 0 et 100 %.");
    }
    return $prix - ($prix * $remise / 100);
}
```

L'erreur explose **au bon endroit**, **au bon moment**, avec un **message clair**. On la
corrige en 5 minutes au lieu de 5 heures.

---

## 🧱 Les « guard clauses » (clauses de garde)

Valide **au début** de la fonction et sors tôt. Le code « heureux » reste lisible et non imbriqué.

```php
<?php
function traiterPaiement(?Commande $commande): void
{
    if ($commande === null) {
        throw new InvalidArgumentException("Commande manquante.");
    }
    if ($commande->montant <= 0) {
        throw new DomainException("Montant invalide.");
    }
    // ... à partir d'ici, on SAIT que tout est valide : le "vrai" travail commence.
}
```

> 💡 En PHP, active le **typage strict** en tête de fichier : `declare(strict_types=1);`.
> C'est du Fail Fast **gratuit** : PHP refuse une string là où tu attends un `int`.

---

## 🔗 Liens avec les autres principes

- Fail Fast rend le comportement **[explicite](./08-explicite-vs-implicite.md)** (pas de dérive
  silencieuse).
- Les clauses de garde servent la **[simplicité](./02-KISS.md)** (moins de `if` imbriqués).

---

## 🏋️ Mini-exercice

Ajoute des **clauses de garde** à cette fonction pour qu'elle échoue vite et clairement :

```php
<?php
function creerUtilisateur(string $email, int $age): array
{
    return ['email' => $email, 'age' => $age];
}
// Règles : l'email doit contenir "@", l'âge doit être entre 0 et 130.
```

> Corrigé dans [corriges.md](./corriges.md).
