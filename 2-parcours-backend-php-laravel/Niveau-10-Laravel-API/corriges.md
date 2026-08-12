# ✅ Niveau 10 — Corrigés (API REST & Authentification)

> ⚠️ Essaie d'abord. Code Laravel idiomatique.

---

## Exercice 1 — Routes RESTful
```php
<?php
// routes/api.php
Route::apiResource('articles', ArticleApiController::class);
```
| Endpoint | Verbe | Action | Statut succès |
|---|---|---|---|
| `/api/articles` | GET | index (liste) | 200 |
| `/api/articles` | POST | store (créer) | **201** |
| `/api/articles/{id}` | GET | show (détail) | 200 |
| `/api/articles/{id}` | PUT/PATCH | update | 200 |
| `/api/articles/{id}` | DELETE | destroy | **204** (No Content) |

## Exercice 2 — API Resource
```php
<?php
// app/Http/Resources/ArticleResource.php
public function toArray(Request $request): array
{
    return [
        'id'        => $this->id,
        'title'     => $this->title,
        'content'   => $this->content,
        'published' => $this->published,
        'author'    => $this->user->name,
        'created_at'=> $this->created_at->toIso8601String(),
    ];
}
```
```php
<?php
public function index()
{
    return ArticleResource::collection(Article::with('user')->paginate(10));
}
```
Centraliser le format dans une Resource = **SSOT du rendu** : si le format change, on le change
**à un seul endroit**, et toutes les réponses restent **cohérentes**. Un `json_encode` éparpillé
violerait DRY et deviendrait incohérent.

## Exercice 3 — Codes de statut
```php
<?php
public function store(StoreArticleRequest $request)
{
    $article = Article::create($request->validated());
    return (new ArticleResource($article))
        ->response()
        ->setStatusCode(201);                 // 201 Created
}
```
- `show` d'un id inexistant → **404** automatique (route model binding).
- Validation échouée → **422 Unprocessable Entity** avec `{ "message": ..., "errors": {...} }`
  automatiquement (Laravel le fait pour les requêtes JSON).

## Exercice 4 — Sanctum
```php
<?php
// routes/api.php
Route::post('/login', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Identifiants invalides.'], 401);   // Fail Fast
    }
    return response()->json(['token' => $user->createToken('api')->plainTextToken]);
});

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', ArticleApiController::class)->except(['index', 'show']);
});
```
Le client envoie ensuite l'en-tête `Authorization: Bearer <token>` sur les requêtes protégées.

## Exercice 5 — Utilisateur authentifié
```php
<?php
public function store(StoreArticleRequest $request)
{
    $article = $request->user()->articles()->create($request->validated());
    return new ArticleResource($article);
}

public function update(UpdateArticleRequest $request, Article $article)
{
    if ($article->user_id !== $request->user()->id) {
        abort(403, "Action non autorisée.");
    }
    $article->update($request->validated());
    return new ArticleResource($article);
}
```
> `$request->user()->articles()->create(...)` associe l'article à l'utilisateur du token **et**
> respecte la relation : plus besoin de passer `user_id` à la main (Explicite + sûr).

## Exercice 6 — Pagination & filtres
```php
<?php
public function index(Request $request)
{
    $query = Article::with('user');

    if ($request->has('published')) {
        $query->where('published', $request->boolean('published'));
    }

    return ArticleResource::collection($query->latest()->paginate(15));
}
```
Renvoyer 10 000 lignes d'un coup **sature** la mémoire du serveur et du client, et rend la
réponse lente. La pagination borne la charge (page par page) et fournit des métadonnées
(`links`, `meta`) pour naviguer.

---

## 🎉 Bilan du Niveau 10
Tu construis des **API REST** propres : endpoints corrects, formats centralisés (Resources),
bons codes de statut, **authentification par token** (Sanctum), pagination. C'est le savoir-faire
backend le plus demandé.
👉 [Niveau 11 : Tests & TDD](../Niveau-11-Tests/)
