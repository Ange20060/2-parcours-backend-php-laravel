# Leçon 11.2 — Tester une API Laravel

> 🎯 **Objectif** : écrire des **tests de fonctionnalité** qui appellent tes endpoints comme un
> vrai client, et vérifient le **statut**, le **JSON** et la **base de données**. Exactement les
> tests qui auraient attrapé les bugs de tes stagiaires.

---

## 🗄️ Une base de test propre à chaque test

Le trait **`RefreshDatabase`** recrée une base **vide** avant chaque test → chaque test part
d'un état connu, sans interférence.

```php
<?php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);   // Pest
// en PHPUnit : use RefreshDatabase; dans la classe
```

---

## 📡 Appeler un endpoint : les assertions HTTP

Laravel fournit des méthodes pour simuler des requêtes JSON et vérifier la réponse :

```php
<?php
// tests/Feature/ArticleTest.php (Pest)
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('la liste des articles répond 200', function () {
    Article::factory()->count(3)->create();

    $this->getJson('/api/articles')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
});
```
| Méthode | Rôle |
|---|---|
| `getJson`, `postJson`, `putJson`, `deleteJson` | envoyer une requête JSON |
| `assertStatus(201)` / `assertCreated()` / `assertNoContent()` | vérifier le code |
| `assertJson([...])` / `assertJsonPath('data.titre', 'X')` | vérifier le contenu |
| `assertJsonCount(3, 'data')` | vérifier le nombre d'éléments |
| `assertJsonValidationErrors(['titre'])` | vérifier une erreur de validation (422) |

---

## 🔐 Tester avec un utilisateur authentifié

`actingAs($user)` simule un utilisateur connecté (via Sanctum pour une API) :

```php
<?php
test('un utilisateur authentifié peut créer un article', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/articles', [
            'titre'   => 'Mon article',
            'contenu' => 'Contenu...',
        ])
        ->assertStatus(201)
        ->assertJsonPath('data.titre', 'Mon article');

    // Vérifier aussi la BASE de données
    $this->assertDatabaseHas('articles', [
        'titre'   => 'Mon article',
        'user_id' => $user->id,
    ]);
});
```
| Assertion base | Rôle |
|---|---|
| `assertDatabaseHas('table', [...])` | la ligne existe |
| `assertDatabaseMissing('table', [...])` | la ligne n'existe pas |
| `assertDatabaseCount('table', 3)` | nombre de lignes |

---

## 🚫 Tester ce qui doit ÉCHOUER (le plus important)

Un bon test suite vérifie aussi les **refus** — c'est **exactement** ce que les copies de
stagiaires ne faisaient pas :

```php
<?php
test('un invité reçoit 401', function () {
    $this->postJson('/api/articles', [])->assertStatus(401);   // non authentifié
});

test('un article sans titre est refusé (422)', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->postJson('/api/articles', ['contenu' => 'sans titre'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['titre']);
});

test('on ne peut pas modifier l\'article d\'un autre (403)', function () {
    $auteur = User::factory()->create();
    $autre  = User::factory()->create();
    $article = Article::factory()->for($auteur)->create();

    $this->actingAs($autre)
        ->putJson("/api/articles/{$article->id}", ['titre' => 'pirate'])
        ->assertStatus(403);                                   // la Policy protège
});
```
> 🎯 **Ces trois tests** auraient attrapé les failles des copies : endpoint non protégé (401),
> validation contournée (422), et **ownership** manquant (403). Tester les **cas négatifs** est
> aussi important que les cas passants.

---

## 🔎 À toi de chercher

> 1. `assertJsonStructure([...])` : vérifier la **forme** du JSON sans se soucier des valeurs.
> 2. `Sanctum::actingAs($user, ['*'])` : tester avec des **abilities** de token précises.
> 3. Comment tester un **envoi d'email** ou un **job** sans réellement les exécuter (`Mail::fake()`, `Queue::fake()`).

---

## 🎓 Ce qu'il faut retenir

- **`RefreshDatabase`** = base propre à chaque test ; **factories** pour les données.
- Simuler des requêtes : `getJson/postJson/...` + assertions `assertStatus`, `assertJsonPath`, `assertJsonValidationErrors`.
- Vérifier la base : `assertDatabaseHas/Missing/Count`.
- **`actingAs($user)`** pour l'authentification.
- **Teste les refus** (401/403/422) autant que les succès — c'est là que se cachent les failles.

👉 Leçon suivante : [Le cycle TDD & les doublures](./03-tdd-mocks.md)
