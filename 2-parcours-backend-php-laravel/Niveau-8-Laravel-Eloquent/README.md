# 🔵 Niveau 8 — Eloquent ORM & Migrations

Gérer la base de données « à la Laravel » : décrire son schéma avec des **migrations**, et
manipuler les données comme des **objets** avec l'ORM **Eloquent**.

> 🎯 **Objectifs :** migrations (versionner le schéma) · modèles **Eloquent** · CRUD élégant ·
> **relations** (hasMany, belongsTo, belongsToMany) · **seeders** & **factories** · *mass
> assignment* et sécurité.

## 📖 Les leçons (dans l'ordre)
1. [Les migrations](./01-migrations.md)
2. [Les modèles Eloquent & le CRUD](./02-modeles-crud.md)
3. [Les relations entre modèles](./03-relations.md)
4. [Requêtes, factories & seeders](./04-requetes-performance.md) (dont l'eager loading anti-N+1)

Puis :
- 📝 [Les exercices](./exercices.md) — 6 exercices, chacun avec son **but précis** · ✅ [Corrigés](./corriges.md)

## 📐 Principes clés
Le modèle Eloquent applique le **[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** (le schéma vit
dans les migrations) et le pattern Active Record. Attention à garder les modèles **cohésifs**
(pas de logique métier lourde dedans → services).

👉 Suite : [Niveau 9 : Contrôleurs, Validation, Middleware](../Niveau-9-Laravel-Web/)
