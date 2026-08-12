# 🟡 Niveau 6 — Bases de données & SQL

Le cœur de tout backend : **stocker et interroger des données** de façon fiable. On apprend la
modélisation relationnelle, le **SQL**, et l'accès sécurisé depuis PHP avec **PDO**.

> 🎯 **Objectifs :** modélisation relationnelle (tables, clés primaires/étrangères,
> normalisation) · SQL (`SELECT`, `INSERT`, `UPDATE`, `DELETE`, `JOIN`, agrégats) · **PDO** et
> **requêtes préparées** (contre l'injection SQL) · transactions.

## 📖 Les leçons (dans l'ordre)
1. [Modéliser des données](./01-modelisation.md) — entités, relations (1-N, N-N), normalisation
2. [Le SQL : créer les tables et le CRUD](./02-sql-crud.md)
3. [Les jointures](./03-jointures.md) (+ agrégats `GROUP BY`, `COUNT`, `SUM`)
4. [PDO : accéder à la base depuis PHP](./04-pdo.md) (requêtes préparées, transactions)
5. [Le pattern Repository](./05-repository.md)

Puis :
- 📝 [Les exercices](./exercices.md) — 7 exercices, chacun avec son **but précis** · ✅ [Corrigés](./corriges.md)

## 📐 Principes clés
**[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** (normalisation : une donnée = un endroit) ·
**[Faible couplage](../Principes-Genie-Logiciel/06-cohesion-couplage.md)** (pattern Repository).

👉 Suite : [Niveau 7 : Introduction à Laravel](../Niveau-7-Laravel-Intro/)
