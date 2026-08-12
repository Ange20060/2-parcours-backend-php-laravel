# Leçon 1.4 — Les fonctions

> 🎯 **Objectif** : écrire des fonctions **typées**, courtes et à responsabilité unique — la
> première vraie brique du code d'ingénieur.

---

## ✍️ Définir une fonction typée

En PHP moderne, on **type les paramètres ET le retour**. Toujours.

```php
<?php
declare(strict_types=1);

function additionner(int $a, int $b): int
{
    return $a + $b;
}

echo additionner(3, 5);   // 8
```
- `int $a, int $b` : types des **paramètres**.
- `: int` après les parenthèses : type de **retour**.
- Ce typage rend le contrat **explicite** et fait **échouer vite** en cas d'erreur d'appel.

Type de retour `void` quand la fonction ne retourne rien :
```php
<?php
function afficherBonjour(string $nom): void
{
    echo "Bonjour $nom";
}
```

---

## 🎁 Valeurs par défaut et arguments nommés

```php
<?php
function saluer(string $nom, string $civilite = "Bonjour"): string
{
    return "$civilite $nom";
}

echo saluer("Marie");                    // Bonjour Marie
echo saluer("Marie", "Bonsoir");         // Bonsoir Marie

// PHP 8 : arguments NOMMÉS → appel explicite et lisible
echo saluer(nom: "Paul", civilite: "Salut");
```
> 💡 Les **arguments nommés** rendent les appels
> [explicites](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md) : fini les
> `saluer("Paul", true, false)` illisibles.

---

## ↩️ `return` : calculer plutôt qu'afficher

Une bonne fonction **retourne** un résultat ; l'affichage se fait **ailleurs**. C'est la
[séparation des responsabilités](../Principes-Genie-Logiciel/04-SoC.md).

```php
<?php
// ✅ Bon : la fonction calcule et retourne. Réutilisable, testable.
function calculerTTC(float $ht, float $taux = 0.20): float
{
    return $ht * (1 + $taux);
}

// L'affichage est séparé :
echo "Total : " . calculerTTC(100) . " €";
```
Dès qu'un `return` s'exécute, la fonction **s'arrête**.

---

## 🎯 Types nullable et union

```php
<?php
// ?string = "une string OU null"
function trouverEmail(int $id): ?string
{
    // retourne l'email, ou null si pas trouvé
    return $id === 1 ? "marie@x.fr" : null;
}

// Type union (PHP 8) : plusieurs types possibles
function formater(int|float $nombre): string
{
    return number_format($nombre, 2);
}
```

---

## 🧩 Une fonction = une seule chose

Applique [Responsabilité unique](../Principes-Genie-Logiciel/04-SoC.md) et
[KISS](../Principes-Genie-Logiciel/02-KISS.md) : une fonction courte, un nom qui dit **ce
qu'elle fait**.

```php
<?php
// ❌ Fait trop de choses, nom vague
function traiter($d) { /* valide, calcule, affiche, enregistre... */ }

// ✅ Chacune fait UNE chose, nom clair
function validerCommande(array $commande): void { /* ... */ }
function calculerTotal(array $lignes): float { /* ... */ }
function enregistrerCommande(array $commande): void { /* ... */ }
```
> 🧠 **Signal d'alerte** : si tu utilises « **et** » pour décrire ta fonction (« ça valide
> **et** ça enregistre »), c'est qu'elle a **deux** responsabilités → découpe-la.

---

## 🔭 Portée des variables

Une variable définie dans une fonction est **locale**. Contrairement à d'autres langages, PHP
ne donne **pas** accès aux variables extérieures par défaut (et c'est tant mieux) :

```php
<?php
$taux = 0.20;

function calcul(float $ht): float
{
    // return $ht * $taux;  // ❌ $taux n'existe pas ici !
    return $ht * 1.20;
}
```
> ⚠️ Le mot-clé `global` existe mais est à **proscrire** : il crée des dépendances cachées
> (anti-[explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)). Passe plutôt la
> valeur en **paramètre** : `calcul(float $ht, float $taux)`.

---

## 🔎 À toi de chercher

> 1. Cherche les **fonctions fléchées** (`fn($x) => $x * 2`) et les **fonctions anonymes**
>    (`function() { ... }`). À quoi servent-elles ? (On les utilisera avec les tableaux au Niveau 2.)
> 2. Cherche ce qu'est un **type de retour `never`** et quand l'utiliser.
> 3. Écris une fonction `estPalindrome(string $mot): bool` (typée, testable), et vérifie-la
>    avec quelques cas.

---

## 🎓 Ce qu'il faut retenir

- **Type** les paramètres **et** le retour ; utilise `void`, `?type`, les types union.
- Les **arguments nommés** rendent les appels explicites.
- Une fonction **calcule et retourne** ; l'affichage se fait ailleurs (SoC).
- **Une fonction = une responsabilité**, un nom clair, du code court (KISS).
- Évite `global` : passe les dépendances en **paramètres**.

---

🎉 **Tu as fini les leçons du Niveau 1 !** Tu écris déjà du PHP typé et propre. Direction les
[exercices](./exercices.md).
