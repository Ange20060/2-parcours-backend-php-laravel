# 8 — Explicite > Implicite

> 🎯 **Le principe** : rends le comportement **évident et intentionnel**. On doit comprendre ce
> que fait le code **en le lisant**, sans deviner ni connaître des règles cachées.
>
> 💥 **Ce que ça tue** : la **« magie »** — un comportement difficile à tracer, qui dépend de
> conventions invisibles ou d'effets de bord cachés.

---

## Le problème (❌)

### Des paramètres « booléens magiques »
```php
<?php
creerCommande($client, true, false, true);
// ...true, false, true ??? Impossible de savoir ce que ça veut dire sans ouvrir la fonction.
```

### Des valeurs cachées et des effets de bord
```php
<?php
function calculer($x)
{
    global $tauxSecret;          // d'où vient ce taux ? mystère
    $_SESSION['dernier'] = $x;   // effet de bord caché : ça modifie la session !
    return $x * $tauxSecret;
}
```

---

## La solution (✅)

### Des arguments nommés / des enums qui se lisent
```php
<?php
creerCommande(
    client: $client,
    urgente: true,
    cadeau: false,
    envoyerEmail: true,
);
```
PHP 8 permet les **arguments nommés** : le sens saute aux yeux. Encore mieux, un objet ou une
enum pour des options : `PrioriteCommande::Urgente`.

### Des dépendances explicites, pas de magie globale
```php
<?php
function calculer(float $x, float $taux): float
{
    return $x * $taux;   // tout ce dont la fonction a besoin est VISIBLE dans sa signature
}
```

Ce qui entre et ce qui sort est **clair**. Pas de `global`, pas d'effet de bord surprise.

---

## 🧠 Règles concrètes pour rester explicite

- **Noms parlants** : `estActif()` plutôt que `check()` ; `montantTTC` plutôt que `m`.
- **Types partout** : arguments typés, type de retour typé, `declare(strict_types=1);`.
- **Évite les `global`** et les variables cachées ; passe les dépendances en paramètre.
- **Rends les effets de bord visibles** : une fonction qui `calcule` ne devrait pas aussi
  écrire en base en douce.
- **Pas de « nombres magiques »** : `if ($statut === 3)` → `if ($statut === Statut::Payee)`.

> 💬 Le Zen de Python le dit bien (et c'est vrai partout) : *« Explicit is better than implicit. »*

---

## 🔗 Liens avec les autres principes

- Soutient le **[Fail Fast](./07-fail-fast.md)** et le **[SSOT](./05-SSOT.md)**.
- Un code explicite est plus facile à garder **[simple](./02-KISS.md)** et à faire évoluer.

---

## 🏋️ Mini-exercice

Rends cet appel explicite (indice : arguments nommés + constante nommée) :

```php
<?php
envoyer($msg, 1, true);
// où 1 = priorité haute, true = accusé de réception
```

> Corrigé dans [corriges.md](./corriges.md).
