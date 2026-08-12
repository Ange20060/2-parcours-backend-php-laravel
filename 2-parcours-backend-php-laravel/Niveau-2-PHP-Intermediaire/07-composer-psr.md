# Leçon 2.7 — Composer, autoloading & les standards PSR

> 🎯 **Objectif** : comprendre **Composer** (le gestionnaire de dépendances), l'**autoloading
> PSR-4** (le chargement automatique des classes) et les **standards PSR** — les fondations de
> tout projet PHP moderne, et le socle sur lequel Laravel est construit.

---

## 📦 Composer, à quoi ça sert ?

**Composer** gère les **bibliothèques** (dépendances) de ton projet et le **chargement des
classes**. C'est l'équivalent de `npm` (JS) ou `pip` (Python).

```bash
composer init                    # crée un composer.json (interactif)
composer require monolog/monolog # installe une bibliothèque + ses dépendances
composer install                 # installe tout ce que composer.json déclare
composer dump-autoload           # régénère l'autoloader
```

Deux fichiers clés :
- **`composer.json`** : la liste **déclarée** de tes dépendances (ce que TU écris).
- **`composer.lock`** : les versions **exactes** installées (à committer, pour que toute l'équipe
  ait les mêmes) → c'est du **[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)**.
- Le dossier **`vendor/`** : le code installé (à **ne pas committer** → `.gitignore`).

---

## 🔁 L'autoloading : fini les `require` à la main

Sans autoloading, il faut `require` **chaque** fichier de classe manuellement — vite ingérable.
L'**autoloading PSR-4** charge automatiquement une classe **au moment où on l'utilise**, à
partir d'une correspondance **namespace → dossier**.

Dans `composer.json` :
```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```
Cela veut dire : « le namespace `App\` correspond au dossier `src/` ». Donc :

| Classe | Fichier attendu |
|---|---|
| `App\Calculatrice` | `src/Calculatrice.php` |
| `App\Models\User` | `src/Models/User.php` |
| `App\Services\PaiementService` | `src/Services/PaiementService.php` |

Après avoir déclaré ça, lance **`composer dump-autoload`**, puis :

```php
<?php
// index.php
require 'vendor/autoload.php';   // UNE seule ligne, au point d'entrée

use App\Calculatrice;

$calc = new Calculatrice();       // chargée automatiquement, sans require manuel 🎉
```

> 💡 C'est **exactement** ainsi que Laravel charge ses milliers de classes. Le comprendre ici te
> rendra Laravel beaucoup plus limpide (Niveau 7).

---

## 🏷️ Les namespaces (rappel & rôle)

Un **namespace** évite les collisions de noms : deux classes `User` peuvent coexister si elles
sont dans des espaces différents (`App\Models\User` vs `App\Admin\User`).

```php
<?php
namespace App\Services;      // en 1re ligne du fichier (après <?php)

class PaiementService { /* ... */ }
```
```php
<?php
namespace App;

use App\Services\PaiementService;   // importer une classe d'un autre namespace

$service = new PaiementService();
```

---

## 📏 Les standards PSR (PHP-FIG)

Les **PSR** (*PHP Standard Recommendations*) sont des conventions communes à toute la
communauté PHP. Les plus importantes à connaître :

| PSR | Ce que ça normalise |
|---|---|
| **PSR-1 / PSR-12** | Le **style de code** (indentation 4 espaces, accolades, nommage…) |
| **PSR-4** | L'**autoloading** (namespace → dossier, vu ci-dessus) |
| **PSR-3** | Une interface commune pour les **loggers** |

Pourquoi s'y plier ? Parce qu'un code standardisé se lit **partout pareil** : un dev qui rejoint
le projet est immédiatement à l'aise. C'est du **[Explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)**
à l'échelle de la communauté.

### Vérifier/corriger son style automatiquement
Des outils **formatent** ton code aux normes en une commande :
```bash
composer require --dev laravel/pint    # ou "friendsofphp/php-cs-fixer"
./vendor/bin/pint                       # corrige tout le style automatiquement
./vendor/bin/pint --test                # signale sans corriger (utile en CI)
```
> 🎯 **Réflexe pro** : passe un formateur (Pint) **avant chaque commit**. Le style ne se discute
> plus, la machine s'en charge. (Tes stagiaires oublient souvent cette étape — pas toi.)

---

## 🔎 À toi de chercher

> 1. Mets en place un mini-projet : `composer.json` avec `"App\\": "src/"`, une classe
>    `App\Calculatrice`, et un `index.php` qui l'utilise via `vendor/autoload.php`. **Aucun**
>    `require` manuel de la classe.
> 2. Différence entre `composer require` et `composer require --dev` (dépendances de production
>    vs de développement).
> 3. Cherche **Packagist** (packagist.org) : le grand annuaire des bibliothèques PHP installables.

---

## 🎓 Ce qu'il faut retenir

- **Composer** gère les dépendances (`require`, `install`) et l'**autoloading**.
- `composer.json` (déclaré) + `composer.lock` (versions exactes, à committer) + `vendor/` (à ignorer).
- **Autoloading PSR-4** : namespace `App\` → dossier `src/` ; on charge `vendor/autoload.php`
  **une fois**, et les classes se chargent **toutes seules**.
- Les **PSR** standardisent style (PSR-12) et autoloading (PSR-4) ; **Pint** formate automatiquement.

---

🎉 **Tu as fini les leçons du Niveau 2 !** Tu manipules tableaux, chaînes, exceptions,
fichiers/JSON et dates **proprement**, et tu comprends **Composer + l'autoloading** — la porte
d'entrée vers Laravel. Direction les [exercices](./exercices.md), puis le **[Niveau 3 : la
POO](../Niveau-3-POO/)**.
