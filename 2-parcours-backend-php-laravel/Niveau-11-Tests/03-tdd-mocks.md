# Leçon 11.3 — Le cycle TDD & les doublures de test

> 🎯 **Objectif** : découvrir le **TDD** (écrire le test **avant** le code) et les **doublures**
> (mocks/fakes) pour tester une unité **isolée** de ses dépendances. Deux pratiques qui font les
> développeurs de confiance.

---

## 🔴🟢🔵 Le cycle TDD

Le **TDD** (*Test-Driven Development*) inverse l'ordre habituel : on écrit **le test d'abord**,
puis le code minimal pour le faire passer. Le cycle en trois temps :

```
🔴 RED     — écris un test qui échoue (la fonctionnalité n'existe pas encore)
🟢 GREEN   — écris le MINIMUM de code pour le faire passer
🔵 REFACTOR — nettoie le code, en gardant le test vert
        ↑___________________________________________|  (on recommence)
```

### Exemple pas à pas
```php
<?php
// 🔴 1. Le test d'abord — il échoue (estPubliable n'existe pas)
test('un article avec titre et contenu est publiable', function () {
    $service = new ArticleService();
    expect($service->estPubliable(new Article(['titre' => 'X', 'contenu' => 'Y'])))->toBeTrue();
});
test('un article sans titre n\'est pas publiable', function () {
    $service = new ArticleService();
    expect($service->estPubliable(new Article(['titre' => '', 'contenu' => 'Y'])))->toBeFalse();
});
```
```php
<?php
// 🟢 2. Le code minimal pour passer au vert
class ArticleService
{
    public function estPubliable(Article $article): bool
    {
        return $article->titre !== '' && $article->contenu !== '';
    }
}
```
```php
<?php
// 🔵 3. Refactor si besoin (ici, c'est déjà simple) — les tests restent verts
```

---

## 🧠 Pourquoi le TDD produit un meilleur code

- Tu penses d'abord au **comportement attendu** (l'interface), pas à l'implémentation.
- Tu n'écris **que le code nécessaire** (KISS/YAGNI) — pas de fonctionnalité non testée « au cas où ».
- Ton code est **testable par construction** → donc **bien conçu** (faible couplage, responsabilités claires).
- Tu obtiens une **couverture** naturelle : chaque ligne existe parce qu'un test l'a demandée.

> 🧩 **Insight clé** : un code **difficile à tester** est presque toujours un code **mal conçu**
> (trop couplé, une fonction qui fait tout). La testabilité **récompense** la
> **[SoC](../Principes-Genie-Logiciel/04-SoC.md)** et l'**[injection de dépendances](../Principes-Genie-Logiciel/11-SOLID.md)**.

---

## 🎭 Les doublures : isoler une unité

Pour tester une classe **sans** exécuter ses dépendances réelles (une API externe, un envoi
d'email, un paiement), on les remplace par des **doublures**.

- **Fake** : une implémentation simplifiée (ex : un repository en mémoire).
- **Mock** : un objet simulé dont on **vérifie** qu'il a été appelé comme prévu.

Comme tu utilises l'**injection de dépendances** (Niveau 3 & 7), injecter une doublure est trivial :

```php
<?php
test('la création notifie l\'auteur', function () {
    // un mock du notifieur : on s'attend à ce que envoyer() soit appelé une fois
    $notifieur = Mockery::mock(NotifieurInterface::class);
    $notifieur->shouldReceive('envoyer')->once();

    $service = new ArticleService($notifieur);   // on injecte la doublure
    $service->creer(['titre' => 'X', 'contenu' => 'Y'], User::factory()->create());
});
```
Laravel fournit aussi des **fakes** intégrés pour ses services :
```php
<?php
Mail::fake();       // les emails ne partent pas vraiment
Queue::fake();      // les jobs ne s'exécutent pas
Event::fake();
// ... puis on assertSent / assertPushed / assertDispatched
Mail::assertSent(BienvenueMail::class);
```

---

## ⚖️ Que tester en priorité ?

Tu ne testes pas tout à 100 %. Concentre-toi sur :
1. La **logique métier** (services, calculs, règles) — via des tests **unitaires**.
2. Les **endpoints critiques** et leurs **cas d'échec** (401/403/422) — via des tests **feature**.
3. Les **bugs corrigés** : ajoute un test qui reproduit le bug → il ne reviendra jamais (test de **non-régression**).

---

## 🔎 À toi de chercher

> 1. Différence entre **mock**, **stub**, **fake** et **spy**.
> 2. `Http::fake()` : tester du code qui appelle une **API externe** sans requête réelle.
> 3. Cherche « TDD débutant » et essaie le cycle rouge-vert-refactor sur une petite fonction.

---

## 🎓 Ce qu'il faut retenir

- **TDD** : 🔴 test qui échoue → 🟢 code minimal → 🔵 refactor. On écrit le test **avant**.
- Le TDD produit du code **testable donc bien conçu** (KISS, faible couplage).
- Les **doublures** (mock/fake) isolent l'unité de ses dépendances — faciles grâce à l'injection.
- Laravel : `Mail::fake()`, `Queue::fake()`, `Http::fake()` pour neutraliser les effets externes.
- Priorise : logique métier, cas d'échec des endpoints, **tests de non-régression** sur les bugs.

---

🎉 **Tu as fini le Niveau 11 !** Tu sais **prouver** que ton code marche : tests unitaires et
feature, cas d'échec, TDD, doublures. C'est la compétence qui aurait sauvé les copies de tes
stagiaires. Fais les [exercices](./exercices.md), puis assemble tout dans le
**[projet final](../Niveau-12-Projet-Final/)**. 🚀
