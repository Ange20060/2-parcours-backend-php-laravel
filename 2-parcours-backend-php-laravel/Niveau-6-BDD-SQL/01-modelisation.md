# Leçon 6.1 — Modéliser des données (relationnel)

> 🎯 **Objectif** : concevoir un schéma de base de données **relationnelle** propre — tables,
> colonnes, clés, relations — avant d'écrire la moindre requête. Un bon modèle évite 90 % des
> problèmes futurs.

---

## 🗃️ Vocabulaire de base

Une **base de données relationnelle** organise l'information en **tables** (comme des feuilles
Excel reliées entre elles) :

| Terme | Sens | Analogie |
|---|---|---|
| **Table** | Un type d'entité (`users`, `articles`) | Une feuille |
| **Colonne** (champ) | Un attribut (`nom`, `email`) | Une colonne Excel |
| **Ligne** (enregistrement) | Une entité concrète | Une ligne |
| **Clé primaire** (PK) | L'identifiant **unique** d'une ligne (souvent `id`) | Le numéro de ligne |
| **Clé étrangère** (FK) | Une référence vers la PK d'une **autre** table | Un lien |

---

## 🔑 La clé primaire

Chaque table a une **clé primaire** : une colonne qui identifie **de façon unique** chaque ligne.
Par convention, un `id` entier auto-incrémenté.

```
users
┌────┬─────────┬──────────────────┐
│ id │ nom     │ email            │
├────┼─────────┼──────────────────┤
│ 1  │ Marie   │ marie@x.fr       │   ← id = clé primaire
│ 2  │ Paul    │ paul@x.fr        │
└────┴─────────┴──────────────────┘
```

---

## 🔗 Les relations (le cœur du relationnel)

### Un-à-plusieurs (1 — N)
« Un utilisateur a **plusieurs** articles ; un article appartient à **un** utilisateur. »
On place une **clé étrangère** `user_id` dans la table `articles` :

```
users                      articles
┌────┬───────┐            ┌────┬────────────┬─────────┐
│ id │ nom   │            │ id │ titre      │ user_id │  ← FK vers users.id
├────┼───────┤            ├────┼────────────┼─────────┤
│ 1  │ Marie │◀───────────│ 10 │ Mon 1er... │ 1       │
│ 2  │ Paul  │            │ 11 │ Recette... │ 1       │
└────┴───────┘            │ 12 │ Voyage...  │ 2       │
                          └────┴────────────┴─────────┘
```

### Plusieurs-à-plusieurs (N — N)
« Un projet a plusieurs membres ; un utilisateur participe à plusieurs projets. » On crée une
**table de liaison** (pivot) `project_user` :

```
project_user
┌────────────┬─────────┐
│ project_id │ user_id │   ← chaque ligne = "cet utilisateur est dans ce projet"
├────────────┼─────────┤
│ 1          │ 2       │
│ 1          │ 5       │
│ 2          │ 2       │
└────────────┴─────────┘
```

> 💡 Ces trois formes (1-1, 1-N, N-N) suffisent à modéliser presque tout. Repère-les dans les
> énoncés : « **a plusieurs** » → 1-N ; « **plusieurs… plusieurs** » → N-N (table pivot).

---

## 🎯 La normalisation : une donnée, un seul endroit

**Normaliser**, c'est éviter de **recopier** une même information à plusieurs endroits. Contre-exemple :

```
❌ MAUVAIS : le nom de l'auteur recopié dans chaque article
articles
┌────┬───────────┬─────────────┬──────────────┐
│ id │ titre     │ auteur_nom  │ auteur_email │   ← si Marie change d'email,
│ 10 │ ...       │ Marie       │ marie@x.fr   │     il faut modifier PARTOUT
│ 11 │ ...       │ Marie       │ marie@x.fr   │
```
```
✅ BON : l'auteur vit UNE fois dans users, référencé par user_id
articles(id, titre, user_id) ──▶ users(id, nom, email)
```
Changer l'email de Marie = **une** modification, dans **une** ligne. C'est le principe
**[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** appliqué aux données : **une donnée = une source de vérité**.

---

## 🧩 Exemple : modéliser un mini-blog

- `users(id, nom, email)`
- `articles(id, titre, contenu, user_id → users.id)`
- `commentaires(id, contenu, article_id → articles.id, user_id → users.id)`

Relations : un user **a plusieurs** articles ; un article **a plusieurs** commentaires ; un
commentaire **appartient à** un article et à un user.

---

## 🔎 À toi de chercher

> 1. Cherche les **types de colonnes** SQL courants : `INTEGER`, `VARCHAR`, `TEXT`, `BOOLEAN`,
>    `DATETIME`, `DECIMAL`. Quand utiliser chacun ?
> 2. Que sont les contraintes **`NOT NULL`**, **`UNIQUE`**, **`DEFAULT`** ? À quoi servent-elles
>    (Fail Fast au niveau de la base) ?
> 3. Cherche les **3 premières formes normales** (1NF, 2NF, 3NF) — l'idée générale suffit pour l'instant.

---

## 🎓 Ce qu'il faut retenir

- Données = **tables** (colonnes/lignes) reliées par des **clés**.
- **Clé primaire** (PK) = identifiant unique ; **clé étrangère** (FK) = référence vers une autre table.
- Relations : **1-N** (FK dans la table « plusieurs ») et **N-N** (table **pivot**).
- **Normaliser** = ne pas recopier une donnée → **SSOT** (une donnée, un seul endroit).

👉 Leçon suivante : [Le SQL : CRUD](./02-sql-crud.md)
