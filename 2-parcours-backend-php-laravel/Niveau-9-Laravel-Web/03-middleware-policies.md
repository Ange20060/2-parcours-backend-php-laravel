# Leçon 9.3 — Middleware & autorisation (Policies)

> 🎯 **Objectif** : filtrer les requêtes avec les **middlewares**, et contrôler **qui a le droit
> de faire quoi** avec les **Policies**. C'est LE point faible des copies de stagiaires : une API
> où « n'importe qui peut modifier les données de n'importe qui ».

---

## 🛡️ Les middlewares : des filtres autour de la requête

Un **middleware** s'exécute **avant** (ou après) le contrôleur : il peut **laisser passer**,
**bloquer**, ou **modifier** la requête/réponse. Idéal pour l'authentification, les rôles, le
throttling…

```php
<?php
// routes/web.php — protéger des routes : il faut être connecté
Route::middleware('auth')->group(function () {
    Route::resource('articles', ArticleController::class);
});

// ou route par route
Route::get('/profil', [ProfilController::class, 'show'])->middleware('auth');
```
Middlewares intégrés utiles : `auth` (connecté), `guest` (non connecté), `throttle:60,1` (limiter
le nombre de requêtes), `verified` (email vérifié).

---

## 🔨 Créer son propre middleware

```bash
php artisan make:middleware EnsureUserIsAdmin
```
```php
<?php
// app/Http/Middleware/EnsureUserIsAdmin.php
public function handle(Request $request, Closure $next): Response
{
    if (! $request->user()?->is_admin) {
        abort(403, "Réservé aux administrateurs.");   // Fail Fast
    }
    return $next($request);     // laisser passer vers le contrôleur
}
```
`$next($request)` = « continuer le traitement ». Sans lui, la requête est **bloquée**.

---

## 🔑 Middleware ≠ autorisation métier

Le middleware `auth` répond à **« es-tu connecté ? »**. Mais **« as-tu le droit de modifier
CET article ? »** est une question **métier** (l'article t'appartient-il ?). Répondre à ça, c'est
le rôle des **Policies**.

---

## 👮 Les Policies : qui peut faire quoi sur un objet

Une **Policy** regroupe les règles d'autorisation d'un modèle (peut-il voir / modifier /
supprimer **cet** objet précis).

```bash
php artisan make:policy ArticlePolicy --model=Article
```
```php
<?php
// app/Policies/ArticlePolicy.php
class ArticlePolicy
{
    public function update(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;   // seul l'auteur peut modifier
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->id === $article->user_id;
    }
}
```
On l'applique dans le contrôleur avec `authorize()` :
```php
<?php
public function update(UpdateArticleRequest $request, Article $article)
{
    $this->authorize('update', $article);      // 403 si la policy renvoie false
    $article->update($request->validated());
    return redirect()->route('articles.show', $article);
}
```
> 🎯 **La faille classique des stagiaires** : sans Policy, tout utilisateur connecté peut modifier
> ou supprimer les données des **autres**. `$this->authorize('update', $article)` ferme cette
> porte proprement, en renvoyant **403** si l'utilisateur n'est pas légitime.

Dans une vue Blade :
```blade
@can('update', $article)
    <a href="{{ route('articles.edit', $article) }}">Modifier</a>
@endcan
```

---

## 🧩 Middleware vs Policy — le bon usage

| Question | Outil |
|---|---|
| « Es-tu **connecté** ? » | Middleware `auth` |
| « As-tu un **rôle** (admin) ? » | Middleware custom (ou policy) |
| « As-tu le droit sur **CET objet** précis ? » | **Policy** (`authorize('update', $article)`) |

> 🧠 En résumé : middleware = filtre **général** sur la requête ; policy = décision **métier** sur
> un **objet précis**. Les deux se complètent — et une app sérieuse a **les deux**.

---

## 🔎 À toi de chercher

> 1. La méthode `before()` d'une policy : un **admin** qui a tous les droits d'un coup.
> 2. Les **Gates** (`Gate::define(...)`) : autorisations simples non liées à un modèle.
> 3. Comment tester une autorisation : simuler un utilisateur **non propriétaire** et vérifier le **403**.

---

## 🎓 Ce qu'il faut retenir

- Un **middleware** filtre la requête (auth, rôles, throttling) **avant** le contrôleur ; `$next($request)` laisse passer.
- Le middleware `auth` = « connecté ? » ; il ne suffit **pas** pour l'autorisation métier.
- Une **Policy** décide « ce user a-t-il le droit sur **CET** objet ? » → `$this->authorize('update', $article)`.
- **Toujours** protéger les actions d'écriture par une autorisation d'appartenance (faille n°1 des débutants).

👉 Leçon suivante : [Services & gestion des erreurs](./04-services-erreurs.md)
