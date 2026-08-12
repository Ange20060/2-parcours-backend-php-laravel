# Leçon 6.3 — Les jointures

> 🎯 **Objectif** : combiner les données de **plusieurs tables** en une seule requête avec les
> **jointures** (`JOIN`) — l'opération qui donne toute sa puissance au relationnel.

---

## 🤔 Le problème

Les données sont **séparées** en tables (normalisation). Mais on veut souvent les **rassembler** :
« la liste des articles **avec le nom de leur auteur** » — or le nom est dans `users`, pas dans
`articles`. La jointure relie les deux via la **clé étrangère**.

---

## 🔗 `INNER JOIN` — les lignes qui correspondent des deux côtés

```sql
SELECT articles.titre, users.nom AS auteur
FROM articles
JOIN users ON users.id = articles.user_id;
```
- `JOIN users ON ...` : relie `articles` à `users`.
- `ON users.id = articles.user_id` : **la condition de liaison** (la FK rencontre la PK).
- `AS auteur` : renomme la colonne dans le résultat.

Résultat :
```
titre          │ auteur
───────────────┼────────
Mon premier    │ Marie
Recette...     │ Marie
Voyage...      │ Paul
```
> 💡 `INNER JOIN` ne garde que les lignes qui ont une correspondance **des deux côtés**. Un
> article sans `user_id` valide n'apparaîtrait pas.

---

## ⬅️ `LEFT JOIN` — garder toutes les lignes de gauche

Pour lister **tous** les utilisateurs, **même** ceux **sans** article :

```sql
SELECT users.nom, COUNT(articles.id) AS nb_articles
FROM users
LEFT JOIN articles ON articles.user_id = users.id
GROUP BY users.id;
```
```
nom    │ nb_articles
───────┼────────────
Marie  │ 2
Paul   │ 1
Sofia  │ 0            ← apparaît grâce au LEFT JOIN (0 article)
```
> 🧠 **INNER vs LEFT** : `INNER` = l'intersection (correspondance des deux côtés) ; `LEFT` = tout
> ce qui est à gauche, complété par la droite quand il y a correspondance (sinon `NULL`).

---

## 🔀 Jointures multiples

On enchaîne les `JOIN` pour relier trois tables (articles + auteurs + commentaires) :

```sql
SELECT articles.titre, users.nom AS auteur, COUNT(commentaires.id) AS nb_commentaires
FROM articles
JOIN users ON users.id = articles.user_id
LEFT JOIN commentaires ON commentaires.article_id = articles.id
GROUP BY articles.id;
```

---

## 🏷️ Les alias (pour la lisibilité)

On raccourcit les noms de tables :

```sql
SELECT a.titre, u.nom AS auteur
FROM articles AS a
JOIN users AS u ON u.id = a.user_id;
```

---

## ⚡ Un mot sur la performance : le problème N+1

Piège classique côté application : récupérer les articles, **puis** faire **une requête par
article** pour obtenir l'auteur → des centaines de requêtes (le « **N+1** »). Une **jointure**
(ou l'*eager loading* de Laravel, Niveau 8) récupère tout en **une** requête. Garde-le en tête.

---

## 🔎 À toi de chercher

> 1. Différence entre `INNER JOIN`, `LEFT JOIN`, `RIGHT JOIN` et `FULL JOIN` (un schéma de
>    « diagrammes de Venn des jointures » aide beaucoup).
> 2. Qu'est-ce qu'une **sous-requête** (une requête dans une requête) ? Un exemple simple.
> 3. Cherche ce qu'est un **INDEX** en base de données et pourquoi il accélère les `JOIN` et les
>    `WHERE` sur de grosses tables.

---

## 🎓 Ce qu'il faut retenir

- Une **jointure** relie plusieurs tables via `JOIN ... ON fk = pk`.
- **`INNER JOIN`** = correspondances des deux côtés ; **`LEFT JOIN`** = tout à gauche + la droite si dispo.
- On enchaîne les jointures pour relier 3+ tables ; les **alias** rendent le SQL lisible.
- Une jointure évite le **N+1** (des centaines de petites requêtes).

👉 Leçon suivante : [PDO — accéder à la base depuis PHP](./04-pdo.md)
