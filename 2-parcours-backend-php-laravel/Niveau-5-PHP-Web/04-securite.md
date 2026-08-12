# Leçon 5.4 — Sécurité de base

> 🎯 **Objectif** : connaître et neutraliser les failles web les plus courantes — **XSS**,
> **injection SQL**, **CSRF** — et stocker un mot de passe **correctement**. La sécurité n'est
> pas une option : c'est une responsabilité d'ingénieur.

---

## 💉 1. L'injection SQL

Une **injection SQL** survient quand on **concatène** une entrée utilisateur dans une requête.
Un attaquant peut alors exécuter son propre SQL.

```php
<?php
// ❌ CATASTROPHE : si $email vaut  x'; DROP TABLE users; --
$sql = "SELECT * FROM users WHERE email = '$email'";
```
✅ **La parade : les requêtes préparées** (paramètres liés — vu en détail au Niveau 6) :
```php
<?php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);   // la valeur n'est JAMAIS interprétée comme du SQL
```
> 🧠 Règle absolue : **jamais** de variable concaténée dans une requête. **Toujours** des
> requêtes préparées. Laravel/Eloquent le fait pour toi par défaut.

---

## 🧨 2. Le XSS (Cross-Site Scripting)

Le **XSS** survient quand on **affiche** une entrée utilisateur **sans l'échapper** : un
attaquant injecte du `<script>` qui s'exécute dans le navigateur des autres visiteurs.

```php
<?php
// ❌ Si $message contient  <script>vol_de_cookies()</script>  → il s'exécute !
echo $message;

// ✅ Échapper à l'affichage : le HTML est neutralisé (affiché, pas exécuté)
echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
```
`htmlspecialchars` transforme `<` en `&lt;`, `"` en `&quot;`… Le code s'**affiche** au lieu de
s'exécuter.
> 💡 **Règle** : **échappe TOUTE donnée** venant de l'utilisateur **au moment de l'afficher**.
> En Laravel, la syntaxe Blade `{{ $var }}` échappe **automatiquement** (tu l'as peut-être vu au
> parcours web) — mais il faut comprendre **pourquoi**.

---

## 🎭 3. Le CSRF (Cross-Site Request Forgery)

Le **CSRF** : un site malveillant fait exécuter, à ton insu, une action sur un site où tu es
**déjà connecté** (ex : un `<form>` caché qui poste un virement). La parade : un **jeton CSRF**
unique, exigé sur chaque formulaire sensible.

```php
<?php
session_start();
// Générer un jeton et le mettre dans le formulaire
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));
```
```html
<form method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    ...
</form>
```
```php
<?php
// Vérifier à la réception
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(419);
    exit("Jeton CSRF invalide.");
}
```
> 💡 Laravel gère le CSRF **automatiquement** (directive `@csrf` dans les formulaires). Encore une
> fois : le framework automatise, mais l'ingénieur **comprend**.

---

## 🔑 4. Stocker un mot de passe : `password_hash`

**Ne stocke JAMAIS un mot de passe en clair**, ni avec `md5`/`sha1` (cassables). Utilise le
hachage dédié, qui gère le **sel** et l'algorithme :

```php
<?php
// À l'inscription : hacher
$hash = password_hash($motDePasse, PASSWORD_DEFAULT);
// -> à stocker en base (jamais le mot de passe lui-même)

// À la connexion : vérifier
if (password_verify($motDePasseSaisi, $hash)) {
    echo "Connexion réussie";
} else {
    echo "Identifiants invalides";
}
```
- Le **même** mot de passe produit un hash **différent** à chaque fois (grâce au sel) — c'est normal.
- `password_verify` compare correctement, en temps constant.

> ⚠️ Réflexe corrections récentes : la vérification d'un login doit renvoyer **401** en cas
> d'échec, et **jamais** révéler *lequel* (email ou mot de passe) est faux.

---

## 🧰 Autres réflexes essentiels

- **Secrets hors du code** : mots de passe de base, clés d'API → dans un fichier `.env`
  **non committé**, pas en dur (**[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** de la config).
- **Ne renvoie pas les erreurs internes** au client en production (pas de stack trace visible).
- **Principe du moindre privilège** : chaque compte/clé n'a que les droits strictement nécessaires.

---

## 🔎 À toi de chercher

> 1. Lis le **OWASP Top 10** (les 10 failles web les plus répandues) — au moins les intitulés.
> 2. `random_bytes` / `bin2hex` vs `rand()` : pourquoi utiliser un générateur **cryptographique**
>    pour un jeton ou un mot de passe temporaire ?
> 3. Cherche `hash_equals()` : pourquoi comparer des jetons avec, plutôt qu'avec `===` ?

---

## 🎓 Ce qu'il faut retenir

- **Injection SQL** → **requêtes préparées** (jamais de concaténation).
- **XSS** → **`htmlspecialchars`** sur toute donnée affichée.
- **CSRF** → un **jeton** unique par formulaire sensible.
- **Mots de passe** → **`password_hash` / `password_verify`** (jamais en clair, jamais `md5`).
- **Secrets dans `.env`**, moindre privilège, pas d'erreurs internes exposées.

👉 Leçon suivante : [Retourner du JSON (première API)](./05-api-json.md)
