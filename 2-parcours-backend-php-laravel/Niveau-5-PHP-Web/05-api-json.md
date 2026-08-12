# Leçon 5.5 — Retourner du JSON (ta première API)

> 🎯 **Objectif** : construire une petite **API** en PHP pur — recevoir une requête, renvoyer du
> **JSON** avec le bon **Content-Type** et le bon **code de statut**. C'est le brouillon de ce que
> Laravel automatisera au Niveau 10.

---

## 🌐 Une API, c'est quoi ?

Une **API** (Application Programming Interface) est un service que d'**autres programmes**
consomment (une appli mobile, un front React, un autre serveur). Au lieu de renvoyer du **HTML**
(pour un humain), elle renvoie des **données**, presque toujours en **JSON**.

```
   App mobile / front  ──── requête ────▶  ton API PHP
                       ◀──── JSON ────────
```

---

## 🧾 Renvoyer du JSON correctement

Deux choses **obligatoires** : l'en-tête **`Content-Type: application/json`** et l'encodage JSON.

```php
<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$produits = [
    ['id' => 1, 'nom' => 'Clavier', 'prix' => 25.0],
    ['id' => 2, 'nom' => 'Souris',  'prix' => 12.5],
];

echo json_encode($produits, JSON_UNESCAPED_UNICODE);
```
> 💡 Le `Content-Type` dit au client « je t'envoie du JSON » ; sans lui, il pourrait l'interpréter
> comme du texte brut. `JSON_UNESCAPED_UNICODE` garde les accents lisibles.

---

## 🔢 Renvoyer le bon code de statut

Un point **crucial** (et souvent raté par les débutants) : le code HTTP doit refléter le
résultat. On le fixe avec `http_response_code(...)`.

```php
<?php
header('Content-Type: application/json; charset=utf-8');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id === null) {
    http_response_code(400);                       // requête invalide
    echo json_encode(['erreur' => 'id manquant ou invalide']);
    exit;
}

$produits = [1 => 'Clavier', 2 => 'Souris'];
if (!isset($produits[$id])) {
    http_response_code(404);                       // pas trouvé
    echo json_encode(['erreur' => 'produit introuvable']);
    exit;
}

http_response_code(200);                           // succès
echo json_encode(['id' => $id, 'nom' => $produits[$id]]);
```

---

## 📥 Lire un corps JSON entrant (POST/PUT)

Une API reçoit souvent du **JSON dans le corps** de la requête (pas un formulaire). On le lit
sur le flux `php://input` :

```php
<?php
$donnees = json_decode(file_get_contents('php://input'), true);

if (!is_array($donnees) || !isset($donnees['nom'])) {
    http_response_code(422);                        // validation
    echo json_encode(['erreur' => 'le champ "nom" est requis']);
    exit;
}
// ... créer la ressource
http_response_code(201);                            // Created
echo json_encode(['message' => 'créé', 'nom' => $donnees['nom']]);
```

---

## 🧩 Router à la main (l'idée que Laravel généralise)

Une API dirige les requêtes selon la **méthode** et le **chemin**. En PHP pur, c'est manuel :

```php
<?php
$methode = $_SERVER['REQUEST_METHOD'];
$chemin  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($methode === 'GET' && $chemin === '/produits') {
    // lister
} elseif ($methode === 'POST' && $chemin === '/produits') {
    // créer
} else {
    http_response_code(404);
    echo json_encode(['erreur' => 'route inconnue']);
}
```
> 🧠 Fais-le une fois **à la main** pour comprendre ce qui se passe. Ensuite, tu apprécieras
> **Laravel** : `Route::get('/produits', ...)`, la validation, les *Resources*, les codes de
> statut… tout ce que tu écris ici péniblement devient **une ligne propre** (Niveau 7 à 10).

---

## 🧪 Tester ton API

```bash
curl http://localhost:8000/api.php?id=1
curl -X POST -d '{"nom":"Écran"}' http://localhost:8000/api.php
```
Ou avec **Postman** / l'extension REST de VSCode. Vérifie le **corps** ET le **code de statut**.

---

## 🔎 À toi de chercher

> 1. Qu'est-ce que **CORS** et pourquoi une API appelée depuis un navigateur en a besoin ?
> 2. Cherche la structure d'une réponse d'erreur JSON **cohérente** (ex : `{ "message": ...,
>    "errors": {...} }`) — un format unique pour toute l'API (**[SSOT](../Principes-Genie-Logiciel/05-SSOT.md)** du rendu).
> 3. Compare ton routeur manuel avec `Route::apiResource(...)` de Laravel : que gagne-t-on ?

---

## 🎓 Ce qu'il faut retenir

- Une **API** renvoie des **données JSON** (pour des programmes), pas du HTML.
- **Obligatoire** : en-tête `Content-Type: application/json` + `json_encode`.
- Renvoie **le bon code de statut** (200/201/400/404/422…) — il fait partie du contrat.
- Lire un corps JSON : `json_decode(file_get_contents('php://input'), true)`.
- Tu viens de faire **à la main** ce que Laravel rendra élégant : routing, validation, JSON.

---

🎉 **Tu as fini le Niveau 5 !** Tu comprends le **cycle HTTP**, tu **valides et sécurises** les
entrées, tu gères **sessions** et **mots de passe**, et tu renvoies du **JSON** avec les bons
codes. Fais les [exercices](./exercices.md), puis passe aux **[bases de données](../Niveau-6-BDD-SQL/)** —
pour enfin **stocker** durablement tes données.
