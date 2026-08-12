# Leçon 8.4 — Requêtes Eloquent, factories & seeders

> 🎯 **Objectif** : écrire des requêtes efficaces (éviter le **N+1**), et **remplir** ta base de
> données de test réalistes avec les **factories** et **seeders**.

---

## ⚡ Eager loading : tuer le problème N+1

Rappel : accéder à une relation dans une boucle déclenche **une requête par élément**. `with()`
charge la relation **en amont**, en une seule requête supplémentaire.

```php
<?php
// ❌ N+1 : 1 + N requêtes
$articles = Article::all();
foreach ($articles as $a) { echo $a->user->name; }

// ✅ Eager loading : 2 requêtes, quel que soit le nombre d'articles
$articles = Article::with('user')->get();

// Charger plusieurs relations, et des relations imbriquées
$articles = Article::with(['user', 'comments.user'])->get();
```
> 🔎 Pour **détecter** un N+1 : installe **Laravel Debugbar** ou **Telescope** — ils affichent le
> nombre de requêtes exécutées. Un nombre qui explose avec la taille des données = un N+1.

---

## 🔧 Requêtes courantes

```php
<?php
Article::where('published', true)
    ->where('titre', 'like', '%php%')
    ->orderBy('created_at', 'desc')
    ->get();

Article::whereIn('id', [1, 2, 3])->get();
Article::whereNull('published_at')->get();
Article::where('vues', '>', 100)->get();

// Agrégats
Article::count();
Article::where('published', true)->count();
Order::sum('montant');
Article::withCount('comments')->get();     // ajoute une colonne comments_count
```

---

## 📄 La pagination (indispensable)

Ne renvoie **jamais** des milliers de lignes d'un coup. `paginate()` découpe automatiquement :

```php
<?php
$articles = Article::latest()->paginate(15);   // 15 par page
```
`paginate()` fournit les métadonnées (page courante, total, liens) — parfait pour une vue ou une
API. On y reviendra pour les API au Niveau 10.

---

## 🏭 Les factories : générer des données réalistes

Écrire des données de test à la main est fastidieux et répétitif. Une **factory** décrit **comment
fabriquer** un modèle avec de fausses données crédibles (via **Faker**).

```bash
php artisan make:factory ArticleFactory
```
```php
<?php
// database/factories/ArticleFactory.php
public function definition(): array
{
    return [
        'titre'     => fake()->sentence(),
        'contenu'   => fake()->paragraphs(3, true),
        'published' => fake()->boolean(),
        'user_id'   => \App\Models\User::factory(),   // crée aussi un user au besoin
    ];
}
```
Utilisation :
```php
<?php
Article::factory()->create();                 // 1 article
Article::factory()->count(20)->create();      // 20 articles
Article::factory()->count(5)->create(['published' => true]);  // en forçant un champ
```
> 💡 Les factories évitent la duplication (**DRY**) et rendent les **tests** reproductibles
> (Niveau 11) : chaque test génère les données dont il a besoin.

---

## 🌱 Les seeders : remplir la base

Un **seeder** peuple la base (données de départ, jeu de démo) en appelant les factories.

```php
<?php
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    $users = User::factory()->count(10)->create();

    Article::factory()
        ->count(30)
        ->recycle($users)                 // réutilise ces users comme auteurs
        ->create();
}
```
```bash
php artisan db:seed
php artisan migrate:fresh --seed         # recrée le schéma + remplit (dev)
```

---

## 🔎 À toi de chercher

> 1. Les **states** de factory : `Article::factory()->published()->create()` — définir des variantes.
> 2. Les **scopes** Eloquent (`scopePublished`) : nommer une condition réutilisable
>    (`Article::published()->get()`) — lien avec **[DRY](../Principes-Genie-Logiciel/01-DRY.md)**.
> 3. `chunk()` / `cursor()` : parcourir de **très** gros volumes sans saturer la mémoire.

---

## 🎓 Ce qu'il faut retenir

- **Eager loading `with('relation')`** = la parade au **N+1** (2 requêtes au lieu de N+1).
- Requêtes chaînées : `where`, `orderBy`, `whereIn`, agrégats, `withCount`.
- **`paginate(15)`** au lieu de tout renvoyer d'un coup.
- **Factories** (fausses données réalistes) + **seeders** (remplir la base) → DRY et tests reproductibles.

---

🎉 **Tu as fini le Niveau 8 !** Tu versionnes ton schéma (**migrations**), tu manipules la base
comme des objets (**Eloquent** + relations), tu évites le **N+1** et tu génères des données de
test. Fais les [exercices](./exercices.md), puis construis de vraies fonctionnalités web au
**[Niveau 9](../Niveau-9-Laravel-Web/)**. 🚀
