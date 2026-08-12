# ✅ Niveau 3 — Corrigés (POO)

> ⚠️ Essaie d'abord. Code supposé précédé de `declare(strict_types=1);`.

---

## Exercice 1 — Première classe (avec promotion de propriétés)
```php
<?php
class CompteBancaire
{
    public function __construct(private float $solde = 0.0) {}   // promotion de propriété

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

    public function solde(): float
    {
        return $this->solde;
    }
}

$compte = new CompteBancaire(100);
$compte->deposer(50);
$compte->retirer(30);
echo $compte->solde() . PHP_EOL;   // 120
```

## Exercice 2 — Encapsulation
`$compte->solde = -1000;` provoque une **erreur** : `solde` est `private`. On ne peut modifier
le solde **que** via `deposer`/`retirer`, qui **garantissent** les règles (montant positif,
pas de découvert). C'est **l'invariant** protégé : l'état interne reste toujours **valide**,
peu importe qui utilise la classe.

## Exercice 3 — Interface & polymorphisme
```php
<?php
interface Forme
{
    public function aire(): float;
}

class Cercle implements Forme
{
    public function __construct(private float $rayon) {}
    public function aire(): float { return pi() * $this->rayon ** 2; }
}

class Rectangle implements Forme
{
    public function __construct(private float $largeur, private float $hauteur) {}
    public function aire(): float { return $this->largeur * $this->hauteur; }
}

class Triangle implements Forme   // ajouté SANS toucher afficherAire (Open/Closed)
{
    public function __construct(private float $base, private float $hauteur) {}
    public function aire(): float { return $this->base * $this->hauteur / 2; }
}

function afficherAire(Forme $forme): void
{
    echo "Aire : " . round($forme->aire(), 2) . PHP_EOL;
}

afficherAire(new Cercle(3));
afficherAire(new Rectangle(4, 5));
afficherAire(new Triangle(6, 2));
```

## Exercice 4 — Classe abstraite
```php
<?php
abstract class Employe
{
    public function __construct(protected string $nom) {}

    abstract public function salaire(): float;   // chaque sous-classe DOIT l'implémenter

    public function presentation(): string       // comportement commun
    {
        return "{$this->nom} gagne " . $this->salaire() . " € / mois.";
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

echo (new Developpeur("Marie"))->presentation() . PHP_EOL;
```

## Exercice 5 — Traits
```php
<?php
trait Horodatable
{
    public function creerHorodatage(): string
    {
        return (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    }
}

class Article { use Horodatable; }
class Commentaire { use Horodatable; }   // aucune relation d'héritage, code réutilisé

echo (new Article())->creerHorodatage() . PHP_EOL;
```

## Exercice 6 — Enum métier
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
}

echo StatutCommande::Payee->libelle() . PHP_EOL;   // Payée
```
> L'enum est la **source unique de vérité** des statuts (SSOT) : impossible d'avoir une valeur invalide.

## Exercice 7 — Composition / injection
```php
<?php
interface Logger { public function log(string $message): void; }

class ConsoleLogger implements Logger
{
    public function log(string $message): void { echo "[LOG] $message" . PHP_EOL; }
}

class LoggerNul implements Logger
{
    public function log(string $message): void { /* ne fait rien */ }
}

class CommandeService
{
    public function __construct(private Logger $logger) {}   // dépend de l'ABSTRACTION

    public function traiter(): void
    {
        $this->logger->log("Commande traitée.");
    }
}

(new CommandeService(new ConsoleLogger()))->traiter();   // affiche
(new CommandeService(new LoggerNul()))->traiter();        // silencieux
```
> `CommandeService` ignore quelle implémentation il reçoit : **faible couplage** + inversion de
> dépendances (le **D** de SOLID). C'est **exactement** le fonctionnement du conteneur de Laravel.

## Exercice 8 — Mini-domaine paiement
```php
<?php
enum Devise: string { case EUR = 'EUR'; case USD = 'USD'; }

interface MoyenPaiement
{
    public function payer(float $montant): string;
}

class CarteBancaire implements MoyenPaiement
{
    public function payer(float $montant): string
    {
        return "Payé $montant € par carte bancaire.";
    }
}

class Paypal implements MoyenPaiement
{
    public function payer(float $montant): string
    {
        return "Payé $montant € via PayPal.";
    }
}

class Caisse
{
    public function __construct(private MoyenPaiement $moyen) {}   // composition

    public function encaisser(float $montant): string
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Montant invalide.");   // Fail Fast
        }
        return $this->moyen->payer($montant);
    }
}

echo (new Caisse(new CarteBancaire()))->encaisser(49.99) . PHP_EOL;
echo (new Caisse(new Paypal()))->encaisser(120.0) . PHP_EOL;
```
**Principes appliqués** : interface + polymorphisme (Open/Closed), composition > héritage,
enum = SSOT, Fail Fast sur le montant, faible couplage (`Caisse` ignore le moyen concret).

---

## 🎉 Bilan du Niveau 3
Tu modélises avec des classes cohésives, tu programmes contre des **interfaces**, et tu
**composes** plutôt que d'empiler l'héritage. Tu es prêt·e à (re)lire tout le domaine
**[Principes du Génie Logiciel](../Principes-Genie-Logiciel/)** — surtout **SOLID**.
👉 [Niveau 5 : PHP & le Web](../Niveau-5-PHP-Web/)
