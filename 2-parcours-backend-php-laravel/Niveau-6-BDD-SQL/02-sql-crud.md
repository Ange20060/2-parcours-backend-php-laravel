# Leçon 6.2 — Le SQL : créer les tables et le CRUD

> 🎯 **Objectif** : écrire du **SQL** — créer des tables et effectuer les 4 opérations de base
> **CRUD** : Create (`INSERT`), Read (`SELECT`), Update (`UPDATE`), Delete (`DELETE`).

---

## 🛠️ SGBD et outils

Un **SGBD** (Système de Gestion de Base de Données) exécute le SQL : **MySQL**, **PostgreSQL**,
**SQLite**… On commencera avec **SQLite** (un simple fichier, zéro installation) ; le SQL est
quasi identique partout.

> 💡 Pour t'entraîner sans rien installer : un fichier SQLite + un outil comme **DB Browser for
> SQLite**, ou directement en PHP via PDO (leçon 6.4).

---

## 🏗️ Créer une table : `CREATE TABLE`

```sql
CREATE TABLE users (
    id    INTEGER PRIMARY KEY AUTOINCREMENT,
    nom   TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE
);

CREATE TABLE articles (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    titre    TEXT NOT NULL,
    contenu  TEXT NOT NULL,
    user_id  INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```
- `PRIMARY KEY AUTOINCREMENT` : l'`id` se remplit tout seul.
- `NOT NULL` : le champ est **obligatoire** (Fail Fast au niveau base).
- `UNIQUE` : pas deux fois le même email.
- `FOREIGN KEY ... REFERENCES` : la relation vers `users`.

---

## ➕ Create — `INSERT`

```sql
INSERT INTO users (nom, email) VALUES ('Marie', 'marie@x.fr');
INSERT INTO articles (titre, contenu, user_id) VALUES ('Mon premier', 'Bonjour !', 1);
```

## 👁️ Read — `SELECT` (l'opération reine)

```sql
SELECT * FROM users;                          -- toutes les colonnes, toutes les lignes
SELECT nom, email FROM users;                 -- certaines colonnes
SELECT * FROM users WHERE id = 1;             -- filtrer
SELECT * FROM articles WHERE user_id = 1;     -- les articles de Marie
SELECT * FROM users ORDER BY nom ASC;         -- trier
SELECT * FROM articles LIMIT 10;              -- limiter (pagination)
```

### La clause `WHERE` (filtrer)
```sql
SELECT * FROM articles WHERE titre LIKE '%php%';   -- contient "php"
SELECT * FROM users WHERE id IN (1, 2, 3);
SELECT * FROM articles WHERE user_id = 1 AND titre LIKE '%recette%';
```
| Opérateur | Sens |
|---|---|
| `=`, `!=`, `<`, `>`, `<=`, `>=` | comparaisons |
| `AND`, `OR`, `NOT` | combiner des conditions |
| `LIKE '%mot%'` | contient (recherche texte) |
| `IN (...)` | dans une liste |
| `IS NULL` / `IS NOT NULL` | valeur absente ou présente |

## ✏️ Update — `UPDATE`

```sql
UPDATE users SET email = 'nouvelle@x.fr' WHERE id = 1;
```
> ⚠️ **DANGER** : un `UPDATE` **sans `WHERE`** modifie **TOUTES** les lignes ! Vérifie toujours
> ta clause `WHERE` avant d'exécuter. Même avertissement pour `DELETE`.

## 🗑️ Delete — `DELETE`

```sql
DELETE FROM articles WHERE id = 10;
```

---

## 📊 Agréger : compter, sommer, grouper

```sql
SELECT COUNT(*) FROM articles;                      -- nombre total d'articles
SELECT SUM(prix) FROM commandes;                    -- somme
SELECT AVG(note) FROM avis;                         -- moyenne

-- Compter les articles PAR utilisateur
SELECT user_id, COUNT(*) AS nb_articles
FROM articles
GROUP BY user_id;
```
| Fonction | Rôle |
|---|---|
| `COUNT(*)` | compter les lignes |
| `SUM`, `AVG`, `MIN`, `MAX` | somme, moyenne, min, max |
| `GROUP BY` | regrouper avant d'agréger |

---

## 🔎 À toi de chercher

> 1. Différence entre `DELETE FROM t` et `TRUNCATE TABLE t`.
> 2. La clause **`HAVING`** : filtrer **après** un `GROUP BY` (vs `WHERE` qui filtre avant).
> 3. Cherche comment **`ON DELETE CASCADE`** supprime automatiquement les lignes liées (les
>    articles d'un user supprimé).

---

## 🎓 Ce qu'il faut retenir

- `CREATE TABLE` avec `PRIMARY KEY`, `NOT NULL`, `UNIQUE`, `FOREIGN KEY`.
- **CRUD** : `INSERT` (créer), `SELECT` (lire), `UPDATE` (modifier), `DELETE` (supprimer).
- `SELECT ... WHERE ... ORDER BY ... LIMIT` : filtrer, trier, paginer.
- **`UPDATE`/`DELETE` sans `WHERE` = catastrophe** : vérifie toujours.
- Agrégats : `COUNT`, `SUM`, `AVG` + `GROUP BY`.

👉 Leçon suivante : [Les jointures](./03-jointures.md)
