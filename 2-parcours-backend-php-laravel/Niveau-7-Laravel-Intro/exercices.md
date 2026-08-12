# 📝 Niveau 7 — Exercices (Introduction à Laravel)

Prérequis : un projet Laravel créé (`composer create-project laravel/laravel blog` ou
`laravel new blog`), lancé avec `php artisan serve`. **🎯 But** à chaque exercice. Corrigés :
[corriges.md](./corriges.md).

> 🎯 **Exigence** : garde les **contrôleurs fins** et observe comment Laravel applique les
> principes que tu connais (MVC = [SoC](../Principes-Genie-Logiciel/04-SoC.md)).

---

## Exercice 1 — Explorer la structure 🗺️
> 🎯 **But** : comprendre l'organisation d'un projet Laravel.

Explore et note le rôle de : `routes/web.php`, `app/Http/Controllers/`, `resources/views/`,
`app/Models/`, `.env`, `config/`. En quoi cette organisation reflète-t-elle la **séparation
des responsabilités** ?

---

## Exercice 2 — Première route 🛣️
> 🎯 **But** : définir une route qui retourne une réponse.

Dans `routes/web.php`, crée une route `GET /bonjour` qui retourne « Bonjour Laravel ». Puis
une route `GET /bonjour/{nom}` qui retourne « Bonjour {nom} » en utilisant le paramètre.

---

## Exercice 3 — Un contrôleur 🎛️
> 🎯 **But** : déplacer la logique de la route vers un **contrôleur** (Artisan).

1. Génère un contrôleur : `php artisan make:controller PageController`.
2. Ajoute une méthode `accueil()` qui retourne un texte.
3. Branche la route `GET /` sur `PageController@accueil`.

---

## Exercice 4 — Une vue Blade 🎨
> 🎯 **But** : séparer l'affichage (la **Vue** du MVC) avec Blade.

1. Crée `resources/views/accueil.blade.php`.
2. Passe-lui une variable `$nom` depuis le contrôleur (`return view('accueil', ['nom' => 'Marie'])`).
3. Affiche-la dans la vue avec `{{ $nom }}`. Pourquoi `{{ }}` est-il sûr contre le XSS ?

---

## Exercice 5 — Artisan & Tinker 🛠️
> 🎯 **But** : découvrir la ligne de commande de Laravel.

1. Liste toutes les commandes : `php artisan list`.
2. Ouvre `php artisan tinker` et exécute quelques expressions PHP (ex : `now()`, `1+1`).
3. Cherche 3 commandes `make:` utiles et note leur rôle.

---

## Exercice 6 — Injection de dépendances 🌟
> 🎯 **But** : voir le **conteneur de services** (inversion de dépendances) en action.

1. Crée une classe `App\Services\SalutationService` avec une méthode `saluer(string $nom): string`.
2. **Type-hint** ce service dans une méthode de contrôleur : Laravel l'**injecte automatiquement**.
3. Explique le lien avec le **D de [SOLID](../Principes-Genie-Logiciel/11-SOLID.md)** : le
   contrôleur dépend d'une classe qu'il ne construit pas lui-même.

---

👉 Correction : [corriges.md](./corriges.md)
