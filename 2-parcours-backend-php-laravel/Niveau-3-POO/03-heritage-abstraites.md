# Leçon 3.3 — Héritage et classes abstraites

> 🎯 **Objectif** : réutiliser du code entre classes proches avec l'**héritage**, et forcer un
> contrat commun avec les **classes abstraites** — tout en sachant **quand ne pas** en abuser.

---

## 🧬 L'héritage : une classe qui en étend une autre

Une classe **enfant** hérite des propriétés et méthodes de sa classe **parent**, et peut en
ajouter ou en redéfinir. Mot-clé : **`extends`**.

```php
<?php
declare(strict_types=1);

class Animal
{
    public function __construct(protected string $nom) {}

    public function dormir(): string
    {
        return "{$this->nom} dort 😴";
    }
}

class Chien extends Animal
{
    public function aboyer(): string
    {
        return "{$this->nom} fait Wouf !";
    }
}

$rex = new Chien("Rex");
echo $rex->dormir();   // hérité d'Animal : "Rex dort 😴"
echo $rex->aboyer();   // propre à Chien : "Rex fait Wouf !"
```

> 💡 `protected` (et non `private`) sur `$nom` permet à la sous-classe `Chien` d'y accéder.

---

## ♻️ Redéfinir une méthode (override) et `parent::`

Une sous-classe peut **remplacer** une méthode du parent, et éventuellement **réutiliser** la
version parente avec `parent::` :

```php
<?php
class Chat extends Animal
{
    public function dormir(): string
    {
        return parent::dormir() . " (16h par jour !)";   // on complète le comportement parent
    }
}

echo (new Chat("Félix"))->dormir();   // "Félix dort 😴 (16h par jour !)"
```

---

## 🧩 Les classes abstraites : un contrat + du commun

Une classe **abstraite** ne peut **pas** être instanciée directement. Elle sert de **base** :
elle fournit du **code commun** et **impose** des méthodes que les enfants **doivent**
implémenter (les méthodes `abstract`).

```php
<?php
abstract class Employe
{
    public function __construct(protected string $nom) {}

    // Chaque type d'employé DOIT définir son salaire → méthode abstraite (sans corps)
    abstract public function salaire(): float;

    // Comportement COMMUN à tous les employés
    public function presentation(): string
    {
        return "{$this->nom} gagne {$this->salaire()} € / mois.";
    }
}

class Developpeur extends Employe
{
    public function salaire(): float { return 3500; }
}

class Manager extends Employe
{
    public function salaire(): float { return 4500; }
}

echo (new Developpeur("Marie"))->presentation();   // "Marie gagne 3500 € / mois."
// new Employe("X");   // ❌ ERREUR : on ne peut pas instancier une classe abstraite
```

> 🧠 `presentation()` appelle `$this->salaire()` **sans savoir** quelle sous-classe c'est :
> c'est du **polymorphisme** (leçon suivante). Le code commun vit **à un seul endroit** (DRY).

---

## ⚠️ Attention : l'héritage est un outil **rigide**

L'héritage crée un lien **fort** (« est un »). Il est utile pour une **vraie spécialisation
stable**, mais il devient vite un piège :

- PHP n'a **pas** d'héritage multiple : une classe ne peut étendre qu'**une** seule autre.
- Un cas hybride (« un Lead Dev qui code **et** manage ») ne rentre pas dans un arbre simple.
- Modifier une classe parent peut casser **toutes** ses sous-classes.

> 📏 **Règle de décision** : demande-toi « **est un** » (héritage) ou « **a un / peut faire** »
> (composition) ? Si c'est « a un » ou « peut faire » → préfère la **[composition](../Principes-Genie-Logiciel/09-composition-vs-heritage.md)**
> (leçons 3.4 et 3.5). L'héritage se réserve aux hiérarchies **vraiment** stables.

---

## 🔎 À toi de chercher

> 1. Le mot-clé **`final`** : empêcher qu'une classe soit étendue ou qu'une méthode soit
>    redéfinie. Quand est-ce utile ?
> 2. Cherche le piège **`Carre extends Rectangle`** : pourquoi c'est un mauvais héritage ?
>    (Lien avec le **L** de [SOLID](../Principes-Genie-Logiciel/11-SOLID.md) — substitution de Liskov.)
> 3. Différence entre une **classe abstraite** et une **interface** (leçon suivante).

---

## 🎓 Ce qu'il faut retenir

- **`extends`** : une sous-classe hérite et peut **redéfinir** (avec `parent::` pour réutiliser).
- Une **classe abstraite** ne s'instancie pas : elle fournit du **commun** et **impose** des
  méthodes `abstract`.
- L'héritage = lien **rigide** (« est un ») : réserve-le aux spécialisations **stables**, sinon
  préfère la **composition**.

👉 Leçon suivante : [Interfaces et polymorphisme](./04-interfaces-polymorphisme.md)
