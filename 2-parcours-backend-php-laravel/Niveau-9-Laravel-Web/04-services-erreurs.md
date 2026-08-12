# Leçon 9.4 — Services & gestion des erreurs

> 🎯 **Objectif** : sortir la **logique métier** des contrôleurs vers des **services** (SoC +
> testabilité), et gérer les **erreurs** proprement à l'échelle de l'application.

---

## 🧩 Le pattern Service : où va la logique métier ?

Un contrôleur doit rester **fin** (couche HTTP). La **logique métier** — les règles, les calculs,
l'orchestration de plusieurs modèles — va dans une classe **Service**.

```php
<?php
// app/Services/ArticleService.php
namespace App\Services;

use App\Models\Article;
use App\Models\User;

class ArticleService
{
    public function creer(array $donnees, User $auteur): Article
    {
        $article = $auteur->articles()->create($donnees);
        // ... autre logique métier : notifier des abonnés, journaliser, indexer…
        return $article;
    }

    public function publier(Article $article): void
    {
        $article->update(['published' => true, 'published_at' => now()]);
        // ... envoyer une notification, etc.
    }
}
```
Le contrôleur **orchestre** et **délègue** (le service est injecté par le conteneur, Niveau 7) :
```php
<?php
public function store(StoreArticleRequest $request, ArticleService $service)
{
    $article = $service->creer($request->validated(), $request->user());
    return redirect()->route('articles.show', $article);
}
```
> 🎯 Bénéfices : le contrôleur reste **lisible**, la logique est **réutilisable** (depuis une
> commande Artisan, un job de file d'attente…) et **testable** en isolation. C'est le **S** de
> [SOLID](../Principes-Genie-Logiciel/11-SOLID.md) (responsabilité unique) et la
> **[séparation des responsabilités](../Principes-Genie-Logiciel/04-SoC.md)**.

> ⚠️ **Rappel des corrections** : une classe qui fait tout (sauvegarde + email + PDF + stats) est
> une « pieuvre ». Découpe : `ArticleService`, `NotificationService`… une responsabilité chacune.

---

## 🧯 La gestion des erreurs HTTP

Laravel transforme les exceptions en **réponses HTTP** appropriées. Les plus utiles :

```php
<?php
abort(404, "Article introuvable.");     // renvoie une 404
abort(403, "Action non autorisée.");    // renvoie une 403
abort_if($condition, 403);              // 403 si la condition est vraie
abort_unless($autorise, 403);           // 403 sauf si autorisé
```
- **`findOrFail`** / **route model binding** → **404** automatique si l'objet n'existe pas.
- Une **validation** échouée → **422** automatique.
- Une exception non gérée → **500** (avec la trace en dev, masquée en prod).

---

## 🎯 Des exceptions métier explicites

Pour des cas métier, crée des exceptions dédiées (rappel Niveau 2) et laisse Laravel les
transformer en réponse :

```php
<?php
// app/Exceptions/QuotaDepasseException.php
class QuotaDepasseException extends \Exception {}

// dans un service
if ($user->articles()->count() >= 100) {
    throw new QuotaDepasseException("Limite de 100 articles atteinte.");
}
```
On peut **centraliser** le rendu de ces exceptions (JSON pour une API, page pour le web) dans le
gestionnaire d'exceptions de l'app — une **[source unique](../Principes-Genie-Logiciel/05-SSOT.md)**
du format d'erreur.

---

## 📋 Journaliser (logs)

```php
<?php
use Illuminate\Support\Facades\Log;

Log::info('Article publié', ['id' => $article->id]);
Log::warning('Quota proche de la limite', ['user' => $user->id]);
Log::error('Échec paiement', ['exception' => $e->getMessage()]);
```
Les logs vont dans `storage/logs/laravel.log`. Ils sont essentiels pour **comprendre** ce qui se
passe en production (tu t'en souviendras au parcours DevOps 😉).

---

## 🔎 À toi de chercher

> 1. Le fichier de gestion des exceptions (`bootstrap/app.php` en Laravel 11+) : comment
>    personnaliser le rendu d'une exception (ex : toujours renvoyer du JSON pour `/api/*`).
> 2. Les **niveaux de log** (debug, info, warning, error, critical) et les **channels** (`config/logging.php`).
> 3. Débat : **Service** vs **Action classes** (une classe = une seule action) — deux styles d'organisation.

---

## 🎓 Ce qu'il faut retenir

- La **logique métier** va dans des **Services** (injectés) → contrôleur fin, code testable et réutilisable (SoC / S de SOLID).
- Erreurs HTTP : `abort(404/403)`, `findOrFail` (404 auto), validation (422 auto).
- Crée des **exceptions métier** explicites et centralise leur rendu.
- **Journalise** (`Log::info/warning/error`) — indispensable en production.

---

🎉 **Tu as fini le Niveau 9 !** Contrôleurs fins, **validation** en Form Requests, **middlewares**
et **Policies** pour la sécurité, **services** pour le métier, gestion d'erreurs propre : tu
construis des fonctionnalités **robustes et maintenables**. Fais les [exercices](./exercices.md),
puis passe aux **[API REST & authentification](../Niveau-10-Laravel-API/)**. 🚀
