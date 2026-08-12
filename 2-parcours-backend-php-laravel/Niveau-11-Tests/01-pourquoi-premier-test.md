# Leçon 11.1 — Pourquoi tester, et ton premier test

> 🎯 **Objectif** : comprendre pourquoi les **tests automatisés** sont non négociables, et écrire
> ton premier test avec **Pest / PHPUnit**. C'est ce qui sépare « je crois que ça marche » de
> « je **prouve** que ça marche ».

---

## 🛡️ Pourquoi tester ?

Un **test automatisé** est du code qui **vérifie** ton code. Tu le lances d'une commande, et il
te dit **immédiatement** si quelque chose est cassé.

- ✅ **Filet de sécurité** : tu peux **refactoriser** sans peur — si tu casses quelque chose, un
  test rouge te prévient tout de suite.
- ✅ **Documentation vivante** : un test décrit **ce que le code est censé faire**.
- ✅ **Confiance** : tu livres en sachant que les cas importants passent.
- ✅ **Rapidité** : vérifier 100 scénarios à la main prend des heures ; les tests, des secondes.

> ⚠️ **La leçon des corrections récentes** : un stagiaire avait des tests… mais le fichier
> s'appelait `TasApiTest.php` alors que la classe était `TaskApiTest` → **le test ne s'exécutait
> jamais** (juste un warning). Il croyait ses tâches testées : elles ne l'étaient pas, et 3 bugs
> sont passés. **Un test qui ne tourne pas (ou qu'on ne relance pas) ne protège de rien.**

---

## 🧪 Les deux familles de tests

| Type | Vérifie… | Exemple |
|---|---|---|
| **Test unitaire** | une **petite unité** isolée (une fonction, une méthode) | `calculerTTC(100) === 120` |
| **Test de fonctionnalité** (feature) | un **scénario complet** de bout en bout | `POST /api/articles` renvoie 201 |

En Laravel, ils vivent dans `tests/Unit/` et `tests/Feature/`.

---

## ⚙️ Lancer les tests

Laravel est **prêt pour les tests** dès l'installation (Pest par défaut sur les versions
récentes, PHPUnit sinon). Configuration de test dans `phpunit.xml` (souvent une base **SQLite
en mémoire**, ultra rapide).

```bash
php artisan test                    # lance toute la suite
php artisan test --filter=Article   # seulement les tests dont le nom contient "Article"
```

---

## 🥇 Ton premier test (Pest)

**Pest** offre une syntaxe concise et lisible :

```php
<?php
// tests/Unit/CalculTest.php
test('le TTC ajoute 20% de TVA', function () {
    expect(calculerTTC(100.0))->toBe(120.0);
});

test('un montant négatif est refusé', function () {
    expect(fn () => calculerTTC(-5))->toThrow(InvalidArgumentException::class);
});
```

### La même chose en PHPUnit (syntaxe classique)
```php
<?php
// tests/Unit/CalculTest.php
use PHPUnit\Framework\TestCase;

class CalculTest extends TestCase
{
    public function test_le_ttc_ajoute_20_pourcent(): void
    {
        $this->assertSame(120.0, calculerTTC(100.0));
    }
}
```
> ⚠️ **En PHPUnit, le nom du fichier DOIT correspondre au nom de la classe** (`CalculTest.php`
> ↔ `class CalculTest`), et une méthode de test doit commencer par `test` (ou porter
> `#[Test]`). Sinon **elle ne s'exécute pas** — l'erreur exacte du stagiaire.

---

## 🟢🔴 Lire le résultat

```
✓ le TTC ajoute 20% de TVA
✓ un montant négatif est refusé
Tests: 2 passed
```
Vert = ça passe. Rouge = un test échoue, avec le message et la ligne. **Toujours** vérifier le
nombre de tests exécutés — un `0 passed` ou un `WARN ... cannot be found` est un signal d'alarme.

---

## 🔎 À toi de chercher

> 1. Installe/repère **Pest** dans un projet Laravel ; compare sa syntaxe à PHPUnit. Laquelle préfères-tu ?
> 2. Cherche les **assertions** courantes : `toBe`, `toEqual`, `toBeTrue`, `toContain` (Pest) /
>    `assertSame`, `assertEquals`, `assertTrue`, `assertCount` (PHPUnit).
> 3. Que fait `php artisan test --coverage` ? (mesurer la **couverture** de code, et ses limites.)

---

## 🎓 Ce qu'il faut retenir

- Un **test automatisé** prouve que ton code marche et te permet de **refactoriser sans peur**.
- **Unitaire** (une fonction) vs **feature** (un scénario complet) ; `tests/Unit`, `tests/Feature`.
- On lance avec **`php artisan test`** ; vérifie **le nombre de tests exécutés**.
- En **PHPUnit**, nom de fichier = nom de classe, méthode préfixée `test` — sinon **rien ne
  tourne** (le bug classique à ne jamais reproduire).

👉 Leçon suivante : [Tester une API Laravel](./02-tester-une-api.md)
