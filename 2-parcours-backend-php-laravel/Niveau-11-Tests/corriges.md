# ✅ Niveau 11 — Corrigés (Tests & TDD)

> ⚠️ Essaie d'abord. Exemples en **Pest** (syntaxe moderne de Laravel) ; l'équivalent PHPUnit
> est indiqué quand utile.

---

## Exercice 1 — Premier test unitaire
```php
<?php
// tests/Unit/CalculTest.php  (Pest)
it('calcule le TTC avec la TVA à 20%', function () {
    expect(calculerTTC(100.0))->toBe(120.0);
    expect(calculerTTC(0.0))->toBe(0.0);           // cas limite
});
```
Lancer : `php artisan test`. (En PHPUnit : `$this->assertSame(120.0, calculerTTC(100.0));`.)

## Exercice 2 — Tester une exception
```php
<?php
it('refuse un retrait supérieur au solde', function () {
    retirer(100.0, 500.0);
})->throws(SoldeInsuffisantException::class);
```
> Tester le **chemin d'échec** est aussi important que le chemin heureux : on vérifie que le
> **Fail Fast** fonctionne.

## Exercice 3 — Test de fonctionnalité (API)
```php
<?php
use App\Models\Article;
use function Pest\Laravel\getJson;
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('liste les articles', function () {
    Article::factory()->count(3)->create();

    getJson('/api/articles')
        ->assertStatus(200)
        ->assertJsonStructure(['data' => [['id', 'title', 'content']]]);
});
```
`RefreshDatabase` réinitialise la base entre chaque test → tests **isolés et reproductibles**.

## Exercice 4 — Tester la validation
```php
<?php
it('rejette un article sans titre', function () {
    postJson('/api/articles', ['content' => 'du contenu'])
        ->assertStatus(422)
        ->assertJsonValidationErrors('title');
});
```

## Exercice 5 — Tester l'authentification
```php
<?php
use App\Models\User;
use function Pest\Laravel\{postJson, actingAs};

it('refuse la création à un invité', function () {
    postJson('/api/articles', ['title' => 'X', 'content' => 'Y'])
        ->assertStatus(401);
});

it('autorise un utilisateur authentifié', function () {
    actingAs(User::factory()->create());

    postJson('/api/articles', ['title' => 'X', 'content' => 'Y'])
        ->assertStatus(201);
});
```

## Exercice 6 — TDD
**🔴 Le test d'abord** (échoue : la méthode n'existe pas encore) :
```php
<?php
it('est publiable si titre et contenu sont remplis', function () {
    $service = new ArticleService();
    expect($service->estPubliable(new Article(['title' => 'T', 'content' => 'C'])))->toBeTrue();
    expect($service->estPubliable(new Article(['title' => '', 'content' => 'C'])))->toBeFalse();
});
```
**🟢 Le minimum pour passer au vert** :
```php
<?php
public function estPubliable(Article $a): bool
{
    return trim((string) $a->title) !== '' && trim((string) $a->content) !== '';
}
```
**🔵 Refactor** : le code est déjà simple ; on pourrait extraire une constante ou clarifier les
noms si nécessaire, en gardant le test vert.

**Pourquoi le TDD produit un meilleur code** : écrire le test **avant** te force à concevoir
l'**interface** (le « comment on l'utilise ») avant l'implémentation. Ça pousse naturellement
vers des unités **petites**, **découplées** et **à responsabilité unique** — sinon, elles sont
pénibles à tester. Le test devient aussi un **filet** qui te laisse refactoriser sans peur.

---

## 🎉 Bilan du Niveau 11
Tu écris des tests unitaires et fonctionnels, tu testes API, validation et auth, et tu pratiques
le **TDD**. Tu peux désormais **modifier ton code sans crainte** : le vrai super-pouvoir de
l'ingénieur.
👉 [Niveau 12 : Architecture & Projet final](../Niveau-12-Projet-Final/)
