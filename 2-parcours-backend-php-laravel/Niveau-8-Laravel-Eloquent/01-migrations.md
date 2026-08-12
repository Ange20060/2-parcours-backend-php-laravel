# Leçon 8.1 — Les migrations

> 🎯 **Objectif** : décrire la structure de ta base **en code versionné** avec les **migrations**,
> au lieu de créer les tables à la main. Ton schéma devient reproductible et suivi par Git.

---

## 🤔 Le problème des tables « à la main »

Créer les tables en cliquant dans un outil ou en tapant du SQL une fois, c'est **non
reproductible** : un collègue (ou le serveur de prod) n'a aucun moyen de recréer **exactement**
le même schéma. Et l'historique des changements est perdu.

Une **migration** est un **fichier PHP** qui décrit une modification du schéma (créer une table,
ajouter une colonne…). Versionnée dans Git, elle rejoue le schéma **à l'identique** partout —
c'est le **[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** de la structure de la base.

---

## 🏗️ Créer une migration

```bash
php artisan make:migration create_articles_table
```
Cela génère un fichier dans `database/migrations/` :

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();                                 // clé primaire auto
            $table->string('titre');
            $table->text('contenu');
            $table->boolean('published')->default(false);
            $table->timestamps();                         // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');                 // annuler la migration
    }
};
```
- **`up()`** : ce que fait la migration (créer la table).
- **`down()`** : comment l'**annuler** (supprimer la table) → réversibilité.

---

## 🧱 Les types de colonnes (Blueprint)

| Méthode | Type SQL | Pour |
|---|---|---|
| `$table->id()` | clé primaire auto-incrémentée | l'identifiant |
| `$table->string('nom')` | VARCHAR(255) | texte court |
| `$table->text('contenu')` | TEXT | texte long |
| `$table->integer('qte')` | INTEGER | entier |
| `$table->decimal('prix', 8, 2)` | DECIMAL | montant précis |
| `$table->boolean('actif')` | BOOLEAN | vrai/faux |
| `$table->timestamp('lu_le')` | TIMESTAMP | date/heure |
| `$table->timestamps()` | 2 colonnes | `created_at` / `updated_at` (auto) |

Modificateurs utiles : `->nullable()` (facultatif), `->default(valeur)`, `->unique()`.

---

## ▶️ Lancer et gérer les migrations

```bash
php artisan migrate            # applique les migrations en attente
php artisan migrate:rollback   # annule le dernier lot (appelle les down())
php artisan migrate:fresh      # supprime TOUT et re-migre (dev uniquement !)
php artisan migrate:status     # voir ce qui est appliqué
```
> ⚠️ **`migrate:fresh` efface toutes les données** : jamais en production. En dev, c'est pratique
> pour repartir d'une base propre.

---

## 🔗 Clés étrangères (les relations en base)

```php
<?php
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('titre');
    $table->foreignId('user_id')            // la clé étrangère
          ->constrained()                    // référence users(id) automatiquement
          ->cascadeOnDelete();               // supprimer l'user → supprime ses articles
    $table->timestamps();
});
```
`foreignId('user_id')->constrained()` crée la colonne **et** la contrainte de clé étrangère vers
la table `users`. `cascadeOnDelete()` gère la suppression en cascade.

---

## 🔄 Faire évoluer un schéma existant

On ne modifie **jamais** une vieille migration déjà appliquée : on en crée une **nouvelle**.

```bash
php artisan make:migration add_slug_to_articles_table --table=articles
```
```php
<?php
public function up(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->string('slug')->unique()->after('titre');
    });
}
```
> 🧠 Chaque changement = une nouvelle migration versionnée. L'historique complet du schéma vit
> dans Git, rejouable dans l'ordre. C'est reproductible et traçable.

---

## 🔎 À toi de chercher

> 1. Différence entre `migrate:fresh` et `migrate:refresh`.
> 2. Cherche `->index()` : à quoi sert un **index** et sur quelles colonnes en poser.
> 3. Comment définir une **table pivot** (N-N) avec une migration (ex : `create_project_user_table`).

---

## 🎓 Ce qu'il faut retenir

- Une **migration** décrit le schéma **en code versionné** (`up()` applique, `down()` annule) → SSOT.
- On génère avec `make:migration`, on applique avec `php artisan migrate`.
- **Blueprint** : `id()`, `string()`, `text()`, `boolean()`, `timestamps()`, `foreignId()->constrained()`.
- Pour **faire évoluer** un schéma : une **nouvelle** migration, jamais modifier l'ancienne.

👉 Leçon suivante : [Les modèles Eloquent & le CRUD](./02-modeles-crud.md)
