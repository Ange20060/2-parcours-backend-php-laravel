# Leçon 5.1 — Le protocole HTTP

> 🎯 **Objectif** : comprendre ce qu'est **vraiment** le backend — recevoir une **requête HTTP**,
> renvoyer une **réponse HTTP**. C'est le socle de tout serveur web, et de toute API.

---

## 🔁 Le cycle requête / réponse

Le web fonctionne en **demande → réponse**. Un **client** (navigateur, appli mobile, `curl`)
envoie une **requête** ; le **serveur** (ton code PHP) renvoie une **réponse**.

```
   CLIENT  ──────── requête HTTP ────────▶  SERVEUR (ton PHP)
           ◀─────── réponse HTTP ────────
```
> 🧠 Le backend, c'est **essentiellement** : *recevoir une requête, la traiter, renvoyer une
> réponse*. Tout Laravel n'est qu'une façon élégante et organisée de faire ça.

---

## 🚪 Les méthodes HTTP (le « verbe »)

Chaque requête a une **méthode** qui exprime l'**intention** :

| Méthode | Intention | Exemple |
|---|---|---|
| **GET** | **Lire** une ressource | afficher la liste des articles |
| **POST** | **Créer** une ressource | soumettre un formulaire d'inscription |
| **PUT / PATCH** | **Modifier** une ressource | mettre à jour un profil |
| **DELETE** | **Supprimer** une ressource | supprimer un commentaire |

> 💡 Ces verbes sont le cœur des **API REST** (Niveau 10). Respecter leur sémantique rend une
> API **[explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)** et prévisible :
> un GET ne doit **jamais** modifier de données.

---

## 🏷️ L'URL et ses parties

```
https://api.exemple.fr/articles/42?tri=recent
└─┬─┘   └──────┬──────┘└────┬────┘└────┬─────┘
 schéma      domaine       chemin   query string
```
- **chemin** (`/articles/42`) : quelle ressource.
- **query string** (`?tri=recent`) : des paramètres optionnels (filtres, tri, pagination).

---

## 🔢 Les codes de statut (la « réponse en un nombre »)

Chaque réponse porte un **code** qui résume ce qui s'est passé. À connaître par familles :

| Famille | Sens | Exemples |
|:--:|---|---|
| **2xx** | Succès | **200** OK · **201** Created · **204** No Content |
| **3xx** | Redirection | 301 Moved · 302 Found |
| **4xx** | Erreur du **client** | **400** Bad Request · **401** Unauthorized · **403** Forbidden · **404** Not Found · **422** Unprocessable (validation) |
| **5xx** | Erreur du **serveur** | **500** Internal Server Error |

> 🎯 Renvoyer le **bon** code est essentiel : un client (ou un test) s'appuie dessus. Créer une
> ressource → **201**, validation échouée → **422**, non connecté → **401**, interdit → **403**.
> *(Rappel des corrections récentes : un endpoint qui répond 200 pour une erreur, c'est un bug.)*

---

## 📋 Les en-têtes (headers)

Des **métadonnées** accompagnent chaque requête/réponse : le type de contenu, l'authentification…

```
Content-Type: application/json      ← "je t'envoie du JSON"
Authorization: Bearer eyJhbGci...   ← le token d'authentification
Accept: application/json            ← "je veux une réponse en JSON"
```

---

## 🖥️ Lancer un serveur PHP local

PHP a un serveur de développement intégré :
```bash
php -S localhost:8000
```
Puis ouvre `http://localhost:8000`. Chaque fichier `.php` du dossier répond aux requêtes.
Un point d'entrée minimal :
```php
<?php
declare(strict_types=1);

echo "Bonjour depuis le serveur PHP !";   // le corps de la réponse
```

> 🔎 Regarde la **requête réelle** : ouvre les **outils de développement** du navigateur (`F12`)
> → onglet **Réseau (Network)**. Tu y vois la méthode, l'URL, le code de statut, les en-têtes.

---

## 🔎 À toi de chercher

> 1. Différence entre **PUT** et **PATCH** (remplacer entièrement vs modifier partiellement).
> 2. Que signifie qu'une méthode est **idempotente** ? (GET, PUT, DELETE le sont ; POST non.)
> 3. Cherche la différence entre **401 Unauthorized** et **403 Forbidden** — souvent confondues.

---

## 🎓 Ce qu'il faut retenir

- Le backend = **requête → traitement → réponse** HTTP.
- **Méthodes** : GET (lire), POST (créer), PUT/PATCH (modifier), DELETE (supprimer).
- **Codes de statut** : 2xx succès, 4xx erreur client, 5xx erreur serveur — renvoie **le bon**.
- Les **en-têtes** portent les métadonnées (`Content-Type`, `Authorization`).
- `php -S localhost:8000` lance un serveur de test.

👉 Leçon suivante : [Recevoir des données (GET / POST)](./02-donnees-get-post.md)
