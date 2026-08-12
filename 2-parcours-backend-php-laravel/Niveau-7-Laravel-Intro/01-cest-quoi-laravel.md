# Leçon 7.1 — C'est quoi Laravel, et pourquoi

> 🎯 **Objectif** : comprendre ce qu'est un **framework**, pourquoi Laravel domine le backend
> PHP, et installer ton premier projet. Tu vas retrouver **tout** ce que tu as appris — en mieux organisé.

---

## 🏗️ Un framework, c'est quoi ?

Aux niveaux 5 et 6, tu as fait **à la main** : lire la requête HTTP, router, valider, parler à
la base avec PDO, renvoyer du JSON. Ça marche, mais c'est **répétitif** et **risqué** (facile
d'oublier une requête préparée, un code de statut, un échappement).

Un **framework** est une **base de code prête à l'emploi** qui fournit tout ça, testé et
sécurisé, avec une **structure** claire. Tu écris seulement **ta** logique métier.

> 🧠 Analogie : construire une maison en fabriquant soi-même les briques, les tuyaux et
> l'électricité (PHP pur) **vs** partir d'une structure aux normes où tout est prévu (framework).

---

## 🌟 Pourquoi Laravel ?

**Laravel** est le framework PHP le plus populaire. Il apporte :
- un **routing** élégant (`Route::get(...)`),
- l'**ORM Eloquent** (manipuler la base comme des objets — fini le SQL à la main),
- la **validation**, l'**authentification**, les **API**, les **tests**… intégrés,
- **Artisan**, une ligne de commande qui **génère** le code pour toi,
- une immense **communauté** et une **documentation** excellente.

> 💡 Bonne nouvelle : Laravel **applique** les principes que tu connais déjà. Le comprendre, ce
> n'est pas repartir de zéro — c'est **reconnaître** MVC (SoC), l'injection de dépendances (SOLID),
> les requêtes préparées (Eloquent)… dans un habit professionnel.

---

## ⚙️ Prérequis

- **PHP 8.2+**, **Composer** (Niveau 1) — vérifie : `php --version`, `composer --version`.
- Une base de données : **SQLite** suffit pour débuter (un simple fichier, zéro config).

---

## ⬇️ Créer un projet Laravel

```bash
composer create-project laravel/laravel mon-app
cd mon-app
php artisan serve
```
Ouvre `http://127.0.0.1:8000` → la page d'accueil Laravel s'affiche. 🎉

> 🔎 Alternative : l'installeur `laravel new mon-app`. Cherche « installation laravel » pour ta
> plateforme (et l'option **Laravel Sail** si tu utilises Docker).

---

## ⚡ Artisan : ton assistant en ligne de commande

**Artisan** est l'outil CLI de Laravel. Il **génère** du code et automatise les tâches :

```bash
php artisan list                 # toutes les commandes disponibles
php artisan make:controller ...  # générer un contrôleur
php artisan make:model ...       # générer un modèle
php artisan migrate              # créer les tables en base
php artisan tinker               # une console interactive dans le contexte de l'app
```
> 💡 `php artisan make:...` t'évite d'écrire le squelette à la main → gain de temps, cohérence,
> et **[DRY](../Principes-Genie-Logiciel/01-DRY.md)** à l'échelle du projet.

---

## 🔧 La configuration : le fichier `.env`

Laravel range la config **par environnement** dans un fichier **`.env`** (base de données, clés,
URL…). Il n'est **jamais committé** (secrets) — c'est le **[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)**
de ta configuration.

```env
APP_NAME=MonApp
APP_ENV=local
DB_CONNECTION=sqlite
```
Pour SQLite : crée un fichier `database/database.sqlite` et mets `DB_CONNECTION=sqlite`.

---

## 🔎 À toi de chercher

> 1. Crée un projet Laravel et lance-le. Explore la page d'accueil et le fichier `.env`.
> 2. Cherche la différence entre `.env` et les fichiers du dossier `config/`.
> 3. Lance `php artisan tinker` et tape `now()`, `1 + 1`, `str('bonjour')->upper()`. Que fait Tinker ?

---

## 🎓 Ce qu'il faut retenir

- Un **framework** fournit une base structurée, testée et sécurisée : tu écris ta **logique métier**.
- **Laravel** = routing, **Eloquent**, validation, auth, API, tests, **Artisan** — et une grande communauté.
- On crée un projet avec `composer create-project laravel/laravel`, on le lance avec `php artisan serve`.
- **Artisan** génère du code (`make:...`) ; le **`.env`** stocke la config par environnement (jamais committé).

👉 Leçon suivante : [L'architecture MVC & le cycle d'une requête](./02-mvc-cycle-requete.md)
