# Leçon 8.2 — Les modèles Eloquent & le CRUD

> 🎯 **Objectif** : manipuler la base **comme des objets** avec l'ORM **Eloquent** — créer, lire,
> modifier, supprimer **sans écrire de SQL**. C'est le Repository du Niveau 6, industrialisé.

---

## 🧬 Un ORM, c'est quoi ?

Un **ORM** (*Object-Relational Mapping*) fait le pont entre les **tables** de la base et des
**objets** PHP. Une ligne de la table `articles` devient un objet `Article`. Tu manipules des
objets ; Eloquent génère le SQL (avec des **requêtes préparées**, donc sécurisé) pour toi.

---

## 📦 Créer un modèle

```bash
php artisan make:model Article
# ou, tout en un : le modèle + sa migration + un contrôleur ressource + une factory
php artisan make:model Article -mcr
```
```php
<?php
// app/Models/Article.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['titre', 'contenu', 'published'];   // colonnes assignables (voir plus bas)
}
```
> 💡 Convention : le modèle `Article` (singulier) correspond à la table `articles` (pluriel).
> Laravel fait le lien automatiquement.

---

## 🔁 Le CRUD avec Eloquent

### Create — créer
```php
<?php
use App\Models\Article;

$article = Article::create([
    'titre'   => 'Mon premier article',
    'contenu' => 'Bonjour !',
]);
// ou étape par étape :
$article = new Article();
$article->titre = 'Autre';
$article->contenu = '...';
$article->save();
```

### Read — lire
```php
<?php
Article::all();                       // tous les articles
Article::find(1);                     // par id (null si absent)
Article::findOrFail(1);               // par id (404 si absent) — pratique dans un contrôleur
Article::where('published', true)->get();          // filtrer
Article::where('published', true)->first();        // le premier qui correspond
Article::latest()->take(5)->get();                 // les 5 plus récents
Article::count();                                  // compter
```

### Update — modifier
```php
<?php
$article = Article::findOrFail(1);
$article->update(['titre' => 'Titre modifié']);
// ou :
$article->titre = 'Titre modifié';
$article->save();
```

### Delete — supprimer
```php
<?php
$article = Article::findOrFail(1);
$article->delete();
```
Compare avec le PDO du Niveau 6 : **plus de SQL à écrire**, plus de requêtes préparées à gérer à
la main. Eloquent applique **le Repository/Active Record** de façon standardisée.

---

## 🛡️ Le mass assignment et `$fillable` (sécurité)

`Article::create($donnees)` remplit le modèle en masse à partir d'un tableau. **Danger** : si
`$donnees` vient de l'utilisateur, il pourrait injecter des champs non prévus (`is_admin`,
`user_id`…). La **liste blanche** `$fillable` autorise **explicitement** les colonnes assignables :

```php
<?php
class Article extends Model
{
    protected $fillable = ['titre', 'contenu', 'published'];   // SEULS ces champs sont assignables en masse
}
```
> ⚠️ Réflexe (vu dans les corrections récentes) : **ne jamais** faire `Model::create($request->all())`
> sans `$fillable`. C'est une faille classique. Avec `$fillable`, tu es protégé par défaut.

---

## 🔧 Le query builder (requêtes plus riches)

Eloquent chaîne les conditions de façon lisible :

```php
<?php
$articles = Article::query()
    ->where('published', true)
    ->where('titre', 'like', '%php%')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();
```
Chaque méthode renvoie le **builder**, qu'on continue d'enchaîner. La requête n'est exécutée
qu'au **`get()`** (ou `first()`, `count()`…).

---

## ⏱️ Les timestamps et les casts

- Avec `$table->timestamps()` en migration, Eloquent remplit **`created_at`** et **`updated_at`**
  automatiquement à chaque création/modification.
- Les **casts** convertissent une colonne en type PHP (booléen, date, tableau, enum) :

```php
<?php
protected function casts(): array
{
    return [
        'published'  => 'boolean',
        'published_at' => 'datetime',
        'options'    => 'array',           // colonne JSON <-> tableau PHP
    ];
}
```

---

## 🔎 À toi de chercher

> 1. `firstOrCreate`, `updateOrCreate`, `findOrFail`, `firstOrFail` — que font-ils ?
> 2. Le **soft delete** (`SoftDeletes`) : « supprimer » sans effacer réellement (colonne `deleted_at`).
> 3. Différence entre `get()` (une collection) et `first()` (un modèle), et ce qu'est une
>    **Collection** Laravel (`map`, `filter`, `pluck`…).

---

## 🎓 Ce qu'il faut retenir

- **Eloquent** mappe une table à un **modèle** : tu manipules des objets, il génère le SQL sécurisé.
- CRUD : `create`, `find`/`findOrFail`/`where`/`get`, `update`, `delete`.
- **`$fillable`** = liste blanche obligatoire contre le mass assignment (sécurité).
- Le **query builder** s'enchaîne (`where`, `orderBy`, `limit`) et s'exécute au `get()`.
- `timestamps()` et **casts** automatisent dates, booléens, JSON…

👉 Leçon suivante : [Les relations entre modèles](./03-relations.md)
