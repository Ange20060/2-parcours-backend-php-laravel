# Leçon 3.6 — Enums et objets immuables

> 🎯 **Objectif** : remplacer les « chaînes magiques » par des **enums** (types énumérés), et
> concevoir des **objets immuables** — deux outils qui rendent le code plus sûr et plus explicite.

---

## 🏷️ Le problème des « chaînes magiques »

Représenter un statut par une simple chaîne est fragile : rien n'empêche une **faute de frappe**
ou une **valeur invalide**.

```php
<?php
$commande->statut = "payee";
$commande->statut = "payée";     // accent → une AUTRE valeur, bug silencieux
$commande->statut = "nimportequoi"; // aucune protection
```

---

## ✅ Les enums (PHP 8.1) : un ensemble de valeurs autorisées

Une **enum** définit un type dont les valeurs sont **limitées et connues**. Impossible d'avoir
une valeur hors de la liste.

```php
<?php
declare(strict_types=1);

enum StatutCommande: string
{
    case EnAttente = 'en_attente';
    case Payee     = 'payee';
    case Expediee  = 'expediee';
    case Annulee   = 'annulee';
}

$statut = StatutCommande::Payee;
echo $statut->value;              // 'payee' (la valeur associée)
echo $statut->name;               // 'Payee' (le nom du cas)
```
> `enum StatutCommande: string` = enum **adossée** à des chaînes (*backed enum*). On peut aussi
> l'adosser à des `int`, ou faire une enum « pure » sans valeur.

### Convertir depuis/vers une valeur
```php
<?php
$s = StatutCommande::from('payee');       // -> StatutCommande::Payee
$s = StatutCommande::tryFrom('inconnu');  // -> null si invalide (au lieu de planter)
$tous = StatutCommande::cases();          // [EnAttente, Payee, Expediee, Annulee]
```
> 💡 `StatutCommande::cases()` te donne **toutes** les valeurs → parfait pour un menu, une
> validation, une liste déroulante. L'enum est la **[source unique de vérité](../Principes-Genie-Logiciel/05-SSOT.md)**
> des statuts.

---

## 🧠 Une enum peut avoir des méthodes

```php
<?php
enum StatutCommande: string
{
    case EnAttente = 'en_attente';
    case Payee     = 'payee';
    case Expediee  = 'expediee';
    case Annulee   = 'annulee';

    public function libelle(): string
    {
        return match ($this) {
            StatutCommande::EnAttente => "En attente de paiement",
            StatutCommande::Payee     => "Payée",
            StatutCommande::Expediee  => "Expédiée",
            StatutCommande::Annulee   => "Annulée",
        };
    }

    public function estFinal(): bool
    {
        return in_array($this, [self::Expediee, self::Annulee]);
    }
}

echo StatutCommande::Payee->libelle();     // "Payée"
```
Le code métier devient **lisible et sûr** : `if ($statut === StatutCommande::Payee)` au lieu de
`if ($statut === 'payee')`. Laravel intègre nativement les enums dans Eloquent (Niveau 8).

---

## 🧊 Les objets immuables

Un objet **immuable** ne change **jamais** après sa création : au lieu de modifier son état, on
**crée un nouvel objet**. Résultat : pas d'effet de bord surprise (souviens-toi de
`DateTimeImmutable`, leçon 2.6).

```php
<?php
final class Argent
{
    public function __construct(
        public readonly int $centimes,     // readonly : fixé une fois, jamais modifié
        public readonly string $devise = 'EUR',
    ) {}

    public function plus(Argent $autre): Argent
    {
        if ($autre->devise !== $this->devise) {
            throw new InvalidArgumentException("Devises différentes.");
        }
        return new Argent($this->centimes + $autre->centimes, $this->devise);  // NOUVEL objet
    }

    public function format(): string
    {
        return number_format($this->centimes / 100, 2) . ' ' . $this->devise;
    }
}

$a = new Argent(1000);          // 10,00 EUR
$b = $a->plus(new Argent(250)); // NOUVEL objet : 12,50 EUR
echo $a->format();              // 10,00 EUR  ($a n'a PAS changé)
echo $b->format();              // 12,50 EUR
```
- **`readonly`** (PHP 8.1) : la propriété ne peut être fixée qu'**une fois** (dans le constructeur).
- Les méthodes qui « modifient » **retournent un nouvel objet** au lieu de muter l'actuel.

> 🧠 **Pourquoi c'est plus sûr ?** Un objet immuable ne peut pas être corrompu à distance : une
> fois valide, il le reste. C'est **[explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)**
> et sans effet de bord. Idéal pour les « objets-valeur » (montant, date, coordonnées…).

---

## 🔎 À toi de chercher

> 1. Différence entre une enum **backed** (`: string`) et une enum **pure** (sans valeur).
> 2. Une enum peut **implémenter une interface**. Dans quel cas est-ce utile ?
> 3. Cherche le patron « **Value Object** » (objet-valeur immuable) : pourquoi représenter un
>    montant par un objet `Argent` plutôt qu'un simple `float` ?

---

## 🎓 Ce qu'il faut retenir

- Une **enum** limite un type à un **ensemble de valeurs connues** → fini les chaînes magiques ;
  `->value`, `->name`, `::cases()`, `::tryFrom()`, et des **méthodes** possibles.
- L'enum est la **source unique de vérité** d'un ensemble de valeurs (statuts, rôles…).
- Un **objet immuable** (`readonly` + méthodes qui **retournent un nouvel objet**) évite les
  effets de bord — plus sûr, plus explicite.

👉 Leçon suivante : [De la POO vers SOLID](./07-vers-solid.md)
