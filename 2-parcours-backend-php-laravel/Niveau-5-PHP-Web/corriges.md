# ✅ Niveau 5 — Corrigés (PHP & le Web)

> ⚠️ Essaie d'abord. `declare(strict_types=1);` en tête. Lance avec `php -S localhost:8000`.

---

## Exercice 1 — Requête GET
```php
<?php
$nom = $_GET['nom'] ?? 'visiteur';
echo "Bonjour " . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
```
> `htmlspecialchars` neutralise le HTML/JS injecté : c'est la base de la protection XSS.

## Exercice 2 — Formulaire POST
```php
<?php
$erreurs = [];
$nom = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom   = trim($_POST['nom'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: '';

    if ($nom === '')   { $erreurs[] = "Le nom est obligatoire."; }
    if ($email === '') { $erreurs[] = "Email invalide."; }

    if (!$erreurs) {
        echo "Merci " . htmlspecialchars($nom) . " (" . htmlspecialchars($email) . ")";
    }
}
?>
<form method="post">
    <input name="nom" value="<?= htmlspecialchars($nom) ?>">
    <input name="email" value="<?= htmlspecialchars($email) ?>">
    <button>Envoyer</button>
</form>
<?php foreach ($erreurs as $e) { echo "<p>" . htmlspecialchars($e) . "</p>"; } ?>
```

## Exercice 3 — Codes de statut
```php
<?php
$produits = [1 => 'Clavier', 2 => 'Souris'];
$id = $_GET['id'] ?? null;

if ($id === null || !ctype_digit((string) $id)) {
    http_response_code(400);
    echo "Requête invalide (id manquant ou non numérique).";
} elseif (!isset($produits[(int) $id])) {
    http_response_code(404);
    echo "Produit introuvable.";
} else {
    http_response_code(200);
    echo "Produit : " . htmlspecialchars($produits[(int) $id]);
}
```

## Exercice 4 — Hachage de mot de passe
```php
<?php
function creerHash(string $motDePasse): string
{
    return password_hash($motDePasse, PASSWORD_DEFAULT);
}
function verifier(string $motDePasse, string $hash): bool
{
    return password_verify($motDePasse, $hash);
}

$h1 = creerHash("secret123");
$h2 = creerHash("secret123");
var_dump($h1 === $h2);                  // false : le sel diffère à chaque fois
var_dump(verifier("secret123", $h1));   // true
var_dump(verifier("secret123", $h2));   // true
```
> `password_hash`/`password_verify` gèrent le **sel** et l'algorithme automatiquement. Ne
> réinvente jamais ça toi-même.

## Exercice 5 — Sessions
```php
<?php
session_start();

if (isset($_GET['reset'])) {
    session_destroy();
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$_SESSION['visites'] = ($_SESSION['visites'] ?? 0) + 1;
echo "Visites : " . $_SESSION['visites'];
echo ' — <a href="?reset=1">réinitialiser</a>';
```

## Exercice 6 — XSS
```php
<?php
session_start();
$_SESSION['messages'] ??= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['messages'][] = $_POST['message'] ?? '';
}
?>
<form method="post"><input name="message"><button>Poster</button></form>
<?php
foreach ($_SESSION['messages'] as $msg) {
    // ❌ DANGEREUX : echo $msg;  → <script>alert('xss')</script> s'exécuterait
    // ✅ SÛR :
    echo "<p>" . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</p>";
}
```
Sans échappement, un message contenant du `<script>` serait **exécuté** dans le navigateur des
autres visiteurs (faille **XSS**). `htmlspecialchars` transforme `<` en `&lt;` : le code
s'**affiche** au lieu de s'exécuter.

## Exercice 7 — API JSON
```php
<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

if ($action === 'liste') {
    echo json_encode([
        ['id' => 1, 'nom' => 'Clavier', 'prix' => 25.0],
        ['id' => 2, 'nom' => 'Souris', 'prix' => 12.5],
    ]);
} else {
    http_response_code(400);
    echo json_encode(['erreur' => "Action inconnue : $action"]);
}
```
> Un `Content-Type: application/json` + un bon **code de statut**, c'est déjà l'essence d'une
> API REST. Laravel automatisera tout ça au Niveau 10.

---

## 🎉 Bilan du Niveau 5
Tu comprends le **cycle requête/réponse**, tu **valides et échappes** les entrées, tu gères
sessions et mots de passe **sécurisés**, et tu renvoies du JSON. Ces fondations rendront
Laravel **limpide**.
👉 [Niveau 6 : Bases de données & SQL](../Niveau-6-BDD-SQL/)
