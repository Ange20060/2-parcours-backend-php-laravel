# Leçon 7.3 — Routes et contrôleurs

> 🎯 **Objectif** : définir des **routes** (l'URL → le code qui répond) et déléguer le travail à
> des **contrôleurs**. C'est le routing que tu faisais à la main au Niveau 5, en une ligne propre.

---

## 🛣️ Définir une route

Dans `routes/web.php` (pages) ou `routes/api.php` (API) :

```php
<?php
use Illuminate\Support\Facades\Route;

Route::get('/bonjour', function () {
    return 'Bonjour Laravel';
});
```
`Route::get('/bonjour', ...)` = « quand une requête **GET** arrive sur `/bonjour`, exécute cette
fonction ». Un verbe par méthode : `Route::get`, `Route::post`, `Route::put`, `Route::patch`,
`Route::delete`.

---

## 🎯 Paramètres de route

```php
<?php
Route::get('/articles/{id}', function (string $id) {
    return "Article numéro $id";
});
```
Le `{id}` de l'URL est **injecté** dans la fonction. `/articles/42` → « Article numéro 42 ».

---

## 🎛️ Déléguer à un contrôleur (la vraie façon)

Mettre toute la logique dans `routes/*.php` devient vite ingérable. On la déplace dans un
**contrôleur** — une classe qui regroupe les actions liées à une ressource.

```bash
php artisan make:controller PageController
```
```php
<?php
// app/Http/Controllers/PageController.php
namespace App\Http\Controllers;

class PageController extends Controller
{
    public function accueil(): string
    {
        return 'Bienvenue sur la page d\'accueil';
    }
}
```
```php
<?php
// routes/web.php
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'accueil']);
```
> 🧠 La route dit **quoi** appeler ; le contrôleur dit **comment** répondre. Les routes restent
> une **table des matières** lisible de l'application.

---

## 🧩 Le contrôleur « ressource » (le motif REST)

Pour une entité qu'on gère en CRUD (articles, produits…), Laravel génère les **7 actions
standard** d'un coup :

```bash
php artisan make:controller ArticleController --resource
```
```php
<?php
Route::resource('articles', ArticleController::class);
```
Cette **seule ligne** crée toutes les routes REST :

| Méthode | URL | Action | Rôle |
|---|---|---|---|
| GET | `/articles` | `index` | lister |
| GET | `/articles/create` | `create` | formulaire de création |
| POST | `/articles` | `store` | enregistrer |
| GET | `/articles/{article}` | `show` | afficher un |
| GET | `/articles/{article}/edit` | `edit` | formulaire d'édition |
| PUT/PATCH | `/articles/{article}` | `update` | mettre à jour |
| DELETE | `/articles/{article}` | `destroy` | supprimer |

> 💡 Pour une **API** (sans les formulaires HTML `create`/`edit`), utilise `--api` et
> `Route::apiResource(...)` → seulement 5 actions. Tu le verras au Niveau 10.

---

## 🔗 Route model binding (la magie utile)

Si tu type-hint un **modèle** dans la méthode, Laravel **récupère automatiquement** l'objet
correspondant à l'`{article}` de l'URL (ou renvoie **404** s'il n'existe pas) :

```php
<?php
use App\Models\Article;

public function show(Article $article)   // Laravel charge l'article correspondant
{
    return $article;                     // plus besoin de Article::find($id) à la main
}
```
> 🎯 Fini le `Article::find($id)` répétitif et l'oubli du cas « introuvable » : c'est géré,
> **[explicitement](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)** et sans duplication.

---

## 🛠️ Voir et nommer ses routes

```bash
php artisan route:list           # lister TOUTES les routes de l'app
```
```php
<?php
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// puis, ailleurs : route('contact') génère l'URL — pas d'URL codée en dur (SSOT)
```

---

## 🔎 À toi de chercher

> 1. Groupes de routes : `Route::prefix('admin')->group(...)` et `Route::middleware('auth')->group(...)`.
> 2. Différence entre `Route::resource` et `Route::apiResource`.
> 3. Cherche comment restreindre un paramètre de route (`->where('id', '[0-9]+')`).

---

## 🎓 Ce qu'il faut retenir

- Une **route** relie une **méthode + URL** à du code (`Route::get`, `post`, `put`, `delete`).
- On délègue au **contrôleur** ; les routes restent une table des matières lisible.
- **`Route::resource`** génère les 7 actions CRUD d'une ligne ; `apiResource` pour une API.
- Le **route model binding** (type-hint d'un modèle) charge l'objet automatiquement (ou 404).
- **Nomme** tes routes (`->name(...)`) et utilise `route('nom')` — pas d'URL en dur.

👉 Leçon suivante : [Les vues Blade](./04-blade.md)
