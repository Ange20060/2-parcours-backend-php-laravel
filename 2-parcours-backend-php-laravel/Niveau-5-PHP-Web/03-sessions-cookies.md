# Leçon 5.3 — Sessions et cookies

> 🎯 **Objectif** : maintenir un **état** entre les requêtes (rester connecté, un panier…) alors
> que HTTP est **sans mémoire**. C'est le rôle des **cookies** et des **sessions**.

---

## 🤔 Le problème : HTTP est « sans état » (stateless)

Chaque requête HTTP est **indépendante** : le serveur ne se « souvient » pas de la précédente.
Alors comment rester **connecté** d'une page à l'autre ? Avec les **cookies** et **sessions**.

---

## 🍪 Les cookies : une petite donnée stockée côté client

Un **cookie** est une petite valeur que le serveur demande au **navigateur** de conserver et de
**renvoyer** à chaque requête suivante.

```php
<?php
// Déposer un cookie (valable 1 heure)
setcookie('theme', 'sombre', time() + 3600, '/');

// Le lire (à la requête SUIVANTE)
$theme = $_COOKIE['theme'] ?? 'clair';
```
> ⚠️ Un cookie est **stocké chez le client** : l'utilisateur peut le **voir et le modifier**.
> **N'y mets jamais** de donnée sensible ou de confiance (pas de « est_admin=1 » !).

---

## 🗄️ Les sessions : l'état stocké côté serveur

Une **session** stocke les données **sur le serveur** ; le client ne détient qu'un
**identifiant de session** (dans un cookie). Bien plus sûr pour les données sensibles.

```php
<?php
session_start();                 // À APPELER en tout premier, avant tout affichage

$_SESSION['utilisateur_id'] = 42;      // écrire dans la session
$_SESSION['nom'] = "Marie";

// À la requête suivante (après un nouveau session_start()) :
$id = $_SESSION['utilisateur_id'] ?? null;
```
- `session_start()` doit être appelé **avant** toute sortie (`echo`, HTML…).
- Les données vivent côté serveur ; seul un **ID opaque** circule côté client.

### Terminer une session (déconnexion)
```php
<?php
session_start();
$_SESSION = [];        // vide les données
session_destroy();     // détruit la session
```

---

## 🔐 Exemple : un « connecté / déconnecté » minimal

```php
<?php
declare(strict_types=1);
session_start();

// Simuler une connexion
if (($_POST['action'] ?? '') === 'login') {
    $_SESSION['utilisateur_id'] = 42;
}
// Déconnexion
if (($_GET['action'] ?? '') === 'logout') {
    session_destroy();
    header('Location: /');   // redirige
    exit;
}

if (isset($_SESSION['utilisateur_id'])) {
    echo "Connecté (utilisateur #{$_SESSION['utilisateur_id']})";
} else {
    echo "Non connecté";
}
```

> 💡 **Cookie vs session** : le **cookie** stocke côté **client** (visible/modifiable), la
> **session** stocke côté **serveur** (sûr, référencé par un cookie d'ID). Pour l'authentification,
> c'est **la session** (ou, pour une API, un **token** — Niveau 10 avec Sanctum).

---

## 🛡️ Bonnes pratiques de sécurité

- Cookies sensibles : options **`HttpOnly`** (inaccessible au JavaScript → anti-vol par XSS) et
  **`Secure`** (envoyé seulement en HTTPS).
- Régénérer l'ID de session à la connexion (`session_regenerate_id(true)`) → anti « session fixation ».
- Ne **jamais** stocker un mot de passe ou une info de confiance dans un cookie brut.

---

## 🔎 À toi de chercher

> 1. Les options `HttpOnly`, `Secure`, `SameSite` d'un cookie : contre quelles attaques protègent-elles ?
> 2. Où PHP stocke-t-il physiquement les fichiers de session par défaut ? (`session.save_path`)
> 3. En Laravel, cherche le helper **`session()`** et les **drivers** de session (file, database,
>    redis) : le framework généralise ce que tu viens de voir.

---

## 🎓 Ce qu'il faut retenir

- HTTP est **sans état** ; **cookies** et **sessions** ajoutent la « mémoire ».
- **Cookie** = stocké **chez le client** (visible/modifiable) → jamais de secret dedans.
- **Session** = stockée **sur le serveur**, référencée par un cookie d'ID → pour l'auth.
- `session_start()` **avant** toute sortie ; `session_destroy()` pour déconnecter.
- Sécurise les cookies (`HttpOnly`, `Secure`, `SameSite`).

👉 Leçon suivante : [Sécurité de base](./04-securite.md)
