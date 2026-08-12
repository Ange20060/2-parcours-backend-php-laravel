# 📝 Niveau 11 — Exercices (Tests & TDD)

Laravel intègre les tests (Pest par défaut sur les versions récentes, ou PHPUnit). Lance-les
avec `php artisan test`. **🎯 But** à chaque exercice. Corrigés : [corriges.md](./corriges.md).

> 🎯 **Exigence** : un code **testable** est un code **bien conçu**. Si un test est difficile à
> écrire, questionne le **couplage** de ton code, pas le test.

---

## Exercice 1 — Premier test unitaire 🧪
> 🎯 **But** : écrire et lancer un test sur une fonction pure.

Reprends une fonction du Niveau 1/2 (ex : `calculerTTC`, `genererSlug`). Écris un test qui
vérifie plusieurs cas (dont un cas limite). Lance `php artisan test`.

---

## Exercice 2 — Tester une exception 🛡️
> 🎯 **But** : vérifier qu'un code **échoue** correctement (Fail Fast testé).

Écris un test qui vérifie qu'appeler `retirer($solde, $montant)` avec un montant supérieur au
solde **lève bien** l'exception attendue.

---

## Exercice 3 — Test de fonctionnalité (API) 🌐
> 🎯 **But** : tester un endpoint HTTP de bout en bout.

Écris un test qui envoie `GET /api/articles` et vérifie : le statut **200**, et que la réponse
JSON contient la bonne structure. Utilise `RefreshDatabase` + une factory pour préparer les données.

---

## Exercice 4 — Tester la validation 🔢
> 🎯 **But** : vérifier qu'une entrée invalide est rejetée (422).

Écris un test qui envoie un `POST /api/articles` **sans `title`** et vérifie que la réponse est
**422** avec une erreur de validation sur `title`.

---

## Exercice 5 — Tester l'authentification 🔐
> 🎯 **But** : vérifier qu'une route protégée refuse les non-authentifiés.

Écris deux tests : (a) un utilisateur **non connecté** reçoit **401** sur `POST /api/articles` ;
(b) un utilisateur **authentifié** (via `actingAs`) peut créer un article (**201**).

---

## Exercice 6 — TDD : rouge → vert → refactor 🌟
> 🎯 **But** : écrire le **test d'abord**, puis le code.

Implémente en **TDD** une méthode `ArticleService::estPubliable(Article $a): bool` (règle :
publiable si le titre et le contenu ne sont pas vides). Étapes :
1. 🔴 Écris le test **d'abord** (il échoue, la méthode n'existe pas).
2. 🟢 Écris le **minimum** de code pour le faire passer.
3. 🔵 **Refactorise** si besoin, en gardant le test vert.
Explique en quoi ce cycle produit un code mieux conçu.

---

👉 Correction : [corriges.md](./corriges.md)
