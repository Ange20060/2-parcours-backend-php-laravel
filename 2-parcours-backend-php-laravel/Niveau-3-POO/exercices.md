# 📝 Niveau 3 — Exercices (POO)

Un fichier `.php` par exercice, `declare(strict_types=1);`. Chaque exercice indique son
**🎯 But**. Cherche 🔎 avant les [corrigés](./corriges.md).

> 🎯 **Exigence** : classes **cohésives**, propriétés **encapsulées** et **typées**. Dès qu'un
> choix se pose entre héritage et composition, applique le principe
> [Composition > Héritage](../Principes-Genie-Logiciel/09-composition-vs-heritage.md).

---

## Exercice 1 — Ta première classe 🧱
> 🎯 **But** : créer une classe avec propriétés typées, constructeur et méthode.

Crée une classe `CompteBancaire` avec un solde privé. Méthodes : `deposer(float $montant)`,
`retirer(float $montant)`, `solde(): float`. Un dépôt/retrait négatif doit lever une
exception (Fail Fast). Utilise la **promotion de propriétés** du constructeur.

---

## Exercice 2 — Encapsulation 🔒
> 🎯 **But** : protéger l'état interne (pourquoi `private` > `public`).

Reprends `CompteBancaire`. Montre qu'on **ne peut pas** faire `$compte->solde = -1000;` de
l'extérieur. Explique en 2 phrases pourquoi l'encapsulation protège l'**invariant** « le solde
ne peut pas devenir négatif ».

---

## Exercice 3 — Interface & polymorphisme 🎭
> 🎯 **But** : programmer **contre une interface** (base de l'Open/Closed).

1. Crée une interface `Forme` avec `aire(): float`.
2. Implémente `Cercle` et `Rectangle`.
3. Écris une fonction `afficherAire(Forme $forme): void` qui marche pour **n'importe quelle**
   forme — présente ou future. Ajoute un `Triangle` **sans** modifier `afficherAire`.

---

## Exercice 4 — Classe abstraite 🧬
> 🎯 **But** : factoriser du comportement commun avec une classe abstraite.

Crée une classe abstraite `Employe` (propriété `nom`, méthode abstraite `salaire(): float`,
méthode concrète `presentation(): string`). Crée `Developpeur` et `Manager` qui implémentent
`salaire()` différemment.

---

## Exercice 5 — Traits ♻️
> 🎯 **But** : réutiliser du code **horizontalement** entre classes non liées.

Crée un trait `Horodatable` fournissant `creerHorodatage(): string` (date actuelle formatée).
Utilise-le dans deux classes **sans lien d'héritage** : `Article` et `Commentaire`.

---

## Exercice 6 — Enum métier 🏷️
> 🎯 **But** : remplacer des « chaînes magiques » par une **enum** (SSOT + Explicite).

Crée une enum `StatutCommande: string` (`EnAttente`, `Payee`, `Expediee`, `Annulee`). Ajoute
une méthode `libelle(): string` qui retourne le libellé français de chaque cas.

---

## Exercice 7 — Composition (injection de dépendances) 🧩
> 🎯 **But** : appliquer **Composition > Héritage** et le **faible couplage**.

Crée une interface `Logger` avec `log(string $message): void`, une implémentation
`ConsoleLogger`, et une classe `CommandeService` qui **reçoit** un `Logger` par son
constructeur (injection). Montre qu'on peut lui donner un `ConsoleLogger` **ou** un
`LoggerNul` (qui ne fait rien) sans changer `CommandeService`.

---

## Exercice 8 — Mini-domaine complet 🌟
> 🎯 **But** : combiner classes, interface, enum et composition dans un petit modèle réaliste.

Modélise un mini système de paiement :
1. Interface `MoyenPaiement` avec `payer(float $montant): string` (retourne un message de reçu).
2. Implémentations `CarteBancaire` et `Paypal`.
3. Enum `Devise: string` (`EUR`, `USD`).
4. Classe `Caisse` qui reçoit un `MoyenPaiement` (composition) et encaisse un montant.
Écris un petit scénario d'utilisation. Cite les principes appliqués.

---

👉 Correction : [corriges.md](./corriges.md)
