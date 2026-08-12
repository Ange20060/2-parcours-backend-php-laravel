# Leçon 10.3 — Authentification d'API avec Sanctum

> 🎯 **Objectif** : sécuriser ton API par **token** avec **Laravel Sanctum** — inscription,
> connexion, routes protégées, déconnexion. C'est l'authentification des applis mobiles et des
> fronts modernes (React, Vue).

---

## 🔑 Token plutôt que session

Une **API** est **sans état** (stateless) : pas de session comme sur le web. Le client
(appli mobile, front JS) reçoit un **token** à la connexion, et l'envoie à chaque requête dans
l'en-tête `Authorization: Bearer <token>`. **Sanctum** gère l'émission et la vérification de ces tokens.

---

## ⚙️ Installer Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate                # crée la table personal_access_tokens
```
Sur le modèle `User`, ajoute le trait :
```php
<?php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

---

## 📝 Inscription et connexion

```php
<?php
// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Route::post('/register', function (Request $request) {
    $donnees = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',       // attend password_confirmation
    ]);
    $donnees['password'] = Hash::make($donnees['password']);   // hachage (jamais en clair !)
    $user = User::create($donnees);

    return response()->json([
        'token' => $user->createToken('api')->plainTextToken,
    ], 201);
});

Route::post('/login', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    $user = User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Identifiants invalides.'], 401);  // Fail Fast
    }
    return response()->json([
        'token' => $user->createToken('api')->plainTextToken,
    ]);
});
```
> ⚠️ Réflexes (corrections récentes) : **hacher** le mot de passe (`Hash::make`), renvoyer **401**
> en cas d'échec **sans dire** lequel (email ou mot de passe) est faux, et **déclarer réellement**
> ces routes (une copie avait un `AuthController::login`… sans route pour l'atteindre !).

---

## 🔒 Protéger les routes

Le middleware **`auth:sanctum`** exige un token valide :

```php
<?php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $r) => $r->user());
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();   // révoque le token
        return response()->noContent();
    });
});
```
Le client envoie ensuite :
```
Authorization: Bearer 3|xr7Kp...le_token
Accept: application/json
```
`$request->user()` renvoie alors l'utilisateur authentifié ; sinon, Laravel répond **401** automatiquement.

---

## 👤 Lier les actions à l'utilisateur du token

```php
<?php
public function store(StoreArticleRequest $request)
{
    // l'article est créé POUR l'utilisateur du token (pas de user_id à passer à la main)
    $article = $request->user()->articles()->create($request->validated());
    return new ArticleResource($article);
}
```
Combine avec une **Policy** (Niveau 9) pour l'`update`/`delete` : seul le propriétaire agit sur
**son** contenu.

---

## 🧩 Abilities (permissions du token, bonus)

Un token peut porter des **capacités** limitées :
```php
<?php
$user->createToken('mobile', ['articles:read'])->plainTextToken;
// puis : $request->user()->tokenCan('articles:read')
```

---

## 🔎 À toi de chercher

> 1. Différence entre l'auth **par token** (API/mobile) et l'auth **SPA** de Sanctum (cookies, même domaine).
> 2. Comment **révoquer tous** les tokens d'un utilisateur (`$user->tokens()->delete()`).
> 3. Ajouter un **throttling** au login (`->middleware('throttle:5,1')`) contre le bruteforce.

---

## 🎓 Ce qu'il faut retenir

- Une API s'authentifie par **token** (`Authorization: Bearer ...`) — **Sanctum** l'implémente.
- Émettre : `$user->createToken('api')->plainTextToken` ; protéger : middleware **`auth:sanctum`**.
- **Hacher** les mots de passe (`Hash::make`/`Hash::check`), renvoyer **401** sur échec, **déclarer** les routes d'auth.
- Lier les actions à `$request->user()` + **Policies** pour l'ownership.

👉 Leçon suivante : [Pagination, filtres & finitions d'API](./04-pagination-finitions.md)
