# 🔵 Niveau 9 — Contrôleurs, Validation & Middleware

Construire des fonctionnalités web robustes : recevoir et **valider** les données proprement,
protéger les routes avec des **middlewares**, et gérer les erreurs élégamment.

> 🎯 **Objectifs :** contrôleurs « ressource » · **Form Requests** & règles de validation ·
> **middleware** (auth, throttling…) · réponses & redirections · gestion centralisée des
> erreurs · sessions et messages flash.

## 📖 Les leçons (dans l'ordre)
1. [Contrôleurs ressource & CRUD web](./01-controleurs-ressource.md)
2. [La validation & les Form Requests](./02-validation.md)
3. [Middleware & autorisation (Policies)](./03-middleware-policies.md)
4. [Services & gestion des erreurs](./04-services-erreurs.md)

Puis :
- 📝 [Les exercices](./exercices.md) — 6 exercices, chacun avec son **but précis** · ✅ [Corrigés](./corriges.md)

## 📐 Principes clés
Garder les contrôleurs **fins** (**[SoC](../Principes-Genie-Logiciel/04-SoC.md)** : le contrôleur
orchestre, le service fait le métier). La validation en Form Request = **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)** + **[DRY](../Principes-Genie-Logiciel/01-DRY.md)**.

👉 Suite : [Niveau 10 : API REST & Authentification](../Niveau-10-Laravel-API/)
