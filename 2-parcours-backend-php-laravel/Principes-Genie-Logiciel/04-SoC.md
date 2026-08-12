# 4 — SoC (Separation of Concerns / Séparation des responsabilités)

> 🎯 **Le principe** : chaque module, classe ou fonction a **une seule responsabilité
> claire**. On sépare les préoccupations : la logique métier, l'accès aux données, l'affichage…
>
> 💥 **Ce que ça tue** : la fonction de 800 lignes qui fait tout — valider, calculer,
> enregistrer en base, envoyer un email et afficher du HTML.

---

## Le problème (❌)

Une seule fonction qui mélange **tout** : validation, base de données, email, affichage.

```php
<?php
function inscrireUtilisateur($data)
{
    // 1. Validation
    if (empty($data['email']) || !str_contains($data['email'], '@')) {
        echo "Email invalide";
        return;
    }
    // 2. Accès base de données (SQL en dur ici)
    $pdo = new PDO('mysql:host=localhost;dbname=app', 'root', '');
    $stmt = $pdo->prepare("INSERT INTO users (email) VALUES (?)");
    $stmt->execute([$data['email']]);

    // 3. Envoi d'email
    mail($data['email'], "Bienvenue", "Merci de votre inscription !");

    // 4. Affichage HTML
    echo "<h1>Bienvenue " . $data['email'] . " !</h1>";
}
```

Impossible à tester, à réutiliser, ou à modifier sans risque. Changer la base **casse**
l'affichage. C'est le contraire de l'ingénierie.

---

## La solution (✅)

Chaque responsabilité dans **son** module. On **assemble** ensuite.

```php
<?php
// Responsabilité 1 : valider
class ValidateurInscription
{
    public function valider(array $data): void
    {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide");
        }
    }
}

// Responsabilité 2 : persister (accès aux données)
class UtilisateurRepository
{
    public function __construct(private PDO $pdo) {}

    public function creer(string $email): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (email) VALUES (?)");
        $stmt->execute([$email]);
    }
}

// Responsabilité 3 : notifier
class ServiceEmail
{
    public function envoyerBienvenue(string $email): void
    {
        mail($email, "Bienvenue", "Merci de votre inscription !");
    }
}

// Orchestration : chaque brique fait UNE chose, on les compose
class InscriptionService
{
    public function __construct(
        private ValidateurInscription $validateur,
        private UtilisateurRepository $repository,
        private ServiceEmail $email,
    ) {}

    public function inscrire(array $data): void
    {
        $this->validateur->valider($data);
        $this->repository->creer($data['email']);
        $this->email->envoyerBienvenue($data['email']);
    }
}
```

L'**affichage HTML**, lui, reste dans la couche présentation (une vue), **séparée**. Chaque
partie est **testable** et **remplaçable** indépendamment.

> 🧩 C'est exactement la philosophie du modèle **MVC** de Laravel (Modèle / Vue / Contrôleur) :
> une séparation des responsabilités que tu retrouveras au Niveau 7.

---

## 🔗 Liens avec les autres principes

- SoC est le moteur de la **[haute cohésion & faible couplage](./06-cohesion-couplage.md)**.
- C'est aussi le **S** de **[SOLID](./11-SOLID.md)** (Single Responsibility Principle).

---

## 🏋️ Mini-exercice

Reprends la fonction `inscrireUtilisateur` ❌ ci-dessus et **liste** (juste par écrit) les
**responsabilités distinctes** qu'elle mélange. Puis propose **un nom de classe** pour chacune.

> Corrigé dans [corriges.md](./corriges.md).
