# ✅ Principes — Corrigés

> ⚠️ Il y a souvent **plusieurs bons refactorings**. L'important est que tu saches **nommer
> le principe** appliqué et que le comportement reste identique.

---

## Corrigés des mini-exercices (fiches)

**DRY (fiche 1)**
```php
<?php
function messageSolde(string $nom, float $solde): string
{
    return "Bonjour $nom, votre solde est de " . number_format($solde, 2) . " €";
}
echo messageSolde("Marie", 1500.5);
echo messageSolde("Paul", 230.0);
echo messageSolde("Sofia", 89.99);
```

**KISS (fiche 2)**
```php
<?php
function estMajeur(int $age): string
{
    return $age >= 18 ? "oui" : "non";
}
```

**YAGNI (fiche 3)**
```php
<?php
function envoyerEmailBienvenue(string $email, string $prenom): void
{
    mail($email, "Bienvenue", "Bonjour $prenom, bienvenue !");
}
```
> On refuse SMS / multi-langue / file d'attente **parce qu'aucun de ces besoins n'existe
> aujourd'hui** : les ajouter maintenant complexifie le code et risque de ne jamais servir.
> On les ajoutera **quand** un vrai ticket les demandera.

**SoC (fiche 4)** — responsabilités mélangées dans `inscrireUtilisateur` :
1. **Validation** → `ValidateurInscription`
2. **Persistance** → `UtilisateurRepository`
3. **Notification** → `ServiceEmail`
4. **Affichage** → une **vue** (couche présentation)

**SSOT (fiche 5)** — placer `const FRAIS_LIVRAISON = 4.99;` (ou une entrée de config `.env`)
dans **un** fichier de configuration, et faire référence à cette constante dans les 3 fichiers.

**Cohésion/Couplage (fiche 6)**
```php
<?php
class RapportService
{
    public function __construct(private Logger $logger) {}   // injecté, plus créé en dur
    public function generer(): string
    {
        $this->logger->info("Génération du rapport");
        return "... rapport ...";
    }
}
```

**Fail Fast (fiche 7)**
```php
<?php
function creerUtilisateur(string $email, int $age): array
{
    if (!str_contains($email, '@')) {
        throw new InvalidArgumentException("Email invalide.");
    }
    if ($age < 0 || $age > 130) {
        throw new InvalidArgumentException("Âge hors limites (0–130).");
    }
    return ['email' => $email, 'age' => $age];
}
```

**Explicite (fiche 8)**
```php
<?php
const PRIORITE_HAUTE = 1;
envoyer(message: $msg, priorite: PRIORITE_HAUTE, accuseReception: true);
```

**Composition (fiche 9)**
```php
<?php
interface MoyenPaiement { public function payer(float $montant): void; }
class Carte    implements MoyenPaiement { public function payer(float $m): void { /* ... */ } }
class Paypal   implements MoyenPaiement { public function payer(float $m): void { /* ... */ } }
class Virement implements MoyenPaiement { public function payer(float $m): void { /* ... */ } }
```

**Boy Scout (fiche 10)**
```php
<?php
function moyenne(array $valeurs): float
{
    if (count($valeurs) === 0) {
        throw new InvalidArgumentException("Liste vide.");   // bonus : Fail Fast
    }
    return array_sum($valeurs) / count($valeurs);
}
```
Améliorations : nom parlant, types, suppression du tableau `$z` inutile (code mort),
`array_sum` (plus simple), garde contre la division par zéro.

---

## Corrigés des exercices transversaux

