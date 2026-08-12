# 📝 Niveau 2 — Exercices

Un fichier `.php` par exercice, avec `declare(strict_types=1);`. Chaque exercice indique son
**🎯 But**. Cherche 🔎 avant les [corrigés](./corriges.md).

> 🎯 **Exigence** : privilégie les **fonctions de tableaux** aux boucles manuelles quand c'est
> plus clair (DRY), et **échoue vite** avec des exceptions (Fail Fast).

---

## Exercice 1 — Le panier (tableau associatif) 🛒
> 🎯 **But** : manipuler un **tableau associatif** et le parcourir avec `foreach`.

Modélise un panier comme un tableau `['produit' => ['prix' => x, 'qte' => y], ...]`. Écris :
1. `ajouterAuPanier(array $panier, string $produit, float $prix, int $qte): array`
2. `totalPanier(array $panier): float` (somme des `prix × qte`)
3. Affiche le panier ligne par ligne, puis le total.

---

## Exercice 2 — `map`, `filter`, `reduce` 🔧
> 🎯 **But** : remplacer des boucles par les **fonctions de tableaux** (style déclaratif).

À partir de `$nombres = [4, 7, 2, 9, 1, 8, 5];`, **sans écrire de `for`/`foreach`** :
1. Crée la liste des **carrés** (`array_map`).
2. Garde uniquement les nombres **pairs** (`array_filter`).
3. Calcule la **somme** (`array_reduce`).
4. Compare la lisibilité avec la version en boucle. Laquelle est la plus DRY ?

---

## Exercice 3 — Trier des objets 📊
> 🎯 **But** : trier un tableau de données avec `usort` et une fonction de comparaison.

À partir d'une liste d'utilisateurs `[['nom' => 'Marie', 'age' => 30], ...]` :
1. Trie-les par **âge croissant**.
2. Puis par **nom alphabétique**.
> 🔎 Indice : `usort($users, fn($a, $b) => $a['age'] <=> $b['age']);` (opérateur spaceship `<=>`).

---

## Exercice 4 — Générateur de slug 🔤
> 🎯 **But** : manipuler les **chaînes** (fonctions `str_*`, `mb_*`).

Écris `genererSlug(string $titre): string` qui transforme `"Mon Premier Article !"` en
`"mon-premier-article"` : minuscules, espaces → tirets, caractères spéciaux retirés.
> 🔎 `strtolower`, `trim`, `preg_replace`. (Bonus : gérer les accents é→e.)

---

## Exercice 5 — Exceptions personnalisées 🛡️
> 🎯 **But** : créer une **exception métier** et la gérer avec `try/catch` (Fail Fast).

1. Crée une classe `SoldeInsuffisantException extends Exception`.
2. Écris `retirer(float $solde, float $montant): float` qui **lève** cette exception si
   `$montant > $solde`, sinon retourne le nouveau solde.
3. Dans un `try/catch`, appelle-la avec un cas valide et un cas invalide, en affichant un
   message clair pour chacun.

---

## Exercice 6 — Persistance en JSON 💾
> 🎯 **But** : lire/écrire un fichier **JSON** (base d'un stockage simple).

Écris deux fonctions :
1. `sauvegarder(array $donnees, string $fichier): void` (encode en JSON, écrit le fichier).
2. `charger(string $fichier): array` (lit le fichier, décode ; retourne `[]` s'il n'existe pas).
Teste : sauvegarde une liste de tâches, recharge-la, affiche-la.
> ⚠️ Gère les erreurs d'encodage/décodage (Fail Fast) : `json_encode` peut échouer.

---

## Exercice 7 — Calculs de dates 📅
> 🎯 **But** : manipuler les dates avec `DateTimeImmutable` (jamais `DateTime` mutable).

1. `calculerAge(string $dateNaissance): int` (format `AAAA-MM-JJ` → âge en années).
2. `joursAvant(string $dateFuture): int` (nombre de jours d'ici à cette date).
> 🔎 `new DateTimeImmutable(...)`, `->diff(...)`, propriétés `->y`, `->days`.

---

## Exercice 8 — Refactoring : pipeline de données 🌟
> 🎯 **But** : composer `filter` → `map` → `reduce` proprement (DRY + KISS + Explicite).

À partir d'une liste de commandes `[['montant' => 120, 'payee' => true], ...]`, calcule en
**une chaîne de traitements** le **chiffre d'affaires des commandes payées uniquement** :
`array_filter` (payées) → `array_map` (les montants) → `array_reduce` (somme). Nomme chaque
étape clairement.

---

## Exercice 9 — Autoloading PSR-4 (bonus) 📦
> 🎯 **But** : découvrir l'**autoloading** de Composer (préparation à Laravel).

1. Crée un `composer.json` déclarant un namespace `App\` → dossier `src/`.
2. Lance `composer dump-autoload`.
3. Crée `src/Calculatrice.php` (classe `App\Calculatrice`) et utilise-la depuis `index.php`
   via `require 'vendor/autoload.php';` — **sans** aucun `require` manuel de la classe.

---

👉 Correction : [corriges.md](./corriges.md)
