# Leçon 5.2 — Recevoir des données (GET / POST)

> 🎯 **Objectif** : récupérer les données envoyées par le client (query string, formulaire) et
> les **valider** — car **on ne fait jamais confiance** à une entrée utilisateur.

---

## 📥 Les superglobales `$_GET` et `$_POST`

PHP range automatiquement les données reçues dans des tableaux **superglobaux** :

- **`$_GET`** : les paramètres de la **query string** (`?nom=Marie`).
- **`$_POST`** : les données d'un **formulaire** envoyé en POST.

```php
<?php
// URL : http://localhost:8000/bonjour.php?nom=Marie
$nom = $_GET['nom'] ?? 'visiteur';    // ?? : valeur par défaut si absent
echo "Bonjour " . htmlspecialchars($nom);
```
> ⚠️ Toujours utiliser **`?? défaut`** : une clé absente déclencherait un warning. C'est du
> **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)** doux (on gère l'absence proprement).

---

## 📮 Traiter un formulaire POST

```html
<!-- formulaire.html -->
<form method="post" action="traitement.php">
    <input name="nom">
    <input name="email">
    <button>Envoyer</button>
</form>
```
```php
<?php
// traitement.php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    // ... valider puis traiter
}
```
> 💡 `$_SERVER['REQUEST_METHOD']` te dit la méthode (`GET`, `POST`…) : utile pour n'exécuter le
> traitement que sur un vrai envoi POST.

---

## 🛡️ La règle d'or : ne JAMAIS faire confiance à l'entrée

Toute donnée venant du client peut être **absente, vide, malformée ou malveillante**. Deux
réflexes **systématiques** :

1. **Valider** en entrée (Fail Fast) — est-ce présent ? au bon format ?
2. **Échapper** en sortie (leçon 5.4 sur le XSS) — avant d'afficher.

---

## ✅ Valider proprement avec `filter_input` / `filter_var`

PHP fournit des filtres tout prêts, plus fiables qu'une vérification maison :

```php
<?php
// Valider un email reçu en POST
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if ($email === false || $email === null) {
    // email absent ou invalide
    http_response_code(422);
    exit("Email invalide.");
}

// Valider / convertir un entier
$age = filter_input(INPUT_GET, 'age', FILTER_VALIDATE_INT);

// Nettoyer une chaîne
$nom = trim($_POST['nom'] ?? '');
if ($nom === '') {
    http_response_code(422);
    exit("Le nom est obligatoire.");
}
```
| Filtre | Rôle |
|---|---|
| `FILTER_VALIDATE_EMAIL` | email valide ? |
| `FILTER_VALIDATE_INT` / `FLOAT` | entier / décimal valide ? |
| `FILTER_VALIDATE_URL` | URL valide ? |

> 🧠 **Anti-pattern à éviter** : utiliser directement `$_POST['prix']` dans un calcul sans le
> convertir/valider. C'est du texte non fiable → bug ou faille. Valide **d'abord**.

---

## 🧩 Exemple complet : mini-inscription

```php
<?php
declare(strict_types=1);

$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($nom === '')        { $erreurs[] = "Le nom est obligatoire."; }
    if ($email === false || $email === null) { $erreurs[] = "Email invalide."; }

    if (!$erreurs) {
        echo "Bienvenue " . htmlspecialchars($nom) . " !";
        // ... enregistrer en base (Niveau 6)
    }
}
foreach ($erreurs as $e) {
    echo "<p>" . htmlspecialchars($e) . "</p>";
}
```

---

## 🔎 À toi de chercher

> 1. Qu'est-ce que **`$_REQUEST`** et pourquoi vaut-il mieux **l'éviter** (ambiguïté GET/POST/COOKIE) ?
> 2. Comment récupérer un **corps JSON** brut (API) ? (Indice : `file_get_contents('php://input')`
>    puis `json_decode`.) — c'est ce que Laravel fait pour toi.
> 3. Cherche la différence entre **valider** (rejeter l'invalide) et **assainir/sanitize**
>    (nettoyer une valeur).

---

## 🎓 Ce qu'il faut retenir

- Données reçues : **`$_GET`** (query string), **`$_POST`** (formulaire) — toujours avec `?? défaut`.
- **Ne fais jamais confiance** à l'entrée : **valide** en entrée, **échappe** en sortie.
- Utilise **`filter_input` / `filter_var`** (`FILTER_VALIDATE_EMAIL`, `_INT`…) plutôt que des
  vérifications maison fragiles.
- Renvoie un **422** quand la validation échoue.

👉 Leçon suivante : [Sessions et cookies](./03-sessions-cookies.md)
