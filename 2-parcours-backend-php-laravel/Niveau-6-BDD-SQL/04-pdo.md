# Leçon 6.4 — PDO : accéder à la base depuis PHP

> 🎯 **Objectif** : exécuter des requêtes SQL depuis PHP avec **PDO**, **sans jamais** ouvrir de
> faille d'injection SQL, grâce aux **requêtes préparées**.

---

## 🔌 Se connecter avec PDO

**PDO** (*PHP Data Objects*) est l'interface standard de PHP pour parler à une base — la **même**
API que la base soit SQLite, MySQL ou PostgreSQL.

```php
<?php
declare(strict_types=1);

// SQLite (un simple fichier)
$pdo = new PDO('sqlite:app.db');

// MySQL (exemple)
// $pdo = new PDO('mysql:host=localhost;dbname=app;charset=utf8mb4', 'user', 'motdepasse');

// TOUJOURS activer le mode exception (Fail Fast : une erreur SQL lève une exception)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
```
> 🧠 `ERRMODE_EXCEPTION` est **indispensable** : sinon PDO échoue **en silence** (corruption
> silencieuse). Avec, une erreur SQL devient une `PDOException` qu'on peut rattraper.

---

## 🛡️ Requêtes préparées : la règle ABSOLUE

**Jamais** de variable concaténée dans une requête (injection SQL — leçon 5.4). On utilise des
**paramètres liés** : la valeur n'est **jamais** interprétée comme du SQL.

```php
<?php
// ❌ INTERDIT — faille d'injection SQL
$pdo->query("SELECT * FROM users WHERE email = '$email'");

// ✅ Requête préparée avec paramètre nommé
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();     // une ligne (tableau associatif), ou false
```

---

## 👁️ Lire des données

```php
<?php
// Une seule ligne
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => 1]);
$user = $stmt->fetch();              // ['id' => 1, 'nom' => 'Marie', ...] ou false

// Plusieurs lignes
$stmt = $pdo->query("SELECT * FROM users ORDER BY nom");
$users = $stmt->fetchAll();          // tableau de tableaux associatifs

foreach ($users as $u) {
    echo $u['nom'] . PHP_EOL;
}
```
> 💡 `query()` convient pour une requête **sans variable**. Dès qu'il y a une **valeur
> utilisateur**, utilise **`prepare()` + `execute()`**.

---

## ➕✏️🗑️ Écrire des données

```php
<?php
// INSERT + récupérer l'id créé
$stmt = $pdo->prepare("INSERT INTO users (nom, email) VALUES (:nom, :email)");
$stmt->execute([':nom' => 'Paul', ':email' => 'paul@x.fr']);
$nouvelId = (int) $pdo->lastInsertId();

// UPDATE
$stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
$stmt->execute([':email' => 'nouveau@x.fr', ':id' => 1]);
echo $stmt->rowCount();   // nombre de lignes modifiées

// DELETE
$pdo->prepare("DELETE FROM users WHERE id = :id")->execute([':id' => 5]);
```

---

## 🔄 Les transactions : tout ou rien

Quand plusieurs écritures doivent réussir **ensemble** (un virement = un débit **et** un crédit),
on les encadre dans une **transaction** : si l'une échoue, on **annule tout** (`rollBack`).

```php
<?php
$pdo->beginTransaction();
try {
    $pdo->prepare("UPDATE comptes SET solde = solde - :m WHERE id = :id")
        ->execute([':m' => 100, ':id' => 1]);
    $pdo->prepare("UPDATE comptes SET solde = solde + :m WHERE id = :id")
        ->execute([':m' => 100, ':id' => 2]);
    $pdo->commit();          // les deux ont réussi → on valide
} catch (Throwable $e) {
    $pdo->rollBack();        // une erreur → on annule TOUT
    throw $e;                // on remonte (Fail Fast)
}
```
Sans transaction, si le crédit échoue après le débit, **l'argent disparaît**. La transaction
garantit l'**atomicité** (tout ou rien).

---

## 🔎 À toi de chercher

> 1. Différence entre les **paramètres nommés** (`:email`) et **positionnels** (`?`) dans PDO.
> 2. `fetch()` vs `fetchAll()` vs `fetchColumn()` — quand utiliser chacun ?
> 3. Cherche pourquoi on préfère PDO à l'ancienne extension `mysqli` (portabilité, requêtes préparées).

---

## 🎓 Ce qu'il faut retenir

- **PDO** = l'accès standard aux bases depuis PHP ; active **`ERRMODE_EXCEPTION`** (Fail Fast).
- **Requêtes préparées** (`prepare` + `execute([...])`) : **obligatoires** dès qu'il y a une
  valeur utilisateur → aucune injection SQL possible.
- Lire : `fetch()` (une ligne) / `fetchAll()` (plusieurs) ; écrire : `INSERT/UPDATE/DELETE` +
  `lastInsertId()` / `rowCount()`.
- **Transactions** (`beginTransaction`/`commit`/`rollBack`) pour les écritures « tout ou rien ».

👉 Leçon suivante : [Le pattern Repository](./05-repository.md)
