# 📝 Niveau 10 — Exercices (API REST & Authentification)

Sur ton projet Laravel. Teste les endpoints avec `curl`, Postman ou l'extension REST de VSCode.
**🎯 But** à chaque exercice. Corrigés : [corriges.md](./corriges.md).

> 🎯 **Exigence** : une API **prévisible** — bons **verbes HTTP**, bons **codes de statut**,
> format de sortie **cohérent** (Explicite + SSOT).

---

## Exercice 1 — Routes d'API RESTful 🌐
> 🎯 **But** : concevoir des endpoints REST corrects.

Dans `routes/api.php`, crée un contrôleur d'API `--api` pour `Article` et déclare
`Route::apiResource('articles', ...)`. Liste les 5 endpoints générés et le **verbe HTTP** +
**code de statut** attendu pour chacun (index, store, show, update, destroy).

---

## Exercice 2 — API Resource (format JSON) 🎁
> 🎯 **But** : contrôler le **format de sortie** JSON (SSOT du rendu).

1. `php artisan make:resource ArticleResource`.
2. Définis les champs exposés (id, title, content, published, author). **N'expose pas** les
   champs internes inutiles. Retourne `ArticleResource::collection(...)` depuis `index`.
Pourquoi centraliser le format ici plutôt que de faire un `json_encode` à la main partout ?

---

## Exercice 3 — Codes de statut & erreurs 🔢
> 🎯 **But** : renvoyer les bons statuts HTTP (Explicite).

Fais en sorte que : `store` renvoie **201 Created** ; `show` d'un id inexistant renvoie **404** ;
une validation échouée renvoie **422** avec les erreurs en JSON. Vérifie chaque cas.

---

## Exercice 4 — Authentification Sanctum 🔐
> 🎯 **But** : sécuriser l'API par **token**.

1. Installe/configure **Laravel Sanctum**.
2. Crée un endpoint `POST /api/login` qui vérifie les identifiants et retourne un **token**.
3. Protège les routes d'écriture avec le middleware `auth:sanctum`.

---

## Exercice 5 — Utilisateur authentifié 👤
> 🎯 **But** : lier les actions à l'utilisateur du token.

À la création d'un article via l'API, associe automatiquement l'article à
`$request->user()` (l'utilisateur du token). Empêche un utilisateur de modifier l'article d'un
autre (403).

---

## Exercice 6 — Pagination & filtres 🌟
> 🎯 **But** : rendre l'API exploitable à grande échelle.

Ajoute la **pagination** (`paginate`) à `index`, et un **filtre** optionnel `?published=1`.
Assure-toi que la réponse paginée reste cohérente (métadonnées `meta`/`links`). Pourquoi ne
jamais renvoyer 10 000 lignes d'un coup ?

---

👉 Correction : [corriges.md](./corriges.md)
