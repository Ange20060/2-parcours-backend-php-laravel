# 📝 Niveau 9 — Exercices (Contrôleurs, Validation & Middleware)

Sur ton projet Laravel avec le modèle `Article`. **🎯 But** à chaque exercice. Corrigés :
[corriges.md](./corriges.md).

> 🎯 **Exigence** : contrôleurs **fins** (ils orchestrent), logique métier dans des **services**,
> validation en amont ([Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)).

---

## Exercice 1 — Contrôleur ressource 🎛️
> 🎯 **But** : générer un CRUD web complet avec un contrôleur ressource.

1. `php artisan make:controller ArticleController --resource`.
2. Déclare `Route::resource('articles', ArticleController::class);`.
3. Implémente `index` (liste) et `show` (détail) en retournant des vues Blade.

---

## Exercice 2 — Validation en ligne ✅
> 🎯 **But** : valider les données d'un formulaire (Fail Fast).

Dans `store()`, valide la requête : `title` requis, max 255 ; `content` requis. Utilise
`$request->validate([...])`. Que se passe-t-il si la validation échoue (redirection ? erreurs ?) ?

---

## Exercice 3 — Form Request dédiée 🧾
> 🎯 **But** : extraire la validation dans une classe (SoC + DRY + réutilisable).

1. `php artisan make:request StoreArticleRequest`.
2. Déplace les règles de validation dedans (`rules()`).
3. Type-hint `StoreArticleRequest` dans `store()`. Le contrôleur devient plus **fin**.

---

## Exercice 4 — Messages d'erreur personnalisés 💬
> 🎯 **But** : rendre les erreurs **explicites** pour l'utilisateur.

Ajoute des messages personnalisés en français dans la Form Request (`messages()`), ex :
« Le titre est obligatoire ». Affiche les erreurs dans la vue avec `@error`.

---

## Exercice 5 — Middleware 🛡️
> 🎯 **But** : filtrer les requêtes (authentification, autorisation).

1. Protège les routes de création/édition avec le middleware `auth`.
2. Crée un middleware custom `EnsureArticleOwner` qui vérifie que l'utilisateur connecté est
   bien l'auteur de l'article avant de le laisser le modifier. `php artisan make:middleware`.

---

## Exercice 6 — Le pattern Service 🌟
> 🎯 **But** : sortir la logique métier du contrôleur (SoC, testabilité).

Crée `App\Services\ArticleService` avec une méthode `publier(Article $article): void` (met
`published` à true + logique associée). Le contrôleur **appelle** le service au lieu de contenir
la logique. Explique pourquoi ça rend le code plus testable et respecte la
**[responsabilité unique](../Principes-Genie-Logiciel/11-SOLID.md)**.

---

👉 Correction : [corriges.md](./corriges.md)
