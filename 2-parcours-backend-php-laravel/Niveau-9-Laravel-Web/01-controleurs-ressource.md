# Leçon 9.1 — Contrôleurs ressource & CRUD web

> 🎯 **Objectif** : construire un CRUD web complet avec un **contrôleur ressource**, en gardant
> le contrôleur **fin** (il orchestre, il ne fait pas tout).

---

## 🎛️ Le contrôleur ressource

Pour une entité gérée en CRUD, on génère les 7 actions standard et on branche toutes les routes
d'une ligne (rappel Niveau 7) :

```bash
php artisan make:controller ArticleController --resource --model=Article
```
```php
<?php
// routes/web.php
Route::resource('articles', ArticleController::class);
```

---

## 📖 Les actions de lecture

```php
<?php
namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->paginate(10);       // pagination
        return view('articles.index', ['articles' => $articles]);
    }

    public function show(Article $article)                 // route model binding
    {
        return view('articles.show', ['article' => $article]);
    }
}
```
> 💡 `Article $article` en paramètre = **route model binding** (Niveau 7) : Laravel charge
> l'article de l'URL, ou renvoie **404** automatiquement. Pas de `find` + vérification manuelle.

---

## ✏️ Les actions d'écriture

```php
<?php
public function create()
{
    return view('articles.create');       // affiche le formulaire vide
}

public function store(Request $request)   // (on améliorera avec une Form Request, leçon 9.2)
{
    $donnees = $request->validate([
        'titre'   => 'required|max:255',
        'contenu' => 'required',
    ]);
    Article::create($donnees);
    return redirect()->route('articles.index')->with('success', 'Article créé !');
}

public function edit(Article $article)
{
    return view('articles.edit', ['article' => $article]);
}

public function update(Request $request, Article $article)
{
    $donnees = $request->validate([
        'titre'   => 'required|max:255',
        'contenu' => 'required',
    ]);
    $article->update($donnees);
    return redirect()->route('articles.show', $article);
}

public function destroy(Article $article)
{
    $article->delete();
    return redirect()->route('articles.index')->with('success', 'Supprimé.');
}
```

---

## 🔁 Réponses : redirections et messages flash

Après une action d'écriture, on **redirige** (jamais réafficher un POST) et on transmet un
**message flash** (affiché une fois) :

```php
<?php
return redirect()->route('articles.index')->with('success', 'Article créé !');
return back();                       // revenir à la page précédente
return back()->withErrors($erreurs);
```
Dans la vue :
```blade
@if (session('success'))
    <p class="alerte">{{ session('success') }}</p>
@endif
```

---

## 🧭 Garder le contrôleur FIN (règle d'or)

Un contrôleur **orchestre** : il reçoit la requête, délègue, renvoie une réponse. Il ne doit
**pas** contenir de logique métier lourde ni de SQL brut.

```php
<?php
// ❌ Contrôleur qui fait tout (calculs, règles métier, notifications…)
public function store(Request $request) { /* 60 lignes de logique */ }

// ✅ Contrôleur fin : il délègue à un Service (leçon 9.4)
public function store(StoreArticleRequest $request, ArticleService $service)
{
    $article = $service->creer($request->validated(), $request->user());
    return redirect()->route('articles.show', $article);
}
```
> 🧠 C'est la **[séparation des responsabilités](../Principes-Genie-Logiciel/04-SoC.md)** : le
> contrôleur = la couche HTTP, le service = la logique métier. Un contrôleur « pieuvre » (comme
> dans certaines copies de stagiaires) est un signal d'alarme.

---

## 🔎 À toi de chercher

> 1. `--api` vs `--resource` : quelles actions disparaissent pour une API (pas de `create`/`edit`) ?
> 2. Comment restreindre les actions générées : `Route::resource(...)->only([...])` / `->except([...])`.
> 3. Les **messages flash** : différence entre `with()`, `withErrors()` et `withInput()`.

---

## 🎓 Ce qu'il faut retenir

- **`make:controller --resource --model`** + `Route::resource` = CRUD web complet.
- **Route model binding** (type-hint du modèle) charge l'objet ou renvoie 404.
- Après une écriture : **rediriger** + **message flash** (`->with('success', ...)`).
- Garde le contrôleur **fin** : il orchestre, il délègue le métier à un **service** (SoC).

👉 Leçon suivante : [La validation & les Form Requests](./02-validation.md)
