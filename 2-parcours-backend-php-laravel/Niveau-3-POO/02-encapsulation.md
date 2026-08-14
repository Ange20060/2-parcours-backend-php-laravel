# Leçon 3.2 — Encapsulation et visibilité

---

# 🔒 Le problème : un état modifiable n'importe comment

Si tout est `public`, n'importe qui peut mettre l'objet dans un état **incohérent** :

```php
<?php
class CompteBancaire
{
    public float $solde = 0;
}

$compte = new CompteBancaire();
$compte->solde = -99999;   // 😱 rien n'empêche un solde absurde
```

---

## 🛡️ La solution : cacher, exposer par des méthodes

**Encapsuler** = rendre les données **`private`** et n'autoriser leur modification que par des
**méthodes** qui **font respecter les règles**.

```php
<?php
declare(strict_types=1);

class CompteBancaire
{
    public function __construct(private float $solde = 0) {}

    public function deposer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le dépôt doit être positif.");
        }
        $this->solde += $montant;
    }

    public function retirer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le retrait doit être positif.");
        }
        if ($montant > $this->solde) {
            throw new RuntimeException("Solde insuffisant.");
        }
        $this->solde -= $montant;
    }

    public function solde(): float          // lecture seule
    {
        return $this->solde;
    }
}
```

```php
<?php
$compte = new CompteBancaire(100);
$compte->deposer(50);
$compte->retirer(30);
echo $compte->solde();     // 120
$compte->solde = -1000;    // ❌ ERREUR : propriété private, inaccessible de l'extérieur
```

La règle « le solde ne peut pas devenir négatif ou incohérent » est **garantie** : peu importe
qui utilise la classe, l'état reste **valide**. C'est un **invariant** protégé.

---

## 👁️ Les trois niveaux de visibilité

| Mot-clé                | Accessible depuis…                                          |
| ----------------------- | ------------------------------------------------------------ |
| **`public`**    | partout (l'extérieur, les sous-classes, la classe)          |
| **`protected`** | la classe**et** ses **sous-classes** (héritage) |
| **`private`**   | **uniquement** la classe elle-même                    |

```php
<?php
class Exemple
{
    public string $a = "visible partout";
    protected string $b = "visible ici + sous-classes";
    private string $c = "visible ici seulement";
}
```

> 🧠 **Règle de conception** : commence par **`private`** par défaut, et n'ouvre (`protected`,
> `public`) que si un besoin réel l'exige. Moins de surface exposée = moins de risques =
> **[faible couplage](../Principes-Genie-Logiciel/06-cohesion-couplage.md)**.

---

## 🚪 Getters et setters (avec parcimonie)

Pour **lire** ou **modifier de façon contrôlée** une propriété privée, on écrit des méthodes :

```php
<?php
class Utilisateur
{
    public function __construct(private string $email) {}

    public function email(): string          // getter (lecture)
    {
        return $this->email;
    }

    public function changerEmail(string $email): void   // setter contrôlé
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide.");
        }
        $this->email = $email;
    }
}
```

> ⚠️ **Ne crée pas mécaniquement un getter/setter public pour CHAQUE propriété** : ce serait
> revenir à du « tout public » déguisé. Expose seulement ce qui a un **sens métier**. Un bon
> objet offre des **actions** (`retirer()`, `changerEmail()`), pas juste des accès bruts.

---

## 🔎 À toi de chercher

> 1. Qu'est-ce qu'un **objet immuable** (aucune modification après création) ? Pourquoi c'est
>    souvent plus sûr ? (On y revient à la leçon 3.6.)
> 2. Les propriétés **`readonly`** (PHP 8.1) : une propriété qu'on ne peut fixer qu'**une fois**
>    (dans le constructeur). Teste-la.
> 3. Pourquoi dit-on qu'exposer des **comportements** plutôt que des **données** réduit le
>    couplage ? (Principe « Tell, Don't Ask ».)

---

## 🎓 Ce qu'il faut retenir

- **Encapsuler** = données **`private`**, modifications via des **méthodes** qui valident.
- Visibilité : **`private`** (classe seule), **`protected`** (+ sous-classes), **`public`** (partout).
- **Par défaut `private`** ; ouvre seulement au besoin.
- Un objet protège ses **invariants** : son état reste **toujours valide**, quel que soit l'appelant.
- Expose des **actions** utiles, pas un getter/setter public pour tout.

👉 Leçon suivante : [Héritage et classes abstraites](./03-heritage-abstraites.md)
