# Leçon 7.4 — Les vues Blade

> 🎯 **Objectif** : générer du HTML dynamique avec **Blade**, le moteur de templates de Laravel —
> proprement, sans mélanger PHP et HTML en désordre, et **protégé du XSS par défaut**.

---

## 🖼️ Une vue = l'affichage (la « V » de MVC)

Une **vue** produit le HTML envoyé à l'utilisateur. En Laravel, on l'écrit en **Blade** : du
HTML enrichi de directives `@...` et d'affichages `{{ }}`. Les fichiers sont dans
`resources/views/` et finissent par **`.blade.php`**.

```php
<?php
// dans un contrôleur : on rend une vue en lui passant des données
public function accueil()
{
    return view('accueil', ['nom' => 'Marie']);
}
```

```blade
{{-- resources/views/accueil.blade.php --}}
<h1>Bonjour {{ $nom }}</h1>
```

---

## 🔐 `{{ }}` — afficher (et échapper automatiquement)

```blade
<p>{{ $article->titre }}</p>
```

`{{ $var }}` affiche la valeur **en l'échappant automatiquement** (comme `htmlspecialchars`) →
**protection XSS intégrée**. Rappelle-toi la leçon 5.4 : ici, Laravel le fait **pour toi**.

> ⚠️ Pour afficher du **HTML brut** (rare, et risqué) : `{!! $html !!}`. Ne l'utilise **jamais**
> sur une donnée venant de l'utilisateur.

---

## 🔀 Les directives : conditions et boucles

```blade
@if ($article->published)
    <span>Publié</span>
@else
    <span>Brouillon</span>
@endif

<ul>
    @foreach ($articles as $article)
        <li>{{ $article->titre }}</li>
    @endforeach
</ul>

@forelse ($articles as $article)
    <li>{{ $article->titre }}</li>
@empty
    <li>Aucun article.</li>
@endforelse
```

> 💡 `@forelse / @empty` gère élégamment le cas « liste vide » — un réflexe propre.

---

## 🧱 Le layout : ne pas répéter l'en-tête et le pied de page (DRY)

On définit un **gabarit** commun (header, footer…) avec des « trous » (`@yield`), et chaque page
le **remplit** — au lieu de recopier la structure partout.

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head><title>@yield('titre')</title></head>
<body>
    <header>Mon site</header>
    <main>
        @yield('contenu')
    </main>
    <footer>© 2026</footer>
</body>
</html>
```

```blade
{{-- resources/views/articles/index.blade.php --}}
@extends('layouts.app')

@section('titre', 'Les articles')

@section('contenu')
    <h1>Articles</h1>
    @foreach ($articles as $article)
        <p>{{ $article->titre }}</p>
    @endforeach
@endsection
```

La structure vit **à un seul endroit** (le layout) → **[DRY](../Principes-Genie-Logiciel/01-DRY.md)**.

> 🔎 Laravel moderne propose aussi les **composants Blade** (`<x-carte>`) — une façon encore plus
> propre de réutiliser des morceaux d'interface. À explorer (voir « à toi de chercher »).

---

## 🔗 Générer des URL et des liens proprement

```blade
<a href="{{ route('articles.show', $article) }}">Voir</a>
<form action="{{ route('articles.store') }}" method="POST">
    @csrf                       {{-- jeton anti-CSRF, obligatoire sur les formulaires --}}
    <input name="titre">
    <button>Créer</button>
</form>
```

> 💡 `@csrf` insère le **jeton CSRF** (leçon 5.4) automatiquement. `route('articles.show', ...)`
> génère l'URL à partir du **nom** de la route → pas d'URL codée en dur (**SSOT**).

---

## 🔎 À toi de chercher

> 1. Les **composants Blade** (`php artisan make:component`, syntaxe `<x-...>`) : réutiliser un
>    bloc d'interface avec des paramètres.
> 2. `@auth` / `@guest` : afficher du contenu selon que l'utilisateur est connecté ou non.
> 3. Comment afficher les **erreurs de validation** dans un formulaire avec `@error('champ')`.

---

## 🎓 Ce qu'il faut retenir

- Une **vue Blade** (`.blade.php`) génère le HTML ; on lui passe des données depuis le contrôleur (`view('nom', [...])`).
- **`{{ $var }}`** affiche **en échappant** → protection **XSS** par défaut (`{!! !!}` = brut, dangereux).
- Directives : `@if`, `@foreach`, `@forelse/@empty`.
- **Layouts** (`@extends`/`@yield`/`@section`) évitent de répéter la structure → DRY.
- `@csrf` sur les formulaires, `route('nom')` pour les URL (pas d'URL en dur).

👉 Leçon suivante : [Le conteneur de services &amp; l&#39;injection de dépendances](./05-conteneur-di.md)
