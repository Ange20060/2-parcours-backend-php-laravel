# Leçon 12.1 — Architecture d'une vraie application

> 🎯 **Objectif** : organiser une application Laravel qui **grandit** — les couches, les patterns
> utiles, et surtout **quand** les utiliser (et quand s'abstenir). C'est la maturité qui distingue
> un développeur d'un codeur.

---

## 🧱 Les couches d'une application backend

Une app bien structurée sépare les **responsabilités** en couches. Une requête les traverse :

```
   HTTP (Route → Contrôleur)          ← reçoit la requête, renvoie la réponse
        │  délègue à
   Service (logique métier)           ← les règles, l'orchestration
        │  utilise
   Modèle / Repository (données)      ← accès à la base (Eloquent)
```
| Couche | Responsabilité | Ne doit PAS… |
|---|---|---|
| **Contrôleur** | Traduire HTTP ↔ métier | contenir de la logique métier ou du SQL |
| **Form Request** | Valider l'entrée | contenir de la logique métier |
| **Service** | La logique métier, l'orchestration | savoir qu'il y a du HTTP |
| **Modèle / Repository** | Lire/écrire les données | contenir des règles métier lourdes |
| **Resource** | Formater la sortie JSON | requêter la base |

> 🧠 Chaque couche a **un** rôle (**[SoC](../Principes-Genie-Logiciel/04-SoC.md)**). Un changement
> dans l'une n'ébranle pas les autres → l'application reste **maintenable** en grandissant.

---

## 🎯 Les patterns utiles (avec discernement)

| Pattern | Quand | Bénéfice |
|---|---|---|
| **Service** | Dès qu'un contrôleur dépasse quelques lignes de logique | contrôleur fin, testable |
| **Form Request** | Toute action qui reçoit des données | validation isolée, réutilisable |
| **API Resource** | Toute API | format de sortie maîtrisé (SSOT) |
| **Policy** | Toute action sur un objet appartenant à quelqu'un | autorisation métier |
| **Action class** | Une opération unique et réutilisable (`PublierArticle`) | une classe = une action |
| **DTO** | Passer des données typées entre couches | explicite, moins d'erreurs |
| **Repository** | Isoler l'accès données / le rendre remplaçable | découplage, testabilité |

---

## ⚖️ La règle d'or : ne pas sur-architecturer

Le plus dur n'est pas de **connaître** ces patterns, c'est de savoir **quand** les appliquer.

> ❌ Créer une interface + un repository + un DTO + une action pour un simple `GET /articles` →
> tu violes **[KISS](../Principes-Genie-Logiciel/02-KISS.md)** et **[YAGNI](../Principes-Genie-Logiciel/03-YAGNI.md)**.
>
> ✅ Commence **simple** (contrôleur + Eloquent). **Extrais** un service/repository **quand** la
> complexité réelle apparaît (logique qui grossit, besoin de tester, 2ᵉ implémentation).

C'est la **[règle du Boy Scout](../Principes-Genie-Logiciel/10-boy-scout.md)** : améliore
progressivement, ne conçois pas une cathédrale d'avance.

---

## 🗂️ Organisation des dossiers (au-delà du défaut)

Pour une app moyenne, l'arborescence Laravel suffit. Quand ça grossit, on regroupe **par
domaine** plutôt que par type technique :

```
app/
├── Http/Controllers/Api/
├── Services/            (ArticleService, PaiementService…)
├── Actions/            (PublierArticle, InviterMembre…)
├── Policies/
├── Data/               (DTO)
└── Models/
```
> 🔎 Pour aller plus loin : cherche l'**architecture hexagonale** / **DDD** (Domain-Driven
> Design). Utile sur de **gros** projets — surdimensionné pour un petit. Sache que ça existe.

---

## 🔗 Récapitulatif : où vit chaque chose

Une requête `POST /api/articles` bien architecturée :
1. **Route** (`api.php`) → **Contrôleur** `Api\ArticleController@store`
2. **Form Request** `StoreArticleRequest` valide (Fail Fast)
3. **Policy** vérifie l'autorisation
4. **Service** `ArticleService::creer()` applique la logique métier
5. **Modèle** Eloquent persiste
6. **Resource** `ArticleResource` formate la réponse JSON (201)

Chaque étape est **isolée**, **testable** et **remplaçable**. C'est **tout** le parcours réuni.

---

## 🔎 À toi de chercher

> 1. Compare **Service** et **Action class** : deux façons d'organiser la logique métier.
> 2. Qu'est-ce qu'un **DTO** et pourquoi préférer un objet typé à un tableau associatif entre couches ?
> 3. Survole l'**architecture hexagonale** : quel problème résout-elle, et pour quelle taille de projet ?

---

## 🎓 Ce qu'il faut retenir

- Structure en **couches** : HTTP (contrôleur) → **service** (métier) → **modèle/repository** (données) → **resource** (sortie).
- Chaque couche a **une** responsabilité ; utilise les patterns (Service, Policy, Resource, DTO…) **à bon escient**.
- **Ne sur-architecture pas** : commence simple, extrais quand la complexité réelle l'exige (KISS/YAGNI).
- Une requête bien architecturée réunit **tout** le parcours (validation, autorisation, service, resource).

👉 Leçon suivante : [Du projet au déploiement](./02-deploiement.md)
