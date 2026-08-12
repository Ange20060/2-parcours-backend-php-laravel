# Leçon 9.2 — La validation & les Form Requests

> 🎯 **Objectif** : valider les données entrantes proprement — d'abord en ligne, puis dans des
> classes dédiées (**Form Requests**). C'est le **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)**
> de Laravel, et un point que tes stagiaires ont souvent bâclé.

---

## ✅ Valider en ligne : `$request->validate()`

```php
<?php
public function store(Request $request)
{
    $donnees = $request->validate([
        'titre'   => 'required|string|max:255',
        'contenu' => 'required|string',
        'email'   => 'required|email',
        'age'     => 'nullable|integer|min:0|max:130',
    ]);

    // Le code ici ne s'exécute QUE si tout est valide (Fail Fast intégré)
    Article::create($donnees);
    return redirect()->route('articles.index');
}
```
Si la validation **échoue** :
- pour une requête **web** → Laravel **redirige en arrière** avec les erreurs et les anciennes valeurs ;
- pour une requête **JSON/API** → il renvoie automatiquement un **422** avec `{ "message": ..., "errors": {...} }`.

> 🧠 `validate()` **renvoie uniquement les champs validés** — utilise ce résultat (`$donnees`),
> pas `$request->all()`, pour éviter d'insérer des champs non prévus.

---

## 📏 Les règles de validation courantes

| Règle | Sens |
|---|---|
| `required` | obligatoire |
| `nullable` | facultatif (peut être null) |
| `string` / `integer` / `boolean` | type attendu |
| `email` / `url` | format |
| `max:255` / `min:3` | taille/valeur |
| `in:todo,in_progress,done` | valeur parmi une liste |
| `unique:users,email` | pas déjà en base |
| `exists:users,id` | doit exister en base |
| `confirmed` | un champ `x_confirmation` doit correspondre (mots de passe) |

On combine les règles avec `|` (ou un tableau) : `'titre' => ['required', 'string', 'max:255']`.

---

## 🧾 Les Form Requests : extraire la validation (SoC + DRY)

Mettre la validation dans le contrôleur le charge et empêche la **réutilisation**. Une **Form
Request** est une classe dédiée à la validation d'une action.

```bash
php artisan make:request StoreArticleRequest
```
```php
<?php
// app/Http/Requests/StoreArticleRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;    // ⚠️ autorisation : voir l'encadré ci-dessous
    }

    public function rules(): array
    {
        return [
            'titre'   => ['required', 'string', 'max:255'],
            'contenu' => ['required', 'string'],
        ];
    }

    public function messages(): array     // messages personnalisés (facultatif)
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max'      => 'Le titre ne doit pas dépasser 255 caractères.',
        ];
    }
}
```
Le contrôleur devient **minimal** — il suffit de **type-hint** la Form Request :
```php
<?php
public function store(StoreArticleRequest $request)
{
    Article::create($request->validated());   // déjà validé !
    return redirect()->route('articles.index');
}
```
La validation est **isolée** (SoC), **réutilisable** (store & update peuvent partager des règles)
et le contrôleur reste **fin**.

---

## 🚨 Le piège `authorize()` (leçon des corrections récentes)

La méthode **`authorize()`** d'une Form Request décide si la requête est **autorisée** :
- **`return false;`** → **toutes** les requêtes reçoivent un **403** (l'action devient inutilisable).
- **`return true;`** → aucune restriction d'autorisation (à réserver aux cas publics).

> ⚠️ **Un vrai bug rencontré en correction** : une Form Request laissée à `return false;` → la
> création de ressource répondait **403** en permanence, sans que personne ne s'en aperçoive (le
> test ne tournait pas). **Vérifie toujours** que `authorize()` renvoie ce que tu veux, et
> **teste** le cas passant **et** le cas refusé.

Pour une **vraie** autorisation métier (ownership, rôles), on utilise les **Policies** — leçon 9.3.

---

## 🖥️ Afficher les erreurs dans la vue

```blade
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $erreur)
            <li>{{ $erreur }}</li>
        @endforeach
    </ul>
@endif

<input name="titre" value="{{ old('titre') }}">
@error('titre') <span class="erreur">{{ $message }}</span> @enderror
```
`old('titre')` réaffiche la valeur saisie après une erreur (l'utilisateur ne retape pas tout).

---

## 🔎 À toi de chercher

> 1. La validation d'un **tableau** ou de champs imbriqués (`items.*.id`).
> 2. Créer une **règle de validation personnalisée** (`php artisan make:rule`).
> 3. `validated()` vs `safe()->only([...])` : récupérer un sous-ensemble des données validées.

---

## 🎓 Ce qu'il faut retenir

- **`$request->validate([...])`** valide en ligne ; échec → redirection (web) ou **422** (API).
- Utilise **`$request->validated()`** (pas `all()`) pour ne prendre que les champs validés.
- Les **Form Requests** isolent la validation (SoC + DRY) et gardent le contrôleur fin.
- **`authorize()`** : `false` bloque tout (403) — **vérifie-le et teste-le** (bug classique).
- Affiche les erreurs avec `@error` / `$errors`, réaffiche les saisies avec `old()`.

👉 Leçon suivante : [Middleware & autorisation (Policies)](./03-middleware-policies.md)
