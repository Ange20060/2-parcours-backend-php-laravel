# 11 — SOLID (les 5 principes de conception orientée objet)

> 🎯 **Le principe** : cinq règles de conception pour bâtir des systèmes orientés objet
> **maintenables** et **extensibles**.
>
> 💥 **Ce que ça tue** : les conceptions **fragiles**, fortement couplées, où le moindre
> changement est risqué.

> ⚠️ **Prérequis** : ces principes supposent que tu maîtrises la **POO** (classes, interfaces,
> héritage). Reviens sur cette fiche **après le [Niveau 3](../Niveau-3-POO/)** si besoin.

**SOLID** est un acronyme de 5 principes :

| Lettre | Principe | Idée en une phrase |
|:--:|---|---|
| **S** | Single Responsibility | Une classe = **une seule raison de changer** |
| **O** | Open/Closed | **Ouverte** à l'extension, **fermée** à la modification |
| **L** | Liskov Substitution | Une sous-classe doit pouvoir **remplacer** sa classe mère |
| **I** | Interface Segregation | Plusieurs **petites** interfaces valent mieux qu'une grosse |
| **D** | Dependency Inversion | Dépendre d'**abstractions**, pas d'implémentations |

---

## S — Single Responsibility Principle (Responsabilité unique)

> Une classe ne doit avoir qu'**une seule raison de changer**.

❌ `Rapport` fait deux choses sans rapport : produire le contenu **et** l'exporter en PDF.
```php
<?php
class Rapport
{
    public function contenu(): string { /* logique métier */ return "..."; }
    public function exporterPDF(): void { /* logique d'impression PDF */ }
}
```
Deux raisons de changer (la logique du rapport OU le format d'export) → deux responsabilités.

✅ On sépare :
```php
<?php
class Rapport { public function contenu(): string { return "..."; } }
class ExportateurPDF { public function exporter(Rapport $r): void { /* ... */ } }
```
> C'est le **[SoC](./04-SoC.md)** appliqué à la classe.

---

## O — Open/Closed Principle (Ouvert/Fermé)

> Le code doit être **ouvert à l'extension** mais **fermé à la modification** : ajouter un
> comportement sans **modifier** l'existant.

❌ Chaque nouveau moyen de paiement force à **modifier** la fonction (et à risquer de casser le reste) :
```php
<?php
function payer(string $type, float $montant): void
{
    if ($type === 'carte')       { /* ... */ }
    elseif ($type === 'paypal')  { /* ... */ }
    elseif ($type === 'virement'){ /* ... */ }
    // ... on modifie ce bloc à CHAQUE nouveau moyen. Fermé à l'extension. ❌
}
```

✅ On étend en **ajoutant** une classe, sans toucher au code existant :
```php
<?php
interface MoyenPaiement
{
    public function payer(float $montant): void;
}

class PaiementCarte    implements MoyenPaiement { public function payer(float $m): void { /* ... */ } }
class PaiementPaypal   implements MoyenPaiement { public function payer(float $m): void { /* ... */ } }
// Nouveau moyen ? On CRÉE une classe, on ne modifie rien :
class PaiementVirement implements MoyenPaiement { public function payer(float $m): void { /* ... */ } }

class Caisse
{
    public function encaisser(MoyenPaiement $moyen, float $montant): void
    {
        $moyen->payer($montant);   // marche avec TOUS les moyens, présents et futurs
    }
}
```

---

## L — Liskov Substitution Principle (Substitution de Liskov)

> Si `B` hérite de `A`, on doit pouvoir utiliser un `B` **partout** où un `A` est attendu,
> **sans surprise**.

❌ Violation classique : `Carre extends Rectangle`.
```php
<?php
class Rectangle {
    public function __construct(protected int $l, protected int $h) {}
    public function setHauteur(int $h): void { $this->h = $h; }
    public function aire(): int { return $this->l * $this->h; }
}
class Carre extends Rectangle {
    // Pour rester un carré, changer la hauteur change AUSSI la largeur...
    public function setHauteur(int $h): void { $this->l = $h; $this->h = $h; }
}
// Un code qui attend un Rectangle et fait setHauteur(5) obtient un résultat FAUX avec un Carre.
```
✅ Le carré et le rectangle ne partagent pas le même contrat mutable : ne les lie pas par
héritage. Préfère la **[composition](./09-composition-vs-heritage.md)** ou des objets immuables.

> 🧠 En résumé : une sous-classe ne doit **pas** affaiblir ni contredire les promesses de sa
> classe mère.

---

## I — Interface Segregation Principle (Ségrégation des interfaces)

> Mieux vaut **plusieurs petites interfaces** spécifiques qu'une **grosse** interface fourre-tout.

❌ Une interface trop large **force** à implémenter des méthodes inutiles :
```php
<?php
interface Machine
{
    public function imprimer(): void;
    public function scanner(): void;
    public function faxer(): void;
}
// Une simple imprimante est OBLIGÉE d'implémenter scanner() et faxer() (souvent avec des exceptions) ❌
class ImprimanteBasique implements Machine { /* scanner() ??? faxer() ??? */ }
```

✅ Des interfaces **ciblées**, qu'on combine au besoin :
```php
<?php
interface Imprimante { public function imprimer(): void; }
interface Scanner    { public function scanner(): void; }

class ImprimanteBasique implements Imprimante { public function imprimer(): void { /* ... */ } }
class Multifonction implements Imprimante, Scanner { /* implémente les deux */ }
```

---

## D — Dependency Inversion Principle (Inversion des dépendances)

> Les modules de haut niveau ne doivent pas dépendre de modules de bas niveau : **les deux**
> doivent dépendre d'**abstractions**.

❌ `NotificationService` dépend d'une classe **concrète** `EmailSender` :
```php
<?php
class NotificationService
{
    private EmailSender $sender;
    public function __construct() { $this->sender = new EmailSender(); } // couplage en dur ❌
}
```

✅ Il dépend d'une **abstraction**, injectée de l'extérieur :
```php
<?php
interface Notifieur { public function envoyer(string $message): void; }

class EmailNotifieur implements Notifieur { public function envoyer(string $m): void { /* ... */ } }
class SmsNotifieur   implements Notifieur { public function envoyer(string $m): void { /* ... */ } }

class NotificationService
{
    public function __construct(private Notifieur $notifieur) {}   // dépend de l'abstraction ✅
    public function alerter(string $message): void { $this->notifieur->envoyer($message); }
}
```
> 💡 C'est le fondement de l'**injection de dépendances**, cœur du fonctionnement de Laravel
> (son conteneur de services résout automatiquement ces abstractions — Niveau 7).

---

## 🔗 Comment SOLID relie tout le reste

- **S** = **[SoC](./04-SoC.md)** au niveau classe.
- **O, L, I, D** reposent sur les **interfaces** et la **[composition](./09-composition-vs-heritage.md)**,
  et produisent un **[faible couplage](./06-cohesion-couplage.md)**.

> ⚠️ **Ne sur-applique pas SOLID.** Créer une interface pour **chaque** classe « au cas où »
> viole **[KISS](./02-KISS.md)** et **[YAGNI](./03-YAGNI.md)**. Introduis une abstraction quand
> un **vrai** besoin de flexibilité apparaît (2+ implémentations, ou un point de test).

---

## 🏋️ Mini-exercice

Pour **chacune** des 5 lettres, écris en **une phrase** un exemple de **violation** que tu as
déjà vue (ou imagines), puis comment la corriger. C'est l'exercice qui ancre le mieux SOLID.

> Pistes de correction dans [corriges.md](./corriges.md).
