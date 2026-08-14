# Leçon 3.4 — Interfaces et polymorphisme

> 🎯 **Objectif** : définir des **contrats** avec les **interfaces**, et écrire du code qui
> fonctionne avec **n'importe quelle** implémentation grâce au **polymorphisme**. C'est la clé de
> la flexibilité — et le fondement de SOLID et de Laravel.

---

## 📜 Une interface : un contrat, sans code

Une **interface** déclare **ce qu'une classe doit savoir faire** (les méthodes), **sans** dire
**comment**. C'est une **promesse**. Mot-clé : `interface`, puis `implements`.

```php
<?php
declare(strict_types=1);

interface Forme
{
    public function aire(): float;   // signature seulement : PAS de corps
}

class Cercle implements Forme
{
    public function __construct(private float $rayon) {}
    public function aire(): float { return pi() * $this->rayon ** 2; }
}

class Rectangle implements Forme
{
    public function __construct(private float $l, private float $h) {}
    public function aire(): float { return $this->l * $this->h; }
}
```

Toute classe qui `implements Forme` **s'engage** à fournir une méthode `aire()`. Si elle
l'oublie → **erreur** à la compilation. Le contrat est **garanti**.

---

## 🎭 Le polymorphisme : un seul code, plusieurs formes

**Polymorphisme** = « plusieurs formes ». On écrit du code qui parle à l'**interface**, et il
fonctionne avec **toutes** les implémentations, présentes **et futures** :

```php
<?php
function afficherAire(Forme $forme): void   // accepte N'IMPORTE QUELLE Forme
{
    echo "Aire : " . round($forme->aire(), 2) . PHP_EOL;
}

afficherAire(new Cercle(3));       // marche
afficherAire(new Rectangle(4, 5)); // marche aussi

// Demain, une nouvelle forme — SANS toucher afficherAire :
class Triangle implements Forme
{
    public function __construct(private float $base, private float $hauteur) {}
    public function aire(): float { return $this->base * $this->hauteur / 2; }
}
afficherAire(new Triangle(6, 2));  // marche immédiatement 🎉
```

`afficherAire` ne connaît **pas** les classes concrètes : elle connaît seulement le **contrat**
`Forme`. On peut ajouter des formes à l'infini **sans jamais la modifier**.

> 🧩 C'est le **O** de [SOLID](../Principes-Genie-Logiciel/11-SOLID.md) (**Ouvert/Fermé**) : ouvert
> à l'extension (nouvelles formes), fermé à la modification (on ne touche pas au code existant).

---

## 🆚 Interface ou classe abstraite ?

|                      | **Interface**                          | **Classe abstraite**            |
| -------------------- | -------------------------------------------- | ------------------------------------- |
| Contient du code ?   | Non (juste des signatures)\*                 | Oui (méthodes communes + abstraites) |
| Combien par classe ? | **Plusieurs** (`implements A, B, C`) | **Une seule** (`extends`)     |
| Relation             | «**peut faire** / sait faire »       | «**est un** »                 |

\* Une interface peut définir des **constantes**, mais pas d'état ni de logique.

> 🧠 **Réflexe** : programme **contre une interface**, pas contre une classe concrète. Ça rend
> ton code **[faiblement couplé](../Principes-Genie-Logiciel/06-cohesion-couplage.md)** et **testable**.
> Une classe peut implémenter **plusieurs** interfaces (là où l'héritage est limité à une classe).

---

## 🔌 Exemple backend : brancher plusieurs implémentations

```php
<?php
interface MoyenPaiement
{
    public function payer(float $montant): string;
}

class CarteBancaire implements MoyenPaiement
{
    public function payer(float $m): string { return "Payé $m € par carte."; }
}
class Paypal implements MoyenPaiement
{
    public function payer(float $m): string { return "Payé $m € via PayPal."; }
}

class Caisse
{
    public function encaisser(MoyenPaiement $moyen, float $montant): string
    {
        return $moyen->payer($montant);   // marche avec TOUS les moyens
    }
}

$caisse = new Caisse();
echo $caisse->encaisser(new CarteBancaire(), 49.99);
echo $caisse->encaisser(new Paypal(), 120.0);
```

Ajouter Apple Pay demain = **une nouvelle classe** qui `implements MoyenPaiement`. `Caisse` ne
change pas. C'est **exactement** ce que fait Laravel avec ses « contrats ».

---

## 🔎 À toi de chercher

> 1. Cherche les **interfaces natives de PHP** : `Countable`, `Iterator`, `Stringable`,
>    `JsonSerializable`. À quoi servent-elles ?
> 2. Une interface peut **étendre** une autre interface (`interface B extends A`). Dans quel cas ?
> 3. En Laravel, cherche « contracts » (`Illuminate\Contracts\...`) : le framework repose
>    massivement sur des interfaces.

---

## 🎓 Ce qu'il faut retenir

- Une **interface** = un **contrat** (des signatures, pas de code) ; `implements` s'y engage.
- **Polymorphisme** : du code qui parle à l'**interface** marche avec **toutes** les
  implémentations, présentes et futures (**Open/Closed**).
- Une classe implémente **plusieurs** interfaces (≠ héritage limité à une classe).
- **Programme contre une interface** → faible couplage + testabilité.

👉 Leçon suivante : [Les traits](./05-traits.md)
