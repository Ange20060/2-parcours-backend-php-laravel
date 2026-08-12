# 📝 Niveau 12 — Projet final (étapes guidées)

Un **seul grand projet** : une **API backend Laravel complète**, bien architecturée, testée et
publiée. Chaque étape a un **🎯 But** et un **livrable** à valider avant de continuer. Guide de
référence : [corriges.md](./corriges.md).

> 🧭 Ce projet mobilise **tout** le parcours : PHP, POO, principes, SQL, Laravel, API, tests.
> C'est la pièce maîtresse de ton **portfolio de développeur backend**.

**Sujet au choix** (ou propose le tien) :
- **API de gestion de tâches d'équipe** (recommandé) — utilisateurs, projets, tâches, commentaires.
- API de mini-boutique (produits, panier, commandes, stock).
- API de blog/CMS (articles, catégories, modération).

La suite suppose l'**API de tâches d'équipe**.

---

## Étape 1 — Cahier des charges & modélisation 📐
> 🎯 **But** : concevoir avant de coder (SSOT du schéma).

1. Liste les **entités** et leurs **relations** : `User`, `Project`, `Task`, `Comment`.
2. Dessine le **schéma relationnel** (clés, relations 1-N / N-N).
3. Définis les **endpoints** de l'API (verbe + URL + rôle).
**Livrable** : un schéma + une liste d'endpoints dans le `README.md`. ✅

---

## Étape 2 — Fondations Laravel 🏗️
> 🎯 **But** : mettre en place migrations, modèles et relations.

Crée les migrations, les modèles Eloquent avec leurs **relations**, les **factories** et un
**seeder**. `php artisan migrate --seed` doit remplir une base de démonstration.
**Livrable** : base peuplée de données de test cohérentes. ✅

---

## Étape 3 — API RESTful 🌐
> 🎯 **But** : exposer les ressources proprement.

Contrôleurs d'API, **API Resources** (format JSON), **Form Requests** (validation), bons
**codes de statut**. Contrôleurs **fins**, logique métier dans des **services**.
**Livrable** : endpoints CRUD fonctionnels, testés à la main (curl/Postman). ✅

---

## Étape 4 — Authentification & autorisation 🔐
> 🎯 **But** : sécuriser l'API.

**Sanctum** pour les tokens ; les actions sont liées à l'utilisateur connecté ; un utilisateur
ne peut modifier que **ses** ressources (policies/middleware). Mots de passe **hachés**.
**Livrable** : routes protégées, autorisations vérifiées. ✅

---

## Étape 5 — Tests automatisés ✅
> 🎯 **But** : prouver que ça marche et le garder vrai.

Écris des tests **fonctionnels** (endpoints principaux, validation, auth) et **unitaires**
(services). `php artisan test` doit **tout** passer au vert.
**Livrable** : une suite de tests verte, couvrant les cas clés. ✅

---

## Étape 6 — Qualité & revue de principes 🔍
> 🎯 **But** : passer le code au crible des principes du parcours.

Relis ton code avec la [checklist des principes](./corriges.md) : DRY, KISS, SoC, SOLID,
Fail Fast… Applique la **[règle du Boy Scout](../Principes-Genie-Logiciel/10-boy-scout.md)**.
**Livrable** : un code que tu peux **défendre** principe par principe. ✅

---

## Étape 7 — Documentation & livraison 🚀
> 🎯 **But** : livrer comme un pro.

`README` complet (installation, endpoints, exemples de requêtes), dépôt **GitHub** propre
(commits clairs), et **déploiement** en ligne (bonus). Prépare une **démo**.
**Livrable** : projet publié, documenté, démontrable. ✅

---

👉 Guide de référence & grille : [corriges.md](./corriges.md)
