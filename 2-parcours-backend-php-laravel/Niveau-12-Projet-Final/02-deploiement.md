# Leçon 12.2 — Du projet au déploiement

> 🎯 **Objectif** : préparer ton application pour la **production**, comprendre les étapes d'un
> **déploiement**, et faire le pont vers le **DevOps**. Un logiciel n'existe vraiment que quand il
> **tourne pour de vrais utilisateurs**.

---

## ✅ Avant de déployer : la checklist de production

- [ ] **Tests verts** : `php artisan test` passe **entièrement** (et les tests s'exécutent bien !).
- [ ] **Style** : `./vendor/bin/pint` passé (code homogène).
- [ ] **Secrets hors du code** : rien de sensible committé ; `.env` **jamais** dans Git.
- [ ] **`APP_DEBUG=false`** en production (ne jamais exposer les traces d'erreur).
- [ ] **`APP_ENV=production`**, `APP_KEY` généré (`php artisan key:generate`).
- [ ] **Migrations** prêtes (`php artisan migrate --force` au déploiement).
- [ ] **Autorisations** en place (Policies) et endpoints protégés.
- [ ] **Base de données de prod** configurée (MySQL/PostgreSQL, pas SQLite en général).

---

## ⚙️ Les optimisations de production

En production, on **met en cache** la configuration, les routes et les vues pour la performance :

```bash
composer install --optimize-autoloader --no-dev   # sans les dépendances de dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force                        # --force : pas de confirmation interactive
```
> ⚠️ Après chaque déploiement, on **régénère** ces caches (et on les vide en dev avec
> `php artisan optimize:clear`).

---

## 🚀 Les grandes étapes d'un déploiement

```
1. git push                     → le code arrive sur le serveur (ou via CI/CD)
2. composer install --no-dev    → installer les dépendances de prod
3. php artisan migrate --force  → mettre à jour la base
4. config/route/view:cache      → optimiser
5. redémarrer les workers       → files d'attente, etc.
```
Fait **à la main** en SSH, c'est risqué et non reproductible. La bonne pratique : un **pipeline
CI/CD** qui, à chaque push, **teste** puis **déploie** automatiquement.

---

## 🌉 Le pont vers le DevOps

Tu as maintenant une application backend complète. L'étape la plus rentable pour ta carrière :
**la déployer comme un pro**.

- **Conteneuriser** avec Docker (PHP-FPM + Nginx + base de données).
- Un **pipeline GitHub Actions** qui lance `php artisan test` puis déploie.
- La mettre **en ligne**, **supervisée** (logs, métriques).

> 🎯 Il existe un **[parcours DevOps](../../3-parcours-devops/)** dédié, avec un **projet final**
> qui fait exactement ça — sauf qu'ici, **l'application à déployer, c'est la tienne**. Backend
> **+** ops = un profil très recherché.

> 💡 Réflexe déjà vu : **jamais de chemin absolu** ni de secret codé en dur (le bug de la copie
> Mini-CRM). Une app qui marche « sur ma machine » mais nulle part ailleurs n'est **pas** livrable.

---

## 🏁 Le projet final

Il est temps de **tout réunir**. Le cahier des charges et les étapes guidées sont dans les
**[exercices du niveau](./exercices.md)** : une **API complète** (auth Sanctum, CRUD, relations,
validation, policies, resources, tests verts, code propre, README, dépôt Git soigné).

### Idées de projet
- **API de gestion de tâches d'équipe** (projets, membres, tâches, commentaires) — celle que tes
  prédécesseurs ont tenté ; fais-la **irréprochable**.
- **API de mini-boutique** (produits, panier, commandes, stock, transactions).
- **API de blog / CMS** (articles, catégories, rôles, modération).

### Ce qui fait la différence (leçons des corrections)
- Les **tests s'exécutent** et sont **verts** (nom de fichier = nom de classe !).
- **Toutes** les routes sont protégées **et** autorisées (Policies, pas juste `auth`).
- Les endpoints répondent avec **les bons codes** (201/204/401/403/404/422).
- **Aucun** chemin absolu, **aucun** secret dans Git.
- Un **README** clair pour installer et lancer le projet.

---

## 🔎 À toi de chercher

> 1. Les **queues** (files d'attente) : envoyer un email « en arrière-plan » sans ralentir la réponse.
> 2. La **planification de tâches** (scheduler) : exécuter du code à heure fixe (nettoyage, rapports).
> 3. Où héberger une app Laravel : **Laravel Forge**, un VPS, ou via **Docker** (parcours DevOps).

---

## 🎓 Ce qu'il faut retenir

- Avant la prod : **tests verts**, style, secrets hors du code, `APP_DEBUG=false`, migrations prêtes.
- Optimise (`config/route/view:cache`) et déploie via un **pipeline CI/CD**, pas à la main.
- Le déploiement pro passe par le **DevOps** (Docker + CI/CD) — un parcours dédié t'attend.
- Le **projet final** réunit tout : fais-le **complet, testé, sécurisé, propre et déployable**.

---

## 🎓 Félicitations — fin du parcours Backend

Tu es parti des **bases de PHP** et tu tiens maintenant tout le métier : POO, principes du génie
logiciel, SQL, **Laravel**, API sécurisées, **tests**, architecture et déploiement. Tu n'écris
plus du code qui « marche » — tu livres du **logiciel maintenable, testé et propre**.

### Et après ?
- Approfondir : **queues/Redis**, événements, **temps réel** (Reverb/WebSockets), multi-tenant.
- **Déployer** ton projet via le **[parcours DevOps](../../3-parcours-devops/)**.
- Contribuer à l'**open source** Laravel/PHP.
- Continuer à appliquer la **[règle du Boy Scout](../Principes-Genie-Logiciel/10-boy-scout.md)** —
  toute une carrière. 💚
