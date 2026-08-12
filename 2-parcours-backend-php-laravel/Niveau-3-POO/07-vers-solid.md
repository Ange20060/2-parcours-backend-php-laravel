# Leçon 3.7 — De la POO vers SOLID (composition & injection)

> 🎯 **Objectif** : assembler ce que tu viens d'apprendre (classes, interfaces, composition) en
> une pratique clé — l'**injection de dépendances** — et ouvrir la porte des **principes SOLID**,
> qui gouvernent tout code orienté objet professionnel (et tout Laravel).

---

## 🧩 Composition : construire en assemblant des objets

Plutôt que d'empiler l'héritage, on **compose** : un objet **contient** d'autres objets et leur
**délègue** le travail (relation « **a un** »).

```php
<?php
declare(strict_types=1);

class Facture
{
    // Une facture "a un" calculateur de taxes — elle ne "l'est" pas
    public function __construct(private CalculateurTaxes $taxes) {}

    public function total(float $ht): float
    {
        return $ht + $this->taxes->calculer($ht);
    }
}
```
On peut **échanger** le calculateur (français, luxembourgeois, hors taxes…) sans toucher à
`Facture`. Impossible avec de l'héritage figé. → **[Composition > Héritage](../Principes-Genie-Logiciel/09-composition-vs-heritage.md)**.

---

## 💉 L'injection de dépendances (le réflexe pro)

Une classe ne doit **pas** créer elle-même ses dépendances (couplage fort), mais les **recevoir**
de l'extérieur (par le constructeur). Compare :

```php
<?php
// ❌ Couplage fort : le service fabrique lui-même sa dépendance concrète
class CommandeService
{
    public function traiter(): void
    {
        $logger = new FichierLogger('/var/log/app.log'); // soudé à cette implémentation
        $logger->log("Commande traitée");
    }
}

// ✅ Injection : la dépendance arrive de l'extérieur, via une ABSTRACTION
interface Logger { public function log(string $message): void; }

class CommandeService
{
    public function __construct(private Logger $logger) {}   // injecté

    public function traiter(): void
    {
        $this->logger->log("Commande traitée");
    }
}
```
On **branche** l'implémentation au moment de la construction :
```php
<?php
class ConsoleLogger implements Logger {
    public function log(string $m): void { echo "[LOG] $m" . PHP_EOL; }
}
class LoggerNul implements Logger {
    public function log(string $m): void { /* ne fait rien */ }
}

$service = new CommandeService(new ConsoleLogger());  // en prod
$service = new CommandeService(new LoggerNul());       // en test (silencieux)
```
`CommandeService` **ignore** quelle implémentation il reçoit → **[faible couplage](../Principes-Genie-Logiciel/06-cohesion-couplage.md)**,
code **testable** (on injecte une fausse version), et **remplaçable**.

> 💡 C'est **exactement** ce que fait le **conteneur de services de Laravel** : tu déclares avoir
> besoin d'un `Logger`, il te l'injecte automatiquement. Tu le vivras au Niveau 7 — et là, tu
> comprendras *pourquoi* c'est génial.

---

## 🏛️ SOLID : les 5 principes de la conception objet

Tu as maintenant **tous** les outils pour aborder **SOLID**, les 5 règles qui séparent un code
fragile d'un code maintenable :

| Lettre | Principe | En une phrase |
|:--:|---|---|
| **S** | Single Responsibility | Une classe = **une seule raison de changer** |
| **O** | Open/Closed | Ouverte à l'**extension**, fermée à la **modification** |
| **L** | Liskov Substitution | Une sous-classe doit pouvoir **remplacer** sa mère sans surprise |
| **I** | Interface Segregation | Plusieurs **petites** interfaces > une grosse |
| **D** | Dependency Inversion | Dépendre d'**abstractions**, pas d'implémentations |

Tu en as déjà rencontré les briques :
- **S** = la [séparation des responsabilités](../Principes-Genie-Logiciel/04-SoC.md) (une classe, un rôle).
- **O** = le [polymorphisme](./04-interfaces-polymorphisme.md) par interface (ajouter sans modifier).
- **D** = l'**injection de dépendances** ci-dessus (dépendre d'une interface `Logger`).

> 📚 **Étudie-les en détail maintenant** dans le domaine transversal :
> 👉 **[Principes du Génie Logiciel → SOLID](../Principes-Genie-Logiciel/11-SOLID.md)** (et les 10
> autres principes). C'est le moment idéal : tu as la POO pour les comprendre vraiment.

---

## ⚠️ Le bon dosage (KISS + YAGNI)

Ne crée pas une interface et une abstraction pour **chaque** classe « au cas où » : ce serait
violer **[KISS](../Principes-Genie-Logiciel/02-KISS.md)** et **[YAGNI](../Principes-Genie-Logiciel/03-YAGNI.md)**.
Introduis une abstraction **quand un vrai besoin apparaît** : deux implémentations réelles, ou un
point à rendre testable. La maturité, c'est savoir **quand** appliquer un principe — pas les
appliquer tous, tout le temps.

---

## 🔎 À toi de chercher

> 1. Cherche « **injection de dépendances** vs **service locator** » : pourquoi l'injection par
>    constructeur est préférée.
> 2. Qu'est-ce qu'un **conteneur d'inversion de contrôle (IoC container)** ? (C'est le cœur de Laravel.)
> 3. Reprends l'exercice « mini-domaine paiement » du niveau : identifie **où** tu appliques S, O et D.

---

## 🎓 Ce qu'il faut retenir

- **Compose** (« a un ») plutôt que d'hériter (« est un ») ; **délègue** à des objets internes.
- **Injecte les dépendances** par le constructeur, via des **interfaces** → faible couplage,
  testabilité, remplaçabilité (fondation du conteneur Laravel).
- Tu as les bases pour **SOLID** : va l'étudier dans le domaine
  **[Principes du Génie Logiciel](../Principes-Genie-Logiciel/11-SOLID.md)**.
- **Dose** : abstrais quand un **vrai** besoin l'exige, pas « au cas où » (KISS/YAGNI).

---

🎉 **Tu as fini le Niveau 3 !** Tu modélises avec des classes cohésives, tu programmes contre des
**interfaces**, tu **composes** et tu **injectes** — et tu tiens les clés de **SOLID**. Fais les
[exercices](./exercices.md), (re)lis le domaine **[Principes](../Principes-Genie-Logiciel/)**, puis
attaque le **[Niveau 5 : PHP & le Web](../Niveau-5-PHP-Web/)**.