### Exercice 1 — Code spaghetti
**Violations** : SoC (validation + calcul + BDD + email + HTML dans une fonction), DRY (calcul
TTC écrit deux fois, taux `0.20` en dur deux fois → aussi SSOT), Fail Fast (aucune validation).
```php
<?php
declare(strict_types=1);

const TAUX_TVA = 0.20;

function calculerTTC(float $ht): float { return $ht * (1 + TAUX_TVA); }

class ValidateurCommande {
    public function valider(array $d): void {
        if (($d['prix'] ?? 0) <= 0)  throw new InvalidArgumentException("Prix invalide.");
        if (($d['qte'] ?? 0) <= 0)   throw new InvalidArgumentException("Quantité invalide.");
        if (!filter_var($d['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide.");
        }
    }
}
class CommandeRepository { public function __construct(private PDO $pdo) {}
    public function enregistrer(float $total): void {
        $stmt = $this->pdo->prepare("INSERT INTO commandes (total) VALUES (?)");
        $stmt->execute([$total]);
    }
}
class ServiceEmail { public function confirmer(string $email, float $total): void {
    mail($email, "Commande", "Total : " . number_format($total, 2) . " €");
}}
// Orchestration (le contrôleur, en Laravel), affichage laissé à une vue séparée.
```

### Exercice 2 — Booléens magiques
```php
<?php
$user = creerUtilisateur(
    email: "marie@x.fr",
    actif: true,
    newsletter: false,
    envoyerBienvenue: true,
);
function creerUtilisateur(string $email, bool $actif, bool $newsletter, bool $envoyerBienvenue): array { /* ... */ }
```

### Exercice 3 — Open/Closed
```php
<?php
interface ModeLivraison { public function calculerFrais(float $poids): float; }
class Standard    implements ModeLivraison { public function calculerFrais(float $p): float { return $p * 2; } }
class Express     implements ModeLivraison { public function calculerFrais(float $p): float { return $p * 5; } }
class PointRelais implements ModeLivraison { public function calculerFrais(float $p): float { return $p * 1.5; } }
// Nouveau mode = nouvelle classe, AUCUNE modification de l'existant.

function fraisLivraison(ModeLivraison $mode, float $poids): float {
    return $mode->calculerFrais($poids);
}
```

### Exercice 4 — Classe pieuvre
Responsabilités distinctes → 4 classes :
- `Utilisateur` (l'entité, ses données)
- `UtilisateurRepository` (sauvegarde/lecture en base)
- `ServiceEmail` (envoi d'emails)
- `GenerateurFacturePDF` (PDF)
- `StatistiquesService` (calculs)

### Exercice 5 — Sur-ingénierie
Le besoin réel « prix TTC en euros » se résout par une **fonction** (ou une petite classe
`Prix`). On supprime les 6 classes abstraites et la factory : elles violent **KISS** (complexité
inutile) et **YAGNI** (flexibilité imaginaire). On réintroduira une abstraction **le jour où**
un 2ᵉ cas réel (autre devise, autre taxe) apparaîtra.

### Exercice 6 — Refactoring complet
```php
<?php
declare(strict_types=1);

function messageInscription(string $nom, string $email): string
{
    return "$nom a été inscrit avec l'email $email";   // DRY + SSOT du message
}

class DepotUtilisateurFichier
{
    public function __construct(private string $chemin = 'users.txt') {}
    public function enregistrer(string $ligne): void      // SoC : écrire
    {
        file_put_contents($this->chemin, $ligne . "\n", FILE_APPEND);
    }
}

function inscrire(string $nom, string $email, int $age, DepotUtilisateurFichier $depot): string
{
    if ($age <= 0) {                                       // Fail Fast + explicite
        throw new InvalidArgumentException("Âge invalide.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Email invalide.");
    }
    $message = messageInscription($nom, $email);
    $depot->enregistrer($message);                         // persistance
    return $message;                                       // l'affichage se fait AILLEURS (SoC)
}
```

---

## 🎓 Le réflexe à garder

Après **chaque** exercice, verbalise : *« J'ai appliqué [principe] parce que [problème], ce
qui rend le code [plus testable / plus simple / plus sûr] »*. C'est cette **verbalisation** qui
transforme une règle mémorisée en **réflexe d'ingénieur**.
