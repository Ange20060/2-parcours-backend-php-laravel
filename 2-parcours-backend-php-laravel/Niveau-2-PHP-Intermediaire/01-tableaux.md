# Leçon 2.1 — Les tableaux (indexés & associatifs)

> 🎯 **Objectif** : maîtriser la structure de données la plus utilisée du backend — le
> **tableau** PHP — sous ses deux formes : **indexée** (une liste) et **associative** (clé → valeur).

---

## 📋 Tableau indexé (une liste)

Des valeurs ordonnées, accessibles par un **indice** commençant à **0** :

```php
<?php
declare(strict_types=1);

$fruits = ["pomme", "banane", "cerise"];

echo $fruits[0];          // pomme
echo count($fruits);      // 3
$fruits[] = "kiwi";       // ajoute à la fin
```

> 💡 `$fruits[]` = « ajoute à la fin » (équivalent du `.append()` de Python).

---

## 🗂️ Tableau associatif (clé → valeur)

Chaque valeur est rangée sous une **clé** (du texte). C'est l'équivalent du **dictionnaire**
Python — et la brique de base pour représenter **un objet** (un utilisateur, un produit…) :

```php
<?php
$user = [
    "nom"   => "Marie",
    "age"   => 25,
    "actif" => true,
];

echo $user["nom"];        // Marie
$user["email"] = "m@x.fr"; // ajoute une clé
```

> ⚠️ Lire une clé inexistante déclenche un **warning** et renvoie `null`. Pour un accès sûr,
> utilise l'opérateur `??` : `$email = $user["email"] ?? "inconnu";` (Fail Fast maîtrisé).

---

## 🔁 Parcourir un tableau : `foreach`

### Les valeurs
```php
<?php
foreach ($fruits as $fruit) {
    echo $fruit . PHP_EOL;
}
```

### Les paires clé → valeur
```php
<?php
foreach ($user as $cle => $valeur) {
    echo "$cle : $valeur" . PHP_EOL;
}
```

> 💡 En backend, tu passes ton temps à parcourir des **collections** (résultats d'une base de
> données, listes d'objets). `foreach` est ta boucle n°1.

---

## 🧺 Tableaux imbriqués (la structure du monde réel)

Un tableau peut contenir des tableaux. Une **liste de tableaux associatifs** = une collection
d'objets (un panier, un catalogue, des utilisateurs) :

```php
<?php
$panier = [
    ["produit" => "Clavier", "prix" => 25.0, "qte" => 2],
    ["produit" => "Souris",  "prix" => 12.5, "qte" => 3],
];

foreach ($panier as $ligne) {
    echo "{$ligne['produit']} : {$ligne['qte']} × {$ligne['prix']} €" . PHP_EOL;
}
```
> 💡 Note les **guillemets simples** pour la clé à l'intérieur d'une chaîne à guillemets doubles :
> `"{$ligne['produit']}"`. C'est la bonne syntaxe.

---

## 🛠️ Quelques fonctions de tableaux essentielles

| Fonction | Rôle |
|---|---|
| `count($t)` | Nombre d'éléments |
| `in_array($v, $t)` | La valeur existe-t-elle ? (retourne `bool`) |
| `array_key_exists($k, $t)` | La **clé** existe-t-elle ? |
| `array_keys($t)` / `array_values($t)` | La liste des clés / des valeurs |
| `sort($t)` / `rsort($t)` | Trier (croissant / décroissant) — modifie `$t` |
| `array_push` / `array_pop` | Ajouter / retirer à la fin |
| `unset($t[$k])` | Supprimer un élément par sa clé |

```php
<?php
if (in_array("pomme", $fruits)) {
    echo "Il y a des pommes.";
}
```

> ⚠️ **Piège** : `sort()` **ré-indexe** le tableau (perd les clés). Pour trier en gardant les
> clés d'un tableau associatif, cherche `asort`/`ksort` (voir « à toi de chercher »).

---

## 🔎 À toi de chercher

> 1. Différence entre `isset($t[$k])`, `array_key_exists($k, $t)` et `empty($t[$k])` — subtile
>    mais importante (une clé qui vaut `null`).
> 2. Cherche `array_merge()` et l'opérateur **spread** `...` pour combiner deux tableaux.
> 3. `asort`, `arsort`, `ksort` : trier un **associatif** par valeur ou par clé sans perdre les clés.

---

## 🎓 Ce qu'il faut retenir

- **Indexé** = liste (indices depuis 0) ; **associatif** = clé → valeur (comme un dictionnaire).
- Ajouter : `$t[] = ...` (fin) ou `$t["cle"] = ...` (clé). Accès sûr : `$t["cle"] ?? défaut`.
- **`foreach`** est la boucle du backend ; `foreach ($t as $cle => $val)` pour les paires.
- Une **liste de tableaux associatifs** modélise une collection d'objets.
- Fonctions clés : `count`, `in_array`, `array_key_exists`, `array_keys/values`.

👉 Leçon suivante : [Les fonctions de tableaux](./02-fonctions-tableaux.md)
