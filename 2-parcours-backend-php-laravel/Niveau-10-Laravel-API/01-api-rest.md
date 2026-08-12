# Leçon 10.1 — Construire une API REST

> 🎯 **Objectif** : concevoir des **endpoints REST** propres avec un contrôleur d'API, et renvoyer
> les **bons codes de statut**. C'est le savoir-faire backend le plus recherché.

---

## 🌐 Rappel : REST en bref

Une **API REST** expose des **ressources** (articles, users…) manipulées par les **verbes HTTP**
(Niveau 5). Les conventions :

| Méthode | URL | Action | Statut succès |
|---|---|---|---|
| GET | `/api/articles` | lister | 200 |
| POST | `/api/articles` | créer | **201** |
| GET | `/api/articles/{id}` | afficher | 200 |
| PUT/PATCH | `/api/articles/{id}` | modifier | 200 |
| DELETE | `/api/articles/{id}` | supprimer | **204** |

---

## 🎛️ Le contrôleur d'API

Les routes d'API vivent dans **`routes/api.php`** (préfixées par `/api`, sans session/CSRF web).

```bash
php artisan make:controller Api/ArticleController --api --model=Article
```
```php
<?php
// routes/api.php
use App\Http\Controllers\Api\ArticleController;

Route::apiResource('articles', ArticleController::class);
```
`apiResource` génère **5** routes (pas de `create`/`edit`, qui sont des formulaires HTML).

---

## 🧩 Les actions, version API (JSON)

```php
<?php
namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;

class ArticleController extends Controller
{
    public function index()
    {
        return Article::with('user')->latest()->paginate(15);   // JSON automatique
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $request->user()->articles()->create($request->validated());
        return response()->json($article, 201);                 // 201 Created
    }

    public function show(Article $article)
    {
        return $article;                                        // 200, JSON automatique
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);                   // Policy (Niveau 9)
        $article->update($request->validated());
        return $article;
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();
        return response()->noContent();                         // 204 No Content
    }
}
```
> 💡 Retourner un modèle ou une collection Eloquent depuis un contrôleur → Laravel le convertit
> **automatiquement en JSON**. On raffinera le format avec les **Resources** (leçon suivante).

---

## 🔢 Les bons codes de statut (rappel crucial)

C'est le point que **tes stagiaires ratent** : le code HTTP fait partie du **contrat** de l'API.

```php
<?php
return response()->json($data, 201);   // création réussie
return response()->noContent();        // 204 (suppression, pas de corps)
abort(404);                            // ressource absente
abort(403, "Non autorisé.");           // interdit
// validation échouée → 422 automatique via Form Request
// non authentifié → 401 automatique via middleware auth:sanctum
```
> ⚠️ Un endpoint qui répond **200 pour une erreur** (ou **403 alors que tout va bien** à cause
> d'un `authorize()` mal réglé) est un **bug** : le client/les tests s'appuient sur ces codes.

---

## 🧪 Tester son API

```bash
curl http://localhost:8000/api/articles
curl -X POST http://localhost:8000/api/articles \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"titre":"Test","contenu":"..."}'
```
> 💡 Envoie l'en-tête **`Accept: application/json`** : il indique à Laravel de renvoyer les
> erreurs (validation, 404…) en **JSON** plutôt qu'en HTML.

---

## 🔎 À toi de chercher

> 1. Différence entre `routes/api.php` et `routes/web.php` (stateless + pas de CSRF, préfixe `/api`).
> 2. Comment personnaliser la clé du route model binding (`/articles/{article:slug}`).
> 3. Cherche les bonnes pratiques de **nommage** des endpoints REST (noms au pluriel, pas de verbe dans l'URL).

---

## 🎓 Ce qu'il faut retenir

- Une **API REST** : ressources + verbes HTTP ; routes dans **`routes/api.php`** via `apiResource`.
- Un modèle/collection retourné → **JSON automatique** ; `paginate()` pour les listes.
- Renvoie **le bon code** : 201 (créé), 204 (supprimé), 401/403/404/422 selon le cas.
- Envoie `Accept: application/json` pour des erreurs en JSON.

👉 Leçon suivante : [Les API Resources (formater le JSON)](./02-api-resources.md)
