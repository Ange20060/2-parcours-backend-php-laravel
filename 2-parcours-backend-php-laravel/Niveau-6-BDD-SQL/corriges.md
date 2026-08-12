# ✅ Niveau 6 — Corrigés (Bases de données & SQL)

> ⚠️ Essaie d'abord. Les exemples PHP utilisent **SQLite** via PDO (`new PDO('sqlite:blog.db')`)
> — testable sans serveur.

---

## Exercice 1 — Modélisation
- `users(id PK, nom, email UNIQUE)`
- `articles(id PK, titre, contenu, user_id FK → users.id)`
- `commentaires(id PK, contenu, article_id FK → articles.id)`

Relations : `users 1—N articles`, `articles 1—N commentaires`. On ne recopie pas le nom de
l'auteur dans `articles` : on stocke **une seule fois** l'auteur dans `users` et on y **réfère**
par `user_id`. Ainsi, changer un nom se fait **à un endroit** (**SSOT** / normalisation). Recopier
créerait des données **contradictoires**.

## Exercice 2 — Créer les tables
```sql
CREATE TABLE users (
    id    INTEGER PRIMARY KEY AUTOINCREMENT,
    nom   TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE
);

CREATE TABLE articles (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    titre    TEXT NOT NULL,
    contenu  TEXT NOT NULL,
    user_id  INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

## Exercice 3 — CRUD SQL
```sql
INSERT INTO users (nom, email) VALUES ('Marie', 'marie@x.fr');
SELECT * FROM users;
SELECT * FROM users WHERE id = 1;
UPDATE users SET email = 'nouvelle@x.fr' WHERE id = 1;
DELETE FROM users WHERE id = 1;
```

## Exercice 4 — PDO & requêtes préparées
```php
<?php
declare(strict_types=1);

function creerUser(PDO $pdo, string $nom, string $email): int
{
    // Requête PRÉPARÉE : les valeurs passent par des paramètres liés, jamais concaténées
    $stmt = $pdo->prepare("INSERT INTO users (nom, email) VALUES (:nom, :email)");
    $stmt->execute([':nom' => $nom, ':email' => $email]);
    return (int) $pdo->lastInsertId();
}

$pdo = new PDO('sqlite:blog.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // Fail Fast : erreurs = exceptions
```
❌ **Dangereux** : `"INSERT ... VALUES ('$email')"`. Si `$email` vaut
`x'); DROP TABLE users;--`, on exécute une injection SQL. La requête préparée rend ça
**impossible** : la valeur n'est jamais interprétée comme du SQL.

## Exercice 5 — Jointures
```sql
-- Articles avec le nom de l'auteur
SELECT articles.titre, users.nom AS auteur
FROM articles
JOIN users ON users.id = articles.user_id;

-- Nombre d'articles par auteur
SELECT users.nom, COUNT(articles.id) AS nb_articles
FROM users
LEFT JOIN articles ON articles.user_id = users.id
GROUP BY users.id;
```

## Exercice 6 — Transactions
```php
<?php
function virement(PDO $pdo, int $de, int $vers, float $montant): void
{
    $pdo->beginTransaction();
    try {
        $debit = $pdo->prepare("UPDATE comptes SET solde = solde - :m WHERE id = :id");
        $debit->execute([':m' => $montant, ':id' => $de]);

        $credit = $pdo->prepare("UPDATE comptes SET solde = solde + :m WHERE id = :id");
        $credit->execute([':m' => $montant, ':id' => $vers]);

        $pdo->commit();          // tout a réussi → on valide
    } catch (Throwable $e) {
        $pdo->rollBack();        // une opération a échoué → on annule TOUT
        throw $e;                // on remonte l'erreur (Fail Fast)
    }
}
```
Sans transaction, si le **crédit** échoue après le **débit**, l'argent **disparaît**. La
transaction garantit « **tout ou rien** » (atomicité).

## Exercice 7 — Repository
```php
<?php
declare(strict_types=1);

class UserRepository
{
    public function __construct(private PDO $pdo) {}   // injection → faible couplage

    public function trouver(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function tous(): array
    {
        return $this->pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creer(string $nom, string $email): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email) VALUES (:n, :e)");
        $stmt->execute([':n' => $nom, ':e' => $email]);
        return (int) $this->pdo->lastInsertId();
    }
}
```
> Tout le SQL est **isolé** dans le repository (**SoC**). Le reste de l'appli manipule des
> méthodes claires, pas des requêtes. Si un jour tu changes de base, tu ne touches **qu'ici**.

---

## 🎉 Bilan du Niveau 6
Tu modélises, tu écris du SQL, tu accèdes aux données **sans faille** (requêtes préparées),
tu protèges l'intégrité (transactions) et tu **isoles l'accès** (Repository). Eloquent, au
niveau suivant, va te faire gagner un temps fou — mais tu sauras **ce qu'il fait** sous le capot.
👉 [Niveau 7 : Introduction à Laravel](../Niveau-7-Laravel-Intro/)
