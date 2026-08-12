# Leçon 8.3 — Les relations entre modèles

> 🎯 **Objectif** : traduire les relations de la base (1-N, N-N) en **méthodes Eloquent**, et
> naviguer entre modèles liés comme des objets (`$user->articles`, `$article->user`).

---

## 🔗 Rappel : les relations en base

Au Niveau 6, tu as modélisé : **1-N** (un user a plusieurs articles) via une FK `user_id`, et
**N-N** (projets ↔ membres) via une table pivot. Eloquent exprime ces relations par des
**méthodes** dans les modèles.

---

## 1️⃣➡️Nﾠ Un-à-plusieurs : `hasMany` / `belongsTo`

« Un `User` **a plusieurs** `Article` ; un `Article` **appartient à** un `User`. »

```php
<?php
// app/Models/User.php
public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Article::class);        // un user a plusieurs articles
}
```
```php
<?php
// app/Models/Article.php
public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(User::class);         // un article appartient à un user
}
```
Utilisation — on navigue comme des propriétés :
```php
<?php
$user = User::find(1);
foreach ($user->articles as $article) {           // tous les articles de ce user
    echo $article->titre;
}

$article = Article::find(10);
echo $article->user->name;                        // le nom de l'auteur
```
> 💡 `$user->articles` (sans parenthèses) renvoie la **collection** ; `$user->articles()` (avec)
> renvoie le **builder** qu'on peut affiner : `$user->articles()->where('published', true)->get()`.

---

## Nﾠ↔️Nﾠ Plusieurs-à-plusieurs : `belongsToMany`

« Un `Project` **a plusieurs** membres ; un `User` participe à **plusieurs** projets. » Via la
table pivot `project_user` :

```php
<?php
// Project.php
public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
{
    return $this->belongsToMany(User::class);     // cherche la table pivot "project_user"
}
```
```php
<?php
// User.php
public function projects(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
{
    return $this->belongsToMany(Project::class);
}
```
Gérer les liens du pivot :
```php
<?php
$project = Project::find(1);
$project->members()->attach($userId);     // ajouter un membre
$project->members()->detach($userId);     // retirer un membre
$project->members()->sync([1, 2, 3]);     // fixer exactement cette liste
foreach ($project->members as $membre) { echo $membre->name; }
```

---

## 🔁 Autres relations utiles

| Méthode | Sens |
|---|---|
| `hasOne` | un-à-un (un user a **un** profil) |
| `hasMany` | un-à-plusieurs (côté « un ») |
| `belongsTo` | l'inverse (côté « plusieurs ») |
| `belongsToMany` | plusieurs-à-plusieurs (avec pivot) |
| `hasManyThrough` | à travers une table intermédiaire |

---

## ⚡ Le problème N+1 (à connaître absolument)

Parcourir des articles puis accéder à `$article->user` **déclenche une requête par article** →
des centaines de requêtes (le « **N+1** », vu au Niveau 6). La solution : l'**eager loading** avec
`with()` (leçon suivante) :

```php
<?php
// ❌ N+1 : 1 requête + une par article
foreach (Article::all() as $article) {
    echo $article->user->name;         // requête à CHAQUE tour
}

// ✅ 2 requêtes au total
foreach (Article::with('user')->get() as $article) {
    echo $article->user->name;         // déjà chargé
}
```
> 🧠 C'est **le** piège de performance des débutants Laravel. Retiens `with()` — on l'approfondit
> à la leçon suivante.

---

## 🔎 À toi de chercher

> 1. Ajoute des **données au pivot** (ex : un rôle dans le projet) avec `->withPivot('role')` et
>    `attach($id, ['role' => 'admin'])`.
> 2. Compter des relations efficacement avec `withCount('articles')`.
> 3. Cherche la convention de nommage des clés étrangères qu'Eloquent **devine** (et comment la
>    surcharger si besoin).

---

## 🎓 Ce qu'il faut retenir

- **1-N** : `hasMany` (côté « un ») + `belongsTo` (côté « plusieurs »).
- **N-N** : `belongsToMany` (via table pivot) ; gérer avec `attach`/`detach`/`sync`.
- On **navigue** entre modèles comme des propriétés (`$user->articles`, `$article->user`).
- **Attention au N+1** : utilise **`with('relation')`** (eager loading) pour charger en 2 requêtes.

👉 Leçon suivante : [Requêtes Eloquent & performance](./04-requetes-performance.md)
