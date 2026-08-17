

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

```

Les factories génèrent des données réalistes **automatiquement** : plus besoin de saisir 20
articles à la main (**DRY**), et les tests deviennent reproductibles.

---

## 🎉 Bilan du Niveau 8

Tu versionnes ton schéma (migrations = SSOT), tu manipules les données comme des objets, tu
modélises les relations, tu évites le N+1 et tu génères des données de test. Tu es prêt·e à
construire des fonctionnalités web complètes.
👉 [Niveau 9 : Contrôleurs, Validation &amp; Middleware](../Niveau-9-Laravel-Web/)
