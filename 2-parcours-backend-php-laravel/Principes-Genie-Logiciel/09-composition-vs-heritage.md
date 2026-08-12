# 9 — Composition > Héritage

> 🎯 **Le principe** : construis tes systèmes en **assemblant de petites briques**
> (composition) plutôt qu'en empilant des classes dans de longues hiérarchies d'héritage.
>
> 💥 **Ce que ça tue** : les arbres de classes rigides et emmêlés, où un changement en haut
> casse tout en bas.

---

## Le problème (❌) : l'héritage qui dérape

On modélise des « types d'employés » par héritage. Ça marche… jusqu'au premier cas hybride.

```php
<?php
class Employe { /* ... */ }
class Developpeur extends Employe { public function coder() {} }
class Manager extends Employe { public function gererEquipe() {} }

// Et maintenant... un "Lead Developer" qui code ET gère une équipe ?
// PHP n'a pas d'héritage multiple. On est coincé.
class LeadDev extends Developpeur { /* comment hériter aussi de Manager ??? */ }
```

Autre piège classique : `Carre extends Rectangle`. Un carré **est-il** un rectangle ? En
maths oui, en code ça viole le **L** de [SOLID](./11-SOLID.md) (un carré force largeur = hauteur,
ce qui casse le comportement attendu d'un rectangle).

---

## La solution (✅) : composer des capacités

On modélise les **capacités** comme des briques qu'on **assemble**.

```php
<?php
interface Codeur { public function coder(): void; }
interface Gestionnaire { public function gererEquipe(): void; }

// Des "briques" réutilisables (via traits ou classes déléguées)
trait PeutCoder      { public function coder(): void { /* ... */ } }
trait PeutGerer      { public function gererEquipe(): void { /* ... */ } }

class Developpeur implements Codeur { use PeutCoder; }
class Manager     implements Gestionnaire { use PeutGerer; }

// Le cas hybride devient trivial : on COMPOSE les capacités
class LeadDev implements Codeur, Gestionnaire
{
    use PeutCoder, PeutGerer;
}
```

Chaque capacité est **indépendante**, **réutilisable** et **combinable** à volonté. Pas de
hiérarchie rigide.

---

## 🧠 « has-a » plutôt que « is-a »

La composition, c'est souvent **déléguer** à un objet interne (relation « **a un** ») :

```php
<?php
class Facture
{
    // Une facture "a un" calculateur de taxes, plutôt que d'"être" un calculateur
    public function __construct(private CalculateurTaxes $taxes) {}

    public function total(float $ht): float
    {
        return $ht + $this->taxes->calculer($ht);
    }
}
```

Tu peux **échanger** le calculateur (français, luxembourgeois, hors taxes…) sans toucher à
`Facture`. Impossible avec de l'héritage figé.

> 📏 **Règle pratique** : demande-toi « **est un** » ou « **a un / peut** » ? Si c'est « a un »
> ou « peut faire », **compose**. L'héritage se justifie surtout pour une **vraie**
> spécialisation « est un » stable.

---

## 🔗 Liens avec les autres principes

- La composition produit un **[faible couplage](./06-cohesion-couplage.md)**.
- Elle sert le **O** et le **L** de **[SOLID](./11-SOLID.md)** (ouvert/fermé, substitution).

---

## 🏋️ Mini-exercice

On veut modéliser des moyens de paiement : Carte, PayPal, Virement — chacun avec une méthode
`payer(float $montant)`. Un collègue propose `class PayPal extends Paiement`. **Propose plutôt
une solution par composition** (indice : une **interface** `MoyenPaiement`).

> Corrigé dans [corriges.md](./corriges.md).
