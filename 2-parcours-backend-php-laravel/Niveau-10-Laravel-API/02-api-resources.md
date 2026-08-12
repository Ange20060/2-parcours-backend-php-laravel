# Leçon 10.2 — Les API Resources (formater le JSON)

> 🎯 **Objectif** : contrôler **exactement** ce que ton API renvoie avec les **API Resources** —
> le format de sortie centralisé, cohérent et sûr. Fini le « on renvoie le modèle brut ».

---

## 🤔 Le problème du modèle brut

Retourner directement un modèle Eloquent expose **toutes** ses colonnes — y compris des champs
internes ou sensibles (`password`, timestamps techniques, clés internes) — et le format n'est pas
**maîtrisé**. Si tu renommes une colonne, toutes les réponses changent malgré toi.

Une **API Resource** est une classe qui décrit **la forme exacte** du JSON pour une entité.

---

## 🎁 Créer une Resource

```bash
php artisan make:resource ArticleResource
```
```php
<?php
// app/Http/Resources/ArticleResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'titre'     => $this->titre,
            'contenu'   => $this->contenu,
            'publie'    => (bool) $this->published,
            'auteur'    => $this->user->name,               // on choisit ce qu'on expose
            'cree_le'   => $this->created_at->toIso8601String(),
        ];
    }
}
```
Tu **décides** des champs, de leurs noms, de leur format. Les colonnes non listées ne sont
**jamais** exposées → plus sûr et plus stable.

---

## 🚀 Utiliser une Resource dans le contrôleur

```php
<?php
use App\Http\Resources\ArticleResource;

public function show(Article $article)
{
    return new ArticleResource($article);                  // un objet
}

public function index()
{
    return ArticleResource::collection(
        Article::with('user')->paginate(15)                // une collection paginée
    );
}

public function store(StoreArticleRequest $request)
{
    $article = $request->user()->articles()->create($request->validated());
    return (new ArticleResource($article))
        ->response()
        ->setStatusCode(201);                              // 201 + format Resource
}
```
> 💡 `ArticleResource::collection(...)` applique le format à **chaque** élément, et **conserve les
> métadonnées de pagination** (`data`, `links`, `meta`).

---

## 🧩 Champs conditionnels & relations

```php
<?php
public function toArray(Request $request): array
{
    return [
        'id'    => $this->id,
        'titre' => $this->titre,
        // Inclure l'auteur seulement s'il est chargé (évite un N+1 surprise)
        'auteur' => new UserResource($this->whenLoaded('user')),
        // Champ réservé à l'admin
        'vues' => $this->when($request->user()?->is_admin, $this->vues),
    ];
}
```
- `whenLoaded('user')` : n'ajoute la relation que si elle a été chargée avec `with('user')`.
- `when($condition, $valeur)` : n'expose un champ que si la condition est vraie.

---

## 🎯 Pourquoi c'est important (SSOT du rendu)

Le format de sortie vit **à un seul endroit** (la Resource). Si tu dois le changer, tu le fais
**une fois**, et **toutes** les réponses restent **cohérentes** → c'est le
**[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** appliqué à l'API. Un `json_encode` éparpillé dans
chaque contrôleur (comme dans certaines copies) viole DRY et dérive vite en incohérences.

---

## 🔎 À toi de chercher

> 1. Ajouter un **wrapper** ou des **métadonnées** globales avec `additional([...])` ou en
>    surchargeant `with()`.
> 2. La différence entre une **Resource** et une **ResourceCollection** dédiée.
> 3. Cherche comment **documenter** ton API (OpenAPI / Swagger) à partir de ces structures.

---

## 🎓 Ce qu'il faut retenir

- Une **API Resource** décrit **le JSON exact** d'une entité → tu maîtrises les champs exposés (sécurité + stabilité).
- `new XResource($modele)` pour un objet ; `XResource::collection($paginated)` pour une liste (avec pagination).
- `whenLoaded()` / `when()` pour des relations et champs **conditionnels**.
- Le format centralisé = **SSOT du rendu** (cohérence, pas de `json_encode` éparpillé).

👉 Leçon suivante : [Authentification d'API avec Sanctum](./03-sanctum.md)
