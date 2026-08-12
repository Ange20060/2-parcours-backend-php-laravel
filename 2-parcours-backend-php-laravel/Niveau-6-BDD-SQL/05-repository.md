# Leçon 6.5 — Le pattern Repository

> 🎯 **Objectif** : **isoler** tout l'accès à la base dans une couche dédiée (le **Repository**),
> pour un code découplé, testable et facile à faire évoluer. C'est la maturité qui manquait aux
> copies de tes stagiaires.

---

## 🤔 Le problème : du SQL éparpillé partout

Si chaque bout de code fait ses propres requêtes PDO, le SQL se retrouve **dispersé** dans toute
l'application. Résultat : impossible à tester sans base, et changer de stockage (ou de schéma)
oblige à modifier **des dizaines** d'endroits. C'est l'inverse de la
**[séparation des responsabilités](../Principes-Genie-Logiciel/04-SoC.md)**.

---

## 🗄️ La solution : une classe qui centralise l'accès aux données

Un **Repository** est une classe dont **l'unique responsabilité** est de lire/écrire une entité
en base. Le reste de l'application ne fait **jamais** de SQL directement : il **demande** au repository.

```php
<?php
declare(strict_types=1);

class UserRepository
{
    public function __construct(private PDO $pdo) {}   // PDO injecté (faible couplage)

    public function trouver(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function tous(): array
    {
        return $this->pdo->query("SELECT * FROM users ORDER BY nom")->fetchAll();
    }

    public function creer(string $nom, string $email): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email) VALUES (:n, :e)");
        $stmt->execute([':n' => $nom, ':e' => $email]);
        return (int) $this->pdo->lastInsertId();
    }

    public function supprimer(int $id): void
    {
        $this->pdo->prepare("DELETE FROM users WHERE id = :id")->execute([':id' => $id]);
    }
}
```
Le reste du code devient **lisible et sans SQL** :
```php
<?php
$users = new UserRepository($pdo);

$id = $users->creer("Marie", "marie@x.fr");
$marie = $users->trouver($id);
foreach ($users->tous() as $u) { echo $u['nom']; }
```

---

## 🎁 Ce que le Repository apporte

| Bénéfice | Pourquoi |
|---|---|
| **SoC** | Tout le SQL est **à un seul endroit**, par entité |
| **Faible couplage** | L'appli dépend de méthodes claires, pas de requêtes |
| **Testabilité** | On peut remplacer le repository par une **fausse** version en mémoire |
| **Évolutivité** | Changer de base / de schéma = modifier **une** classe |

---

## 🔌 Le rendre remplaçable : une interface

Pour pouvoir **échanger** l'implémentation (base réelle ↔ version de test), on programme contre
une **interface** (rappel : [interfaces & polymorphisme](../Niveau-3-POO/04-interfaces-polymorphisme.md),
et le **D** de [SOLID](../Principes-Genie-Logiciel/11-SOLID.md)) :

```php
<?php
interface UserRepositoryInterface
{
    public function trouver(int $id): ?array;
    public function tous(): array;
    public function creer(string $nom, string $email): int;
}

class PdoUserRepository implements UserRepositoryInterface { /* ... version PDO ... */ }
class InMemoryUserRepository implements UserRepositoryInterface { /* ... version test ... */ }
```
Un service métier reçoit l'interface par **injection**, sans savoir ce qu'il y a derrière :
```php
<?php
class InscriptionService
{
    public function __construct(private UserRepositoryInterface $users) {}
    // ... utilise $this->users->creer(...) sans connaître PDO
}
```

> 💡 **C'est exactement l'idée que Laravel généralise** avec **Eloquent** (Niveau 8) : tu
> manipules des objets `User::find(1)`, `User::create([...])`, et le SQL est géré pour toi. Mais
> parce que tu as fait le Repository **à la main**, tu sauras *ce qui se passe dessous* — et tu
> éviteras les pièges (N+1, requêtes non préparées) que tes stagiaires n'ont pas vus venir.

---

## 🔎 À toi de chercher

> 1. Différence entre le pattern **Repository** et le pattern **Active Record** (celui d'Eloquent).
> 2. Qu'est-ce qu'un **DTO** (Data Transfer Object) ? Pourquoi renvoyer un objet `User` typé
>    plutôt qu'un simple tableau associatif ?
> 3. Cherche « repository pattern php » : les débats sur son intérêt réel **avec** un ORM comme Eloquent.

---

## 🎓 Ce qu'il faut retenir

- Un **Repository** centralise **tout** l'accès à une entité en base (une responsabilité claire).
- Le reste de l'appli **ne fait jamais de SQL** : il passe par le repository → SoC + faible couplage.
- Le rendre **remplaçable** via une **interface** = testabilité + inversion des dépendances (SOLID).
- Eloquent (Laravel) automatise tout ça — mais comprendre le « pourquoi » ici te rend bien meilleur.

---

🎉 **Tu as fini le Niveau 6 !** Tu modélises des données, tu écris du **SQL**, tu accèdes à la
base **sans faille** (PDO + requêtes préparées), tu protèges l'intégrité (transactions) et tu
**isoles** l'accès (Repository). Fais les [exercices](./exercices.md) — puis place à **[Laravel](../Niveau-7-Laravel-Intro/)**,
qui va rendre tout ça élégant. 🚀
