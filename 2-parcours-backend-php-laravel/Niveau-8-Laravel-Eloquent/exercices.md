# 📝 Niveau 8 — Exercices (Eloquent & Migrations)

Sur un projet Laravel avec une base configurée (SQLite le plus simple : `DB_CONNECTION=sqlite`

+ un fichier `database/database.sqlite`). **🎯 But** à chaque exercice. Corrigés :
  [corriges.md](./corriges.md).

> 🎯 **Exigence** : le schéma vit dans les **migrations** (SSOT), pas « à la main » dans la base.
> Garde les modèles cohésifs.

---

## Exercice 1 — Migration 🏗️

> 🎯 **But** : créer et faire évoluer un schéma avec les migrations.

1. `		`.
2. Définis les colonnes : `title` (string), `content` (text), `published` (bool, défaut false),
   `timestamps`.
3. Lance `php artisan migrate`. Vérifie la table créée.

---

## Exercice 2 — Modèle & CRUD 🔁

> 🎯 **But** : manipuler les données comme des **objets** avec Eloquent.

1. `php artisan make:model Article`.
2. Dans Tinker, réalise un CRUD complet : créer un article, le lire (`find`), le modifier, le
   supprimer, lister tous les articles.

---

	## Exercice 3 — Mass assignment sécurisé 🛡️

> 🎯 **But** : comprendre `$fillable` (protection contre l'assignation de masse).

Configure `$fillable` sur le modèle `Article` et crée un article via `Article::create([...])`.
Explique pourquoi remplir aveuglément un modèle avec `$request->all()` sans `$fillable` est un
risque de sécurité (Fail Fast / Explicite).

---

## Exercice 4 — Relations 🔗

> 🎯 **But** : modéliser `1—N` avec `hasMany` / `belongsTo`.

1. Un `User` **a plusieurs** `Article` ; un `Article` **appartient à** un `User`.
2. Ajoute la colonne `user_id` (migration) et les méthodes de relation dans les modèles.
3. Dans Tinker : `$user->articles` et `$article->user`.

---

## Exercice 5 — Requêtes & N+1 ⚡

> 🎯 **But** : écrire des requêtes efficaces et éviter le **problème N+1**.

1. Récupère les articles **publiés**, triés par date (`where` + `orderBy`).
2. Affiche chaque article avec le nom de son auteur, une fois **sans** `with('user')`, une fois
   **avec**. Cherche pourquoi la 2ᵉ version évite le problème N+1.

> 🔎 « laravel eager loading n+1 ».

---

## Exercice 6 — Seeder & Factory 🌱

> 🎯 **But** : générer des données de test réalistes.

1. Crée une factory pour `Article` (`php artisan make:factory ArticleFactory`).
2. Génère 20 articles via un seeder (`php artisan db:seed`). Pourquoi les factories évitent-elles
   de saisir des données à la main (DRY) ?

---

👉 Correction : [corriges.md](./corriges.md)
