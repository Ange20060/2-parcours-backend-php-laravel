# Leçon 10.4 — Pagination, filtres & finitions d'API

> 🎯 **Objectif** : rendre ton API **exploitable à grande échelle** — pagination, filtrage, tri,
> versionnement, limitation de débit, format d'erreur cohérent. Les détails qui séparent une API
> d'amateur d'une API professionnelle.

---

## 📄 Pagination (jamais tout renvoyer)

```php
<?php
public function index()
{
    return ArticleResource::collection(
        Article::with('user')->latest()->paginate(15)
    );
}
```
La réponse inclut automatiquement les métadonnées :
```json
{
  "data": [ ... ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "...?page=2" },
  "meta":  { "current_page": 1, "last_page": 8, "per_page": 15, "total": 116 }
}
```
> ⚠️ Renvoyer 10 000 lignes d'un coup **sature** la mémoire et rend l'API lente. La pagination
> borne la charge — c'est **non négociable** sur une liste.

---

## 🔍 Filtrage et tri (via la query string)

```php
<?php
public function index(Request $request)
{
    $query = Article::with('user');

    if ($request->filled('published')) {
        $query->where('published', $request->boolean('published'));
    }
    if ($request->filled('search')) {
        $query->where('titre', 'like', '%' . $request->string('search') . '%');
    }
    $tri = $request->string('sort', 'latest');
    $query->when($tri === 'oldest', fn ($q) => $q->oldest())->when($tri !== 'oldest', fn ($q) => $q->latest());

    return ArticleResource::collection($query->paginate(15));
}
```
Appels : `/api/articles?published=1&search=php&sort=oldest&page=2`.

> 🔎 Pour des filtres riches, cherche le package **spatie/laravel-query-builder** — il standardise
> `?filter[...]`, `?sort=...`, `?include=...` proprement.

---

## 🏷️ Versionner l'API

Une API publique évolue sans casser les clients existants → on **versionne** par préfixe :

```php
<?php
Route::prefix('v1')->group(function () {
    Route::apiResource('articles', \App\Http\Controllers\Api\V1\ArticleController::class);
});
// endpoints : /api/v1/articles
```
> 🧠 Le contrat d'une API est une **promesse** aux clients. Versionner permet de faire évoluer le
> **v2** sans casser le **v1** — c'est de l'**[Explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)**
> et du respect des consommateurs.

---

## 🚦 Rate limiting (limiter le débit)

Protéger l'API contre les abus / le bruteforce :

```php
<?php
Route::middleware('throttle:60,1')->group(function () {   // 60 requêtes / minute
    // ...
});
Route::post('/login', ...)->middleware('throttle:5,1');   // login : 5 essais / minute
```

---

## 🧯 Un format d'erreur cohérent

Laravel renvoie déjà des erreurs JSON standard quand `Accept: application/json` est présent :
```json
{ "message": "The titre field is required.", "errors": { "titre": ["..."] } }
```
Garde ce format **uniforme** sur toute l'API (validation 422, 404, 401, 403…). Un client doit
pouvoir traiter les erreurs **de la même façon partout** — c'est le **[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)**
du format d'erreur.

---

## ✅ La checklist d'une API pro

- [ ] Bons **verbes** et **codes de statut** (201, 204, 401, 403, 404, 422)
- [ ] **Resources** pour un JSON maîtrisé (pas de modèle brut)
- [ ] **Auth** par token (Sanctum) + **Policies** (ownership)
- [ ] **Pagination** sur les listes, **filtres/tri** via la query string
- [ ] **Rate limiting**, surtout sur le login
- [ ] Format d'**erreur cohérent**
- [ ] **Versionnement** (`/v1`) et **documentation**

---

## 🔎 À toi de chercher

> 1. `cursorPaginate()` vs `paginate()` : quand la pagination par curseur est plus performante.
> 2. **CORS** : configurer `config/cors.php` pour autoriser ton front (autre domaine) à appeler l'API.
> 3. Générer une **documentation OpenAPI/Swagger** (ex : Scramble, L5-Swagger) à partir de ton code.

---

## 🎓 Ce qu'il faut retenir

- **Pagination** obligatoire sur les listes ; **filtres/tri** via la query string.
- **Versionne** (`/api/v1`) pour évoluer sans casser les clients ; **rate limiting** contre les abus.
- Garde un **format d'erreur cohérent** (SSOT) sur toute l'API.
- Vise la **checklist pro** : codes corrects, Resources, auth+policies, pagination, doc.

---

🎉 **Tu as fini le Niveau 10 !** Tu construis des **API REST professionnelles** : endpoints
propres, JSON maîtrisé (Resources), **auth par token** (Sanctum), pagination et finitions. Fais
les [exercices](./exercices.md), puis apprends à **prouver que tout marche** avec les
**[tests](../Niveau-11-Tests/)**. 🚀
