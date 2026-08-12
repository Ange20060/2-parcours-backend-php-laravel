# ✅ Niveau 7 — Corrigés (Introduction à Laravel)

> ⚠️ Essaie d'abord. Les chemins et commandes supposent un projet Laravel standard.

---

## Exercice 1 — Structure
| Élément | Rôle |
|---|---|
| `routes/web.php` | Déclare les URL de l'appli et leur destination |
| `app/Http/Controllers/` | Les **contrôleurs** (orchestrent la réponse) |
| `resources/views/` | Les **vues** Blade (l'affichage) |
| `app/Models/` | Les **modèles** Eloquent (les données) |
| `.env` | La configuration **par environnement** (SSOT des secrets/URLs) |
| `config/` | La configuration de l'application |

C'est du **MVC** : Modèle (données) / Vue (affichage) / Contrôleur (logique) — chaque couche a
**une responsabilité** ([SoC](../Principes-Genie-Logiciel/04-SoC.md)).

## Exercice 2 — Route
```php
<?php
// routes/web.php
use Illuminate\Support\Facades\Route;

Route::get('/bonjour', fn () => 'Bonjour Laravel');
Route::get('/bonjour/{nom}', fn (string $nom) => "Bonjour $nom");
```

## Exercice 3 — Contrôleur
```php
<?php
// app/Http/Controllers/PageController.php
namespace App\Http\Controllers;

class PageController extends Controller
{
    public function accueil(): string
    {
        return 'Bienvenue sur la page d\'accueil';
    }
}
```
```php
<?php
// routes/web.php
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'accueil']);
```

## Exercice 4 — Vue Blade
```php
<?php
// dans le contrôleur
public function accueil()
{
    return view('accueil', ['nom' => 'Marie']);
}
```
```blade
{{-- resources/views/accueil.blade.php --}}
<h1>Bonjour {{ $nom }}</h1>
```
`{{ $nom }}` **échappe automatiquement** la sortie (équivalent de `htmlspecialchars`) : Blade
protège du **XSS** par défaut. (Pour afficher du HTML brut — rare et risqué — c'est `{!! !!}`.)

## Exercice 5 — Artisan & Tinker
- `php artisan list` : toutes les commandes disponibles.
- `php artisan tinker` : un REPL pour exécuter du code dans le contexte de l'appli (tester un
  modèle, une fonction…).
- Commandes `make:` utiles : `make:controller`, `make:model`, `make:migration`,
  `make:request`, `make:test`… (Laravel **génère** le squelette → gain de temps, cohérence, DRY).

## Exercice 6 — Injection de dépendances
```php
<?php
// app/Services/SalutationService.php
namespace App\Services;

class SalutationService
{
    public function saluer(string $nom): string
    {
        return "Bonjour $nom, bienvenue !";
    }
}
```
```php
<?php
// dans un contrôleur — Laravel INJECTE automatiquement le service
use App\Services\SalutationService;

public function accueil(SalutationService $salutation)
{
    return $salutation->saluer('Marie');
}
```
Le contrôleur **déclare** avoir besoin d'un `SalutationService` (type-hint) ; le **conteneur**
de Laravel le construit et l'injecte. Le contrôleur ne dépend **pas** d'une construction
concrète → **inversion des dépendances** (le **D** de SOLID), **faible couplage**, et code
**testable** (on peut injecter une fausse version en test).

---

## 🎉 Bilan du Niveau 7
Laravel n'est plus une boîte noire : tu vois le **MVC**, le **routing**, **Blade** et surtout
le **conteneur** qui applique l'injection de dépendances. Tes bases POO + principes paient déjà.
👉 [Niveau 8 : Eloquent & Migrations](../Niveau-8-Laravel-Eloquent/)
