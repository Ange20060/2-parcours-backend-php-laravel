# 🟣 Niveau 11 — Tests & TDD

Ce qui sépare un amateur d'un ingénieur : **prouver** que son code marche, et le garder
fonctionnel dans le temps. On apprend les tests automatisés et le **TDD**.

> 🎯 **Objectifs :** tests unitaires vs fonctionnels · **PHPUnit** et **Pest** · tester une API
> Laravel · *mocking* et *factories* · le cycle **TDD** (rouge → vert → refactor) · la couverture
> de code (et ses limites).

## 📖 Les leçons (dans l'ordre)
1. [Pourquoi tester, et ton premier test](./01-pourquoi-premier-test.md)
2. [Tester une API Laravel](./02-tester-une-api.md)
3. [Le cycle TDD & les doublures de test](./03-tdd-mocks.md)

Puis :
- 📝 [Les exercices](./exercices.md) — 6 exercices, chacun avec son **but précis** · ✅ [Corrigés](./corriges.md)

## 📐 Le lien fort avec les principes
Un code **testable** est un code **bien conçu** : la testabilité **récompense** le
**[faible couplage](../Principes-Genie-Logiciel/06-cohesion-couplage.md)**, la
**[SoC](../Principes-Genie-Logiciel/04-SoC.md)** et l'**[injection de dépendances](../Principes-Genie-Logiciel/11-SOLID.md)**.
Si un code est dur à tester, c'est souvent qu'il est mal conçu.

👉 Suite : [Niveau 12 : Architecture & Projet final](../Niveau-12-Projet-Final/)
