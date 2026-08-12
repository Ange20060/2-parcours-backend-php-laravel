# ✅ Niveau 12 — Guide de référence & grille (Projet final)

> Ce n'est pas un corrigé « ligne à ligne » (le projet est le tien), mais une **architecture de
> référence**, une **checklist des principes** et une **grille d'auto-évaluation**.

---

## 🏛️ Architecture de référence (API de tâches d'équipe)

```
app/
├── Models/            User, Project, Task, Comment (+ relations Eloquent)
├── Http/
│   ├── Controllers/Api/   contrôleurs FINS (orchestrent, délèguent aux services)
│   ├── Requests/          Form Requests (validation isolée)
│   ├── Resources/         format JSON centralisé (SSOT du rendu)
│   └── Middleware/        auth, autorisations
├── Services/          logique métier (TaskService, ProjectService...)
└── Policies/          autorisations par ressource
database/
├── migrations/        le schéma (SSOT)
├── factories/         données de test
└── seeders/
routes/api.php         endpoints RESTful
tests/                 Feature/ (endpoints) + Unit/ (services)
```

**Flux d'une requête** : Route → Middleware (auth) → Form Request (validation) → Contrôleur
(orchestration) → Service (métier) → Model/Eloquent (données) → API Resource (format) → réponse JSON.

---

## 📐 Checklist des principes (revue de code)

| Principe | À vérifier dans ton projet |
|---|---|
| **[DRY](../Principes-Genie-Logiciel/01-DRY.md)** | Pas de logique dupliquée ; validation en Form Requests, format en Resources |
| **[KISS](../Principes-Genie-Logiciel/02-KISS.md)** | Pas d'abstraction inutile ; chaque classe se comprend vite |
| **[YAGNI](../Principes-Genie-Logiciel/03-YAGNI.md)** | Aucune fonctionnalité « au cas où » non demandée |
| **[SoC](../Principes-Genie-Logiciel/04-SoC.md)** | Contrôleurs fins, métier dans les services, format dans les Resources |
| **[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** | Schéma dans les migrations, config dans `.env`, format dans les Resources |
| **[Couplage/Cohésion](../Principes-Genie-Logiciel/06-cohesion-couplage.md)** | Dépendances injectées, pas de `new` en dur des services |
| **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)** | Validation stricte, autorisations vérifiées tôt, bons codes d'erreur |
| **[Explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)** | Types partout, noms parlants, statuts HTTP corrects |
| **[Composition](../Principes-Genie-Logiciel/09-composition-vs-heritage.md)** | Services composés, peu d'héritage profond |
| **[SOLID](../Principes-Genie-Logiciel/11-SOLID.md)** | Responsabilité unique, injection de dépendances, interfaces si 2+ implémentations |
| **[Boy Scout](../Principes-Genie-Logiciel/10-boy-scout.md)** | Aucun code mort ; commits de nettoyage |

---

## 🧮 Grille d'auto-évaluation (sur 20)

| Critère | Points |
|---|:-:|
| Fonctionnalités de l'API (CRUD complet, relations) | /5 |
| Authentification & autorisations (Sanctum, policies) | /3 |
| Validation & gestion des erreurs (Form Requests, statuts corrects) | /3 |
| Qualité du code & respect des principes (services, Resources, SoC) | /4 |
| Tests automatisés (fonctionnels + unitaires, tous verts) | /3 |
| Git & documentation (commits clairs, README, installation) | /2 |
| **TOTAL** | **/20** |

> 🎯 **Signal de maturité** : tu dois pouvoir **justifier chaque choix** en soutenance
> (« j'ai mis ça dans un service **parce que** le contrôleur doit rester fin — SoC »).

---

## 🚀 Déploiement (bonus)
Pistes : **Laravel Forge + un VPS**, **Railway**, **Render**, ou un hébergeur PHP. Points clés :
variables d'environnement (`.env` **jamais** commité), `php artisan migrate --force`, `APP_DEBUG=false`
en production, HTTPS.

---

## 🎓 Bravo — tu es développeur backend

Si ce projet est **fonctionnel, testé, propre et publié**, tu as prouvé bien plus que « savoir
coder » : tu sais **concevoir un logiciel maintenable**. C'est exactement ce qu'une entreprise
attend d'un ingénieur backend.

**Pour continuer** : queues & jobs, cache (Redis), événements/listeners, WebSockets, architecture
hexagonale, DDD, CI/CD. Et surtout : garde le réflexe des **principes** — toute une carrière. 💚
