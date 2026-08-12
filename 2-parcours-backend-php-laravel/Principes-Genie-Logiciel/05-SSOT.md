# 5 — SSOT (Single Source of Truth / Source unique de vérité)

> 🎯 **Le principe** : chaque **fait** (une valeur, une règle, une donnée) a **une** source qui
> fait **autorité**. Tout le reste s'y **réfère** au lieu de la recopier.
>
> 💥 **Ce que ça tue** : les données dupliquées et **contradictoires** — deux endroits qui ne
> sont plus d'accord sur « la vérité ».

---

## Le problème (❌)

Le taux de TVA et la liste des statuts sont recopiés un peu partout. Un jour, ils **divergent**.

```php
<?php
// fichier panier.php
$statutsValides = ['en_attente', 'payee', 'expediee'];

// fichier admin.php  (quelqu'un a ajouté "annulee" ici seulement)
$statutsValides = ['en_attente', 'payee', 'expediee', 'annulee'];

// Résultat : le panier refuse un statut que l'admin accepte. Bug de cohérence.
```

---

## La solution (✅)

**Une** définition qui fait autorité, référencée partout.

```php
<?php
// StatutCommande.php — LA source de vérité
enum StatutCommande: string
{
    case EnAttente = 'en_attente';
    case Payee     = 'payee';
    case Expediee  = 'expediee';
    case Annulee   = 'annulee';
}

// Partout ailleurs, on s'y réfère :
$statut = StatutCommande::Payee;
$tous   = StatutCommande::cases();   // impossible de diverger
```

Ajouter un statut ? **Un seul endroit**. Plus jamais deux listes en désaccord.

---

## SSOT ne concerne pas que le code

C'est un principe **de données** au sens large :
- **En base de données** : une information n'est stockée qu'à un endroit (c'est le but de la
  **normalisation**, Niveau 6). On ne recopie pas le nom du client dans chaque commande : on
  garde une **clé étrangère** vers la table `clients`.
- **En configuration** : une valeur (URL d'API, clé secrète) vient d'**un** fichier de config
  (`.env` en Laravel), pas codée en dur à dix endroits.

---

## 🔗 Liens avec les autres principes

- SSOT est le **résultat** d'un **[DRY](./01-DRY.md)** bien appliqué à la **connaissance/donnée**.
- Il soutient le principe **[Explicite > Implicite](./08-explicite-vs-implicite.md)** : on sait
  **où** est la vérité.

---

## 🏋️ Mini-exercice

Dans une petite appli, le prix de livraison `4.99 €` apparaît en dur dans 3 fichiers
(le panier, la facture, la page d'accueil). Décris **où** tu placerais la source unique de
vérité et **comment** les 3 fichiers s'y référeraient.

> Corrigé dans [corriges.md](./corriges.md).
