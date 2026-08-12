# ✅ Niveau 9 — Corrigés (Contrôleurs, Validation & Middleware)

> ⚠️ Essaie d'abord. Code Laravel idiomatique.

---

## Exercice 1 — Contrôleur ressource
```php
<?php
// routes/web.php
Route::resource('articles', ArticleController::class);
```
```php
<?php
// app/Http/Controllers/ArticleController.php
public function index()
{
    $articles = Article::latest()->paginate(10);
    return view('articles.index', ['articles' => $articles]);
}

public function show(Article $article)   // route model binding : Laravel injecte l'article
{
    return view('articles.show', ['article' => $article]);
}
```
> 💡 `Article $article` dans la signature = **route model binding** : Laravel récupère
> automatiquement l'article correspondant à l'`{article}` de l'URL (ou renvoie 404).

## Exercice 2 — Validation en ligne
```php
<?php
public function store(Request $request)
{
    $valides = $request->validate([
        'title'   => 'required|max:255',
        'content' => 'required',
    ]);
    Article::create($valides);
    return redirect()->route('articles.index')->with('success', 'Article créé !');
}
```
Si la validation échoue, Laravel **redirige en arrière** automatiquement, avec les **erreurs**
et les **anciennes valeurs** (`old()`) en session. Le code après `validate()` ne s'exécute
**que** si tout est valide — c'est du **Fail Fast** intégré.

## Exercice 3 — Form Request
```php
<?php
// app/Http/Requests/StoreArticleRequest.php
class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'   => 'required|max:255',
            'content' => 'required',
        ];
    }
}
```
```php
<?php
// contrôleur — devient minimal
public function store(StoreArticleRequest $request)
{
    Article::create($request->validated());
    return redirect()->route('articles.index');
}
```
La validation est **isolée** (SoC), **réutilisable** (DRY) et le contrôleur reste **fin**.

## Exercice 4 — Messages personnalisés
```php
<?php
// dans StoreArticleRequest
public function messages(): array
{
    return [
        'title.required'   => 'Le titre est obligatoire.',
        'title.max'        => 'Le titre ne doit pas dépasser 255 caractères.',
        'content.required' => 'Le contenu est obligatoire.',
    ];
}
```
```blade
{{-- dans la vue --}}
@error('title') <p class="erreur">{{ $message }}</p> @enderror
```

## Exercice 5 — Middleware
```php
<?php
// routes/web.php
Route::resource('articles', ArticleController::class)
    ->middleware('auth')
    ->only(['create', 'store', 'edit', 'update', 'destroy']);
```
```php
<?php
// app/Http/Middleware/EnsureArticleOwner.php
public function handle(Request $request, Closure $next): Response
{
    $article = $request->route('article');
    if ($article->user_id !== $request->user()->id) {
        abort(403, "Vous n'êtes pas l'auteur de cet article.");   // Fail Fast
    }
    return $next($request);
}
```
Le middleware **filtre** la requête avant le contrôleur : responsabilité isolée (SoC).

## Exercice 6 — Service
```php
<?php
// app/Services/ArticleService.php
namespace App\Services;

use App\Models\Article;

class ArticleService
{
    public function publier(Article $article): void
    {
        $article->update(['published' => true]);
        // ... autre logique métier : notifier des abonnés, journaliser, etc.
    }
}
```
```php
<?php
// contrôleur — il ORCHESTRE, il ne fait pas le métier lui-même
public function publish(Article $article, ArticleService $service)
{
    $service->publier($article);
    return back()->with('success', 'Article publié.');
}
```
La logique métier est **hors** du contrôleur : on peut la **tester** isolément, la **réutiliser**
(depuis une commande Artisan, un job…), et le contrôleur a **une seule responsabilité**
(orchestrer la requête HTTP). C'est le **S** de SOLID.

---

## 🎉 Bilan du Niveau 9
Contrôleurs fins, validation en Form Requests, middlewares pour la sécurité, logique en
services : tu construis des fonctionnalités web **propres et maintenables**.
👉 [Niveau 10 : API REST & Authentification](../Niveau-10-Laravel-API/)
