# Leçon 3.5 — Les traits

> 🎯 **Objectif** : réutiliser du code **horizontalement** entre des classes **sans lien
> d'héritage**, grâce aux **traits** — et comprendre quand ils aident (et quand ils nuisent).

---

## 🧩 Le problème que ça résout

Imagine que plusieurs classes **non liées** (un `Article`, un `Commentaire`, un `Produit`)
aient besoin du **même** comportement : par exemple, gérer un horodatage. Les mettre dans une
classe parent commune n'a pas de sens (elles n'ont rien à voir), et PHP n'a pas d'héritage
multiple. Le **trait** est la solution : un **bloc de code réutilisable** qu'on « injecte » dans
n'importe quelle classe.

---

## ✍️ Définir et utiliser un trait

```php
<?php
declare(strict_types=1);

trait Horodatable
{
    public function horodatage(): string
    {
        return (new DateTimeImmutable())->format("Y-m-d H:i:s");
    }
}

class Article
{
    use Horodatable;    // on "injecte" le trait
}

class Commentaire
{
    use Horodatable;    // le même code, réutilisé, sans héritage
}

echo (new Article())->horodatage();
echo (new Commentaire())->horodatage();
```
`use NomDuTrait;` **à l'intérieur** de la classe copie les méthodes du trait dans la classe.
Aucune relation « est un » — juste du code partagé.

> ⚠️ Ne confonds pas les deux `use` : `use App\Models\Article;` (en haut du fichier) **importe
> un namespace** ; `use Horodatable;` (dans la classe) **injecte un trait**. Même mot, rôles différents.

---

## 🧰 Un trait peut avoir des propriétés et plusieurs méthodes

```php
<?php
trait Loggable
{
    private array $journal = [];

    public function log(string $message): void
    {
        $this->journal[] = (new DateTimeImmutable())->format("H:i:s") . " — $message";
    }

    public function historique(): array
    {
        return $this->journal;
    }
}

class Commande
{
    use Loggable;
}

$c = new Commande();
$c->log("Commande créée");
$c->log("Paiement reçu");
print_r($c->historique());
```

## Combiner plusieurs traits

```php
<?php
class Facture
{
    use Horodatable, Loggable;   // plusieurs traits d'un coup
}
```

---

## ⚖️ Traits vs interfaces vs héritage

| Outil | Apporte… | Relation |
|---|---|---|
| **Interface** | un **contrat** (signatures, pas de code) | « peut faire » |
| **Trait** | du **code réutilisable** (méthodes concrètes) | « partage un comportement » |
| **Héritage** | code **+** identité | « est un » |

> 🧠 **Combo idiomatique** : une **interface** définit le contrat, un **trait** fournit une
> implémentation par défaut réutilisable. Une classe `implements Interface` et `use Trait`.

---

## ⚠️ Les traits, à utiliser avec discernement

- Un trait qui manipule `$this->quelqueChose` **suppose** que la classe hôte possède cette
  propriété → couplage caché. Documente ces attentes.
- Trop de traits qui s'empilent recréent la complexité qu'on fuyait avec l'héritage multiple.
- **Conflits** : si deux traits ont une méthode de même nom, PHP demande de **résoudre** le
  conflit (`insteadof`, `as`). C'est un signal que tu en fais peut-être trop.

> 📏 Règle : un trait pour un **comportement transversal bien défini** (horodatage, log, …).
> Pour de la vraie logique métier, préfère la **[composition](../Principes-Genie-Logiciel/09-composition-vs-heritage.md)**
> (injecter un objet dédié).

---

## 🔎 À toi de chercher

> 1. Résolution de conflits entre traits : les mots-clés **`insteadof`** et **`as`**.
> 2. En Laravel, cherche le trait **`HasFactory`** (sur les modèles Eloquent) : un exemple concret
>    de trait très utilisé.
> 3. Un trait peut-il déclarer une méthode **`abstract`** pour forcer la classe hôte à
>    l'implémenter ? Teste.

---

## 🎓 Ce qu'il faut retenir

- Un **trait** = du **code réutilisable** injecté avec `use TraitName;` **dans** une classe.
- Il permet le partage **horizontal** entre classes **sans héritage** (et PHP n'a pas
  d'héritage multiple).
- Combo idiomatique : **interface** (contrat) + **trait** (implémentation par défaut).
- À réserver aux **comportements transversaux** ; pour la logique métier, préfère la composition.

👉 Leçon suivante : [Enums et objets immuables](./06-enums-immutabilite.md)
