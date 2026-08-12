# 📐 Domaine transversal — Principes du Génie Logiciel

Ce domaine n'est **pas un niveau parmi les autres** : c'est ta **boussole permanente**. Ces
principes s'appliquent à **tout** le code que tu écriras, en PHP comme en Laravel, du premier
jour jusqu'à ta carrière d'ingénieur.

> 🎯 **Comment l'utiliser** : lis-les une première fois pour comprendre l'idée, puis
> **reviens-y** à chaque niveau. À chaque exercice de code, pose-toi : *« Quel(s) principe(s)
> ai-je respecté ou violé ici ? »*

---

## 🧭 Les 11 principes (fiches individuelles)

Chaque fiche suit le même format : **le principe → ce qu'il tue → exemple ❌ / ✅ en PHP →
liens avec les autres → mini-exercice.**

| # | Principe | En une phrase |
|:--:|---|---|
| 1 | [DRY](./01-DRY.md) — *Don't Repeat Yourself* | Chaque savoir vit à **un seul endroit** |
| 2 | [KISS](./02-KISS.md) — *Keep It Simple* | La solution **la plus simple** qui marche |
| 3 | [YAGNI](./03-YAGNI.md) — *You Aren't Gonna Need It* | Ne code **pas** pour un futur imaginaire |
| 4 | [SoC](./04-SoC.md) — *Separation of Concerns* | Chaque module a **une** responsabilité claire |
| 5 | [SSOT](./05-SSOT.md) — *Single Source of Truth* | Chaque fait a **une** source qui fait autorité |
| 6 | [Cohésion & Couplage](./06-cohesion-couplage.md) | Regrouper le lié, **isoler** le reste |
| 7 | [Fail Fast](./07-fail-fast.md) | Rejeter l'invalide **tout de suite et fort** |
| 8 | [Explicite > Implicite](./08-explicite-vs-implicite.md) | Un comportement **évident**, jamais « magique » |
| 9 | [Composition > Héritage](./09-composition-vs-heritage.md) | Assembler des briques plutôt qu'empiler des classes |
| 10 | [Règle du Boy Scout](./10-boy-scout.md) | Laisser le code **plus propre** qu'on l'a trouvé |
| 11 | [SOLID](./11-SOLID.md) | Les **5** principes de conception orientée objet |

Puis, pour t'entraîner :
- 🏋️ [Exercices transversaux de refactoring](./exercices.md)
- ✅ [Corrigés](./corriges.md)

---

## 🌟 La règle qui les résume tous

> **Écris le code pour l'humain qui le lira dans 6 mois — et cet humain, ce sera souvent toi.**

Le code est **lu** bien plus souvent qu'il n'est **écrit**. Optimise pour la **lecture**, la
**compréhension** et le **changement**, pas pour ta rapidité d'écriture d'aujourd'hui.

---

## ⚠️ Deux avertissements d'ingénieur

1. **Un principe n'est pas une loi religieuse.** Ce sont des **guides**. Il arrive qu'ils se
   contredisent (ex : trop appliquer DRY peut créer du couplage). Ton travail est de
   **juger** — c'est ça, l'expérience.
2. **Ne les applique pas trop tôt.** Sur-appliquer un principe (sur-abstraire « au cas où »)
   viole souvent **KISS** et **YAGNI**. Le bon dosage vient avec la pratique.

👉 Commence par la fiche [1 — DRY](./01-DRY.md).
