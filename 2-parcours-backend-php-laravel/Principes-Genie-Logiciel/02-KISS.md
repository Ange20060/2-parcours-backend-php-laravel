# 2 — KISS (Keep It Simple, Stupid)

> 🎯 **Le principe** : construis la solution **la plus simple** qui résout **vraiment** le
> problème. La simplicité est une **cible**, pas un accident.
>
> 💥 **Ce que ça tue** : le code « malin », alambiqué, que personne (toi y compris, dans un
> mois) n'arrive à relire.

---

## Le problème (❌)

Du code « astucieux » qui veut impressionner. Il est illisible et fragile.

```php
<?php
// "Est-ce que le nombre est pair ?" — version cryptique
function estPair(int $n): bool
{
    return !(($n & 1) ^ 0) === true ? true : (($n % 2 == 0) ?: false);
}
```

---

## La solution (✅)

```php
<?php
function estPair(int $n): bool
{
    return $n % 2 === 0;
}
```

Même résultat, mais **évident**. On comprend en une seconde, sans réfléchir.

---

## Un exemple plus réaliste (backend)

❌ Une usine à gaz « configurable » pour envoyer un message :

```php
<?php
$notifier = new NotificationManagerFactory()
    ->withStrategy(new EmailStrategyBuilder()->build())
    ->withRetryPolicy(new ExponentialBackoffPolicy(3, 1000))
    ->create();
$notifier->dispatch($message);
```

✅ Si le besoin réel est juste « envoyer un email » :

```php
<?php
function envoyerEmail(string $destinataire, string $sujet, string $corps): void
{
    // ... appel à la lib d'email
}

envoyerEmail($client->email, "Confirmation", $corps);
```

Tu ajouteras la complexité **le jour où** tu en auras réellement besoin (voir [YAGNI](./03-YAGNI.md)).

---

## 🧠 Comment reconnaître un manque de KISS

- Tu dois **relire trois fois** ta propre fonction pour comprendre ce qu'elle fait.
- Il y a plus de **couches d'abstraction** que d'étapes réelles dans le problème.
- Tu es **fier** de la « ruse » du code — souvent mauvais signe. 😉

> 💬 « La simplicité est le préalable de la fiabilité. » — Edsger Dijkstra.

---

## 🔗 Liens avec les autres principes

- KISS et **[YAGNI](./03-YAGNI.md)** vont de pair : ne pas anticiper = rester simple.
- Un code simple est plus facile à garder **[explicite](./08-explicite-vs-implicite.md)**.

---

## 🏋️ Mini-exercice

Simplifie cette fonction sans changer son comportement :

```php
<?php
function estMajeur(int $age): string
{
    if ($age >= 18) {
        return "oui";
    } else {
        if ($age < 18) {
            return "non";
        }
    }
    return "non";
}
```

> Corrigé dans [corriges.md](./corriges.md).
