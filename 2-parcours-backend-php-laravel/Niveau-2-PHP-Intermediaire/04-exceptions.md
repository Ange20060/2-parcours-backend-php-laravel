# Leçon 2.4 — La gestion d'erreurs : les exceptions

> 🎯 **Objectif** : gérer les erreurs proprement avec les **exceptions** (`throw`, `try/catch`),
> au lieu de codes de retour fragiles. C'est le mécanisme de **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)** en PHP.

---

## 💥 Le problème des « codes d'erreur »

Une vieille approche : renvoyer `false` ou `-1` en cas de problème. Résultat : on **oublie** de
vérifier, et l'erreur se propage en silence (corruption silencieuse).

```php
<?php
function diviser($a, $b) {
    if ($b === 0) {
        return false;   // ❌ et si l'appelant oublie de tester ? "false" devient 0 plus loin...
    }
    return $a / $b;
}
```

---

## 🚨 La bonne approche : lever une exception

Une **exception** interrompt le flux normal et « remonte » jusqu'à ce que quelqu'un la
**rattrape**. On **ne peut pas l'ignorer** silencieusement.

```php
<?php
declare(strict_types=1);

function diviser(float $a, float $b): float
{
    if ($b === 0.0) {
        throw new InvalidArgumentException("Division par zéro interdite.");
    }
    return $a / $b;
}
```

---

## 🎣 Rattraper : `try / catch / finally`

```php
<?php
try {
    $resultat = diviser(10, 0);
    echo $resultat;                     // non exécuté si l'exception est levée
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage(); // "Erreur : Division par zéro interdite."
} finally {
    echo "Fin du traitement.";           // TOUJOURS exécuté (erreur ou pas)
}
```

- `try` : le code « à risque ».
- `catch (TypeException $e)` : que faire si **ce type** d'exception survient. `$e->getMessage()`
  donne le message.
- `finally` (optionnel) : s'exécute **dans tous les cas** (utile pour fermer un fichier, une connexion…).

---

## 🧬 La hiérarchie des exceptions

Toutes héritent de la classe `Exception` (elle-même sous `Throwable`). Il en existe des
**spécialisées**, à choisir selon le sens :

| Exception                    | Quand l'utiliser                                                   |
| ---------------------------- | ------------------------------------------------------------------ |
| `InvalidArgumentException` | Un argument invalide a été passé                                |
| `RuntimeException`         | Une erreur survenue**à l'exécution** (fichier illisible…) |
| `LogicException`           | Une erreur de logique de programmation (bug)                       |
| `DomainException`          | Une valeur hors du domaine métier autorisé                       |

> 💡 Choisir le **bon type** rend le code **[explicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)** :
> le nom de l'exception documente déjà la nature du problème.

---

## 🏷️ Créer sa propre exception métier

Pour des erreurs **métier**, on crée des exceptions dédiées — le code devient très lisible :

```php
<?php
class SoldeInsuffisantException extends Exception {}

function retirer(float $solde, float $montant): float
{
    if ($montant > $solde) {
        throw new SoldeInsuffisantException("Solde insuffisant : $solde € < $montant €");
    }
    return $solde - $montant;
}

try {
    retirer(100, 500);
} catch (SoldeInsuffisantException $e) {
    echo "Refusé : " . $e->getMessage();
}
```

On peut **cibler** ce cas précis dans un `catch`, sans attraper toutes les autres erreurs.

---

## 🎯 Attraper plusieurs types

```php
<?php
try {
    // ...
} catch (SoldeInsuffisantException $e) {
    // cas métier précis
} catch (InvalidArgumentException | TypeError $e) {
    // plusieurs types d'un coup (opérateur |)
} catch (Throwable $e) {
    // filet de sécurité : ATTRAPE TOUT (à utiliser en dernier recours)
}
```

> ⚠️ Attraper `Throwable` « pour être tranquille » et **ne rien faire** est un anti-pattern
> (ça masque les bugs). Attrape ce que tu sais **traiter**, laisse remonter le reste.

---

## 🔎 À toi de chercher

> 1. Différence entre une **`Exception`** et une **`Error`** en PHP (division par zéro, appel de
>    méthode inexistante…). Les deux implémentent `Throwable`.
> 2. Que contiennent `$e->getCode()`, `$e->getLine()`, `$e->getFile()` ? À quoi sert le
>    **chaînage** d'exceptions (`new Exception("...", 0, $exceptionPrecedente)`) ?
> 3. En Laravel, cherche comment un « exception handler » global transforme une exception en
>    **réponse HTTP** (404, 500, 422) — tu le retrouveras au Niveau 9.

---

## 🎓 Ce qu'il faut retenir

- On **lève** une erreur avec `throw new Exception("message")` — impossible à ignorer en silence.
- On la **rattrape** avec `try / catch` ; `finally` s'exécute **toujours**.
- Choisis le **type** qui a du sens (`InvalidArgumentException`, `RuntimeException`…) et crée des
  **exceptions métier** pour les cas importants.
- N'attrape que ce que tu sais **traiter** ; ne masque pas les bugs avec un `catch` vide.

👉 Leçon suivante : [Fichiers et JSON](./05-fichiers-json.md)
