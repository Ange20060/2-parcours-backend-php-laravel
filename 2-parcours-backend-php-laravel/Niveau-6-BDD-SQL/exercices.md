# 📝 Niveau 6 — Exercices (Bases de données & SQL)

Pour tester sans serveur, tu peux utiliser **SQLite** (fichier local) avec PDO — aucune
installation lourde. `declare(strict_types=1);` en tête. **🎯 But** à chaque exercice.
Corrigés : [corriges.md](./corriges.md).

> 🎯 **Exigence sécurité** : **toujours** des **requêtes préparées** (jamais de variable
> concaténée dans une requête SQL → injection SQL). C'est non négociable.

---

## Exercice 1 — Modéliser un schéma 📐
> 🎯 **But** : concevoir un schéma relationnel normalisé (SSOT).

Sur papier (ou en commentaire), modélise un mini-blog : `users`, `articles`, `commentaires`.
Indique les **clés primaires**, les **clés étrangères** et les **relations** (un user a
plusieurs articles ; un article a plusieurs commentaires). Explique pourquoi on **ne recopie
pas** le nom de l'auteur dans chaque article (lien avec [SSOT](../Principes-Genie-Logiciel/05-SSOT.md)).

---

## Exercice 2 — Créer les tables 🏗️
> 🎯 **But** : écrire du SQL `CREATE TABLE` avec clés et contraintes.

Écris le SQL qui crée les tables `users(id, nom, email)` et `articles(id, titre, contenu,
user_id)` avec la clé étrangère `user_id → users(id)`. `email` doit être **unique**.

---

## Exercice 3 — CRUD en SQL 🔁
> 🎯 **But** : maîtriser `INSERT`, `SELECT`, `UPDATE`, `DELETE`.

Écris les requêtes pour : insérer un user ; lister tous les users ; récupérer un user par id ;
modifier son email ; le supprimer.

---

## Exercice 4 — PDO & requêtes préparées 🔐
> 🎯 **But** : accéder à la base depuis PHP **sans faille d'injection**.

Avec PDO (SQLite), écris une fonction `creerUser(PDO $pdo, string $nom, string $email): int`
qui insère un user via une **requête préparée** et retourne son id. Montre pourquoi
`"INSERT ... VALUES ('$email')"` (concaténé) serait **dangereux**.

---

## Exercice 5 — Les jointures 🔗
> 🎯 **But** : combiner deux tables avec `JOIN`.

Écris une requête qui liste chaque article **avec le nom de son auteur** (jointure entre
`articles` et `users`). Puis une requête qui compte le **nombre d'articles par auteur**
(`GROUP BY`).

---

## Exercice 6 — Transactions 💳
> 🎯 **But** : garantir l'intégrité avec une **transaction** (tout ou rien).

Simule un virement entre deux comptes (`UPDATE` sur deux lignes). Encadre les deux opérations
dans une **transaction** : si la seconde échoue, la première doit être **annulée** (`rollBack`).
Explique pourquoi c'est indispensable ici (Fail Fast + intégrité).

---

## Exercice 7 — Pattern Repository 🌟
> 🎯 **But** : isoler l'accès aux données (SoC + faible couplage).

Crée une classe `UserRepository` qui reçoit un `PDO` par injection et expose `trouver(int $id)`,
`tous()`, `creer(string $nom, string $email)`. Le reste de l'appli ne fait **jamais** de SQL
directement : il passe par le repository.
> 💡 C'est le pattern que Laravel/Eloquent généralise. Comprendre le « pourquoi » ici te fera
> gagner beaucoup de temps ensuite.

---

👉 Correction : [corriges.md](./corriges.md)
