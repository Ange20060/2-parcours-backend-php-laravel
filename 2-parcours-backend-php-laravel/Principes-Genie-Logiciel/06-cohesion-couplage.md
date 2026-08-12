# 6 — Haute Cohésion & Faible Couplage

> 🎯 **Le principe** : garde ensemble ce qui **va ensemble** (haute **cohésion**), et **réduis
> les dépendances** entre les modules (faible **couplage**).
>
> 💥 **Ce que ça tue** : le code où **modifier un fichier en casse dix autres**.

---

## Deux notions à distinguer

- **Cohésion** *(à l'intérieur d'un module)* : à quel point les éléments d'une classe
  travaillent vers **un même but**. On veut une **haute** cohésion.
- **Couplage** *(entre modules)* : à quel point un module **dépend** des détails d'un autre.
  On veut un **faible** couplage.

> 🧠 Image : une **équipe** (haute cohésion : tout le monde vise le même objectif) qui
> communique avec les autres équipes par des **contrats clairs** (faible couplage), pas en
> fouillant dans le bureau des autres.

---

## Faible couplage — le problème (❌)

`CommandeService` crée lui-même un `MySQLConnexion` concret : il est **soudé** à MySQL.
Changer de base, ou tester sans base, devient un enfer.

```php
<?php
class CommandeService
{
    public function enregistrer(Commande $c): void
    {
        $db = new MySQLConnexion('localhost', 'root', ''); // dépendance en dur ❌
        $db->query("INSERT INTO commandes ...");
    }
}
```

## Faible couplage — la solution (✅) : l'injection de dépendances

On dépend d'une **abstraction** (interface), fournie de l'extérieur.

```php
<?php
interface CommandeRepository
{
    public function enregistrer(Commande $c): void;
}

class CommandeService
{
    // Le service ne sait PAS quelle base est derrière. Faible couplage. ✅
    public function __construct(private CommandeRepository $repo) {}

    public function traiter(Commande $c): void
    {
        $this->repo->enregistrer($c);
    }
}

// On branche l'implémentation concrète de l'extérieur :
$service = new CommandeService(new MySQLCommandeRepository($pdo));
// ... ou en test :
$service = new CommandeService(new CommandeRepositoryEnMemoire());
```

> 💡 C'est **exactement** ce que fait le **conteneur de services** de Laravel : il injecte
> automatiquement les dépendances. Tu le découvriras au Niveau 7.

---

## Haute cohésion — l'idée

Une classe `Utilisateur` qui gère **aussi** l'envoi d'emails, la génération de PDF et les
statistiques a une **faible cohésion** : elle fait trop de choses sans rapport. Découpe-la
(voir **[SoC](./04-SoC.md)**) : `Utilisateur`, `ServiceEmail`, `GenerateurPDF`…

---

## 🔗 Liens avec les autres principes

- C'est la conséquence directe d'un bon **[SoC](./04-SoC.md)**.
- Le faible couplage repose sur le **D** de **[SOLID](./11-SOLID.md)** (dépendre d'abstractions)
  et sur la **[composition](./09-composition-vs-heritage.md)**.

---

## 🏋️ Mini-exercice

Cette classe est trop couplée. Explique **pourquoi**, puis réécris son constructeur pour
recevoir sa dépendance par **injection** au lieu de la créer :

```php
<?php
class RapportService
{
    public function generer(): string
    {
        $logger = new FichierLogger('/var/log/app.log'); // couplage en dur
        $logger->info("Génération du rapport");
        return "... rapport ...";
    }
}
```

> Corrigé dans [corriges.md](./corriges.md).
