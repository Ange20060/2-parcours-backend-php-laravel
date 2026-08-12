# 1 — DRY (Don't Repeat Yourself)

> 🎯 **Le principe** : chaque **connaissance** (une règle, un calcul, une valeur) doit exister
> à **un seul endroit** dans le code.
>
> 💥 **Ce que ça tue** : le copier-coller, et le cauchemar de devoir corriger **le même bug à
> dix endroits** (en en oubliant toujours un).

---

## Le problème (❌)

Le même calcul de TVA est dupliqué. Le jour où le taux passe de 20 % à 21 %, il faut le
changer partout — et on **oubliera** un endroit.

```php
<?php
// Dans le panier
$totalPanier = $prixHT * 1.20;

// Dans la facture
$totalFacture = $montantHT * 1.20;

// Dans l'email de confirmation
$totalEmail = $sousTotal * 1.20;
```

---

## La solution (✅)

La connaissance « le taux de TVA et son calcul » vit à **un seul endroit**.

```php
<?php
const TAUX_TVA = 0.20;

function calculerTTC(float $montantHT): float
{
    return $montantHT * (1 + TAUX_TVA);
}

// Partout ailleurs, on RÉUTILISE :
$totalPanier  = calculerTTC($prixHT);
$totalFacture = calculerTTC($montantHT);
$totalEmail   = calculerTTC($sousTotal);
```

Changer le taux ? **Une seule ligne** à modifier. Corriger un bug de calcul ? **Un seul endroit.**

---

## ⚠️ Le piège : DRY ≠ « supprimer tout doublon qui se ressemble »

DRY parle de **connaissance dupliquée**, pas de **lignes qui se ressemblent par hasard**.
Deux morceaux de code identiques **aujourd'hui** mais qui répondent à des **règles métier
différentes** ne doivent **pas** être fusionnés : le jour où l'une des règles change, ton
abstraction commune explose.

> 🧠 « Une duplication est bien moins coûteuse qu'une mauvaise abstraction. » — Sandi Metz.
> Attends d'avoir vu la duplication **3 fois** avant de factoriser (la « règle de trois »).

---

## 🔗 Liens avec les autres principes

- Bien fait, DRY sert le **[SSOT](./05-SSOT.md)** (une source de vérité).
- Mal fait (factorisation prématurée), il augmente le **[couplage](./06-cohesion-couplage.md)**
  et viole **[YAGNI](./03-YAGNI.md)** / **[KISS](./02-KISS.md)**.

---

## 🏋️ Mini-exercice

Voici du code dupliqué. Refactorise-le pour respecter DRY, **sans** sur-abstraire :

```php
<?php
echo "Bonjour Marie, votre solde est de " . number_format(1500.5, 2) . " €";
echo "Bonjour Paul, votre solde est de "  . number_format(230.0, 2)  . " €";
echo "Bonjour Sofia, votre solde est de " . number_format(89.99, 2)  . " €";
```

> 💡 Piste : une fonction `messageSolde(string $nom, float $solde): string`.
> Corrigé dans [corriges.md](./corriges.md).
