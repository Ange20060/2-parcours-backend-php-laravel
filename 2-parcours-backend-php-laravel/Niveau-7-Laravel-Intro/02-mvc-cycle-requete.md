# Leçon 7.2 — L'architecture MVC & le cycle d'une requête

> 🎯 **Objectif** : comprendre l'organisation **MVC** de Laravel et le **trajet d'une requête**,
> du navigateur jusqu'à la réponse. C'est la carte mentale qui rend tout le reste logique.

---

## 🧩 MVC : Modèle — Vue — Contrôleur

Laravel sépare l'application en **trois responsabilités** (c'est la
**[séparation des responsabilités](../Principes-Genie-Logiciel/04-SoC.md)** en action) :

| Couche | Rôle | Exemple |
|---|---|---|
| **Modèle** (Model) | Les **données** et la logique métier associée | `App\Models\Article` |
| **Vue** (View) | L'**affichage** (HTML pour un humain) | `resources/views/...` (Blade) |
| **Contrôleur** (Controller) | **Orchestrer** : reçoit la requête, appelle le modèle, renvoie une réponse | `App\Http\Controllers\ArticleController` |

> 🧠 Chaque couche a **un** rôle : le contrôleur ne fait pas de SQL, la vue ne contient pas de
> logique métier, le modèle ne s'occupe pas de l'affichage. Un changement dans l'une n'affecte
> pas les autres → **faible couplage**.

---

## 🗺️ La structure d'un projet Laravel

```
mon-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/     ← les contrôleurs
│   │   ├── Requests/        ← la validation (Form Requests)
│   │   └── Middleware/      ← les filtres de requêtes
│   ├── Models/              ← les modèles Eloquent
│   └── Services/            ← (ta logique métier, à créer)
├── routes/
│   ├── web.php              ← les routes web (pages)
│   └── api.php              ← les routes d'API
├── resources/views/         ← les vues Blade
├── database/
│   ├── migrations/          ← la structure des tables (versionnée)
│   ├── factories/           ← données de test
│   └── seeders/             ← remplir la base
├── config/                  ← la configuration
└── .env                     ← les secrets / config d'environnement
```
> 💡 Cette structure est **conventionnelle** : tout projet Laravel range les choses au même
> endroit. Un développeur qui rejoint le projet est **immédiatement** à l'aise — c'est
> **[Explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)** par convention.

---

## 🔄 Le trajet d'une requête (le plus important)

Quand une requête arrive, elle traverse Laravel dans cet ordre :

```
1. Navigateur          →  GET /articles/42
2. routes/*.php        →  quelle route correspond ? quel contrôleur appeler ?
3. Middleware          →  filtres (authentifié ? etc.) — peut bloquer ici
4. Contrôleur          →  la méthode reçoit la requête
5. (Form Request)      →  validation des données entrantes
6. Modèle (Eloquent)   →  lit/écrit en base
7. Réponse             →  une Vue (HTML) OU une Resource (JSON)
8. Middleware (retour) →  ajustements de la réponse
9. Navigateur          ←  la réponse
```

Compare avec ce que tu faisais **à la main** au Niveau 5 (lire `$_SERVER`, router avec des `if`,
valider, appeler PDO, `json_encode`) : Laravel fait **tout** ça de façon structurée et testée.
Tu écris juste les **étapes 4, 5, 6, 7** — ta logique.

---

## 🧭 Le point d'entrée unique

Toutes les requêtes passent par **un seul** fichier : `public/index.php`. C'est lui qui démarre
Laravel, lequel dispatch ensuite vers la bonne route. Tu n'y touches jamais — mais savoir qu'il
existe explique comment « tout » arrive au même endroit.

---

## 🔎 À toi de chercher

> 1. Ouvre un projet Laravel et **retrouve** chaque dossier du schéma ci-dessus. À quoi sert `bootstrap/` ?
> 2. Cherche « laravel request lifecycle » (le cycle de vie d'une requête) — la doc officielle a un schéma.
> 3. Différence entre `routes/web.php` et `routes/api.php` (sessions/CSRF vs stateless/token).

---

## 🎓 Ce qu'il faut retenir

- **MVC** = **Modèle** (données), **Vue** (affichage), **Contrôleur** (orchestration) → SoC.
- La structure Laravel est **conventionnelle** : chaque chose a sa place attitrée.
- Une requête traverse : **route → middleware → contrôleur → (validation) → modèle → réponse**.
- Tu écris ta **logique** ; Laravel gère la plomberie que tu faisais à la main au Niveau 5.

👉 Leçon suivante : [Routes et contrôleurs](./03-routes-controleurs.md)
