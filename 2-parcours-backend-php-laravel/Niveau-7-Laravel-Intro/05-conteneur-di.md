# Leçon 7.5 — Le conteneur de services & l'injection de dépendances

> 🎯 **Objectif** : comprendre le **cœur magique** de Laravel — le **conteneur de services** qui
> **injecte automatiquement** les dépendances. C'est l'aboutissement de ta POO (le **D** de SOLID)
> mis en pratique par le framework.

---

## 🔁 Rappel : l'injection de dépendances

Au Niveau 3, tu as appris : une classe ne **crée** pas ses dépendances, elle les **reçoit** par
le constructeur (via une interface) → faible couplage, testabilité.

```php
<?php
class CommandeService
{
    public function __construct(private Logger $logger) {}   // reçue, pas créée
}
```
Le problème « à la main » : quelqu'un doit **fabriquer** le `Logger` et le passer. Sur une grosse
app avec des dizaines de dépendances imbriquées, ça devient un casse-tête. **Laravel le fait pour toi.**

---

## 📦 Le conteneur de services

Le **conteneur** (aussi appelé *IoC container*) est un « assembleur automatique » : quand une
classe déclare avoir besoin d'un type, Laravel **construit** l'objet et l'**injecte** tout seul.

```php
<?php
namespace App\Http\Controllers;

use App\Services\SalutationService;

class PageController extends Controller
{
    // Il suffit de le DEMANDER en paramètre : Laravel l'injecte automatiquement
    public function accueil(SalutationService $salutation)
    {
        return $salutation->saluer('Marie');
    }
}
```
Tu n'écris **jamais** `new SalutationService()` : le conteneur le crée, résout **ses** propres
dépendances (récursivement), et te le donne prêt à l'emploi.

> 🧠 C'est l'**inversion des dépendances** (le **D** de [SOLID](../Principes-Genie-Logiciel/11-SOLID.md))
> automatisée : ton code dépend d'un **type**, le framework se charge de fournir l'**implémentation**.

---

## 🔌 Lier une interface à une implémentation

La vraie puissance : faire dépendre ton code d'une **interface**, et dire au conteneur **quelle
implémentation** utiliser. On configure ça dans un **Service Provider**
(`app/Providers/AppServiceProvider.php`) :

```php
<?php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    // "Quand quelqu'un demande NotifieurInterface, donne-lui EmailNotifieur"
    $this->app->bind(
        \App\Contracts\NotifieurInterface::class,
        \App\Services\EmailNotifieur::class,
    );
}
```
Désormais, n'importe quel contrôleur/service qui demande `NotifieurInterface` reçoit un
`EmailNotifieur`. Pour passer au SMS, tu changes **une** ligne — sans toucher au reste (**Open/Closed**).
En test, tu peux lier une **fausse** implémentation.

```php
<?php
class InscriptionController extends Controller
{
    public function __construct(private NotifieurInterface $notifieur) {}  // injecté
}
```

---

## 🎁 Les façades (le raccourci que tu croiseras partout)

Laravel expose beaucoup de services via des **façades** : une syntaxe statique courte au-dessus
du conteneur.

```php
<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

Route::get(...);          // façade Route
DB::table('users')->get();
Auth::user();
```
> 💡 `Route`, `DB`, `Auth`, `Cache`… sont des façades. Sous le capot, elles utilisent le conteneur.
> C'est pratique et lisible ; en interne, ça reste de l'injection de dépendances.

---

## 🧠 Pourquoi c'est génial (et pourquoi tu le comprends déjà)

Tout ce que tu as construit **à la main** aux niveaux précédents — instancier, câbler des objets,
choisir une implémentation — Laravel l'**automatise** grâce à ce conteneur. Et comme tu as fait
l'injection de dépendances toi-même (Niveau 3), tu **comprends** ce qui se passe : pas de magie,
juste de la bonne conception, industrialisée.

> 🎯 C'est **la** raison pour laquelle on a insisté sur la POO et SOLID **avant** Laravel : sans
> ça, le conteneur ressemble à de la magie incompréhensible. Avec, c'est limpide.

---

## 🔎 À toi de chercher

> 1. Différence entre `bind` (nouvelle instance à chaque fois) et `singleton` (une seule instance
>    partagée) dans le conteneur.
> 2. Cherche « laravel service provider » : à quoi sert la méthode `register` vs `boot` ?
> 3. Comment résoudre manuellement un service : `app(MonService::class)` ou `resolve(...)`.

---

## 🎓 Ce qu'il faut retenir

- Le **conteneur de services** **construit et injecte** automatiquement les dépendances (tu ne
  fais jamais `new` sur tes services).
- On **lie une interface à une implémentation** dans un **Service Provider** → flexibilité +
  testabilité (le **D** de SOLID, automatisé).
- Les **façades** (`Route`, `DB`, `Auth`…) sont des raccourcis lisibles au-dessus du conteneur.
- Tu comprends cette « magie » **parce que** tu as fait l'injection de dépendances à la main.

---

🎉 **Tu as fini le Niveau 7 !** Tu connais Laravel : **MVC**, **routing**, **contrôleurs**,
**Blade**, et le **conteneur** qui automatise l'injection de dépendances. Fais les
[exercices](./exercices.md), puis attaque **[Eloquent & les migrations](../Niveau-8-Laravel-Eloquent/)** —
pour manipuler la base sans écrire une ligne de SQL. 🚀
