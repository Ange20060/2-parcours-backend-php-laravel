# ✅ Niveau 8 — Corrigés (Eloquent & Migrations)

> ⚠️ Essaie d'abord. Code Laravel idiomatique.

---

## Exercice 1 — Migration

```php
<?php
// database/migrations/xxxx_create_articles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->boolean('published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
```

## Exercice 2 — CRUD (dans Tinker)

```php
// Créer
$a = Article::create(['title' => 'Hello', 'content' => 'Mon premier article']);
// Lire
$a = Article::find(1);
Article::all();
// Modifier
$a->update(['title' => 'Titre modifié']);
// Supprimer
$a->delete();
```

## Exercice 3 — Mass assignment

```php
<?php
// app/Models/Article.php
class Article extends Model
{
    protected $fillable = ['title', 'content', 'published'];
}
```

Sans `$fillable`, `Article::create($request->all())` permettrait à un utilisateur malveillant
d'envoyer des champs non prévus (ex : `is_admin`, `user_id`) et de les écrire en base. `$fillable`
est une **liste blanche explicite** des colonnes assignables → sécurité par défaut.

## Exercice 4 — Relations

```php
<?php
// app/Models/User.php
public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Article::class);
}

// app/Models/Article.php
public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(User::class);
}
```

Migration pour la clé étrangère :

```php
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
```

Utilisation : `$user->articles` (collection), `$article->user` (le modèle User).

## Exercice 5 — Requêtes & N+1

```php
// Articles publiés, plus récents d'abord
$articles = Article::where('published', true)->orderBy('created_at', 'desc')->get();

// ❌ Problème N+1 : 1 requête pour les articles + 1 requête PAR article pour l'auteur
foreach ($articles as $article) {
    echo $article->user->name;   // déclenche une requête à chaque tour
}

// ✅ Eager loading : 2 requêtes au total, quel que soit le nombre d'articles
$articles = Article::with('user')->where('published', true)->get();
foreach ($articles as $article) {
    echo $article->user->name;   // déjà chargé, aucune requête supplémentaire
}
```

Le **N+1** (1 requête initiale + N requêtes pour les relations) tue les performances. `with()`
charge tout en amont (**eager loading**).

## Exercice 6 — Factory & Seeder

```php
<?php
// database/factories/ArticleFactory.php
public function definition(): array
{
    return [
        'title'     => fake()->sentence(),
        'content'   => fake()->paragraphs(3, true),
        'published' => fake()->boolean(),
        'user_id'   => \App\Models\User::factory(),
    ];
}
```

```php
<?php
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    \App\Models\Article::factory()->count(20)->create();
}
```

Les factories génèrent des données réalistes **automatiquement** : plus besoin de saisir 20
articles à la main (**DRY**), et les tests deviennent reproductibles.

---

## 🎉 Bilan du Niveau 8

Tu versionnes ton schéma (migrations = SSOT), tu manipules les données comme des objets, tu
modélises les relations, tu évites le N+1 et tu génères des données de test. Tu es prêt·e à
construire des fonctionnalités web complètes.
👉 [Niveau 9 : Contrôleurs, Validation &amp; Middleware](../Niveau-9-Laravel-Web/)
