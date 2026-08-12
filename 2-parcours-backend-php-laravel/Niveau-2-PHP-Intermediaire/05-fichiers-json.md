# Leçon 2.5 — Fichiers et JSON

> 🎯 **Objectif** : lire et écrire des fichiers, et manipuler le **JSON** — le format d'échange
> universel des API. C'est ta première forme de **persistance** (avant les bases de données).

---

## 📄 Lire et écrire un fichier texte

```php
<?php
declare(strict_types=1);

// Écrire (écrase le contenu)
file_put_contents("notes.txt", "Première ligne\n");

// Ajouter à la fin (sans écraser)
file_put_contents("notes.txt", "Deuxième ligne\n", FILE_APPEND);

// Lire tout le contenu
$contenu = file_get_contents("notes.txt");
echo $contenu;

// Lire ligne par ligne (tableau)
$lignes = file("notes.txt", FILE_IGNORE_NEW_LINES);
foreach ($lignes as $ligne) {
    echo $ligne . PHP_EOL;
}
```
> ⚠️ **Fail Fast** : `file_get_contents` sur un fichier absent déclenche un **warning** et
> renvoie `false`. Vérifie l'existence (`file_exists(...)`) ou gère l'erreur.

---

## 🧾 Le JSON, c'est quoi ?

**JSON** (*JavaScript Object Notation*) est un format texte pour représenter des données
structurées. C'est le langage d'échange des **API** : ton API Laravel renverra du JSON.

```json
{
  "nom": "Marie",
  "age": 25,
  "roles": ["admin", "user"]
}
```
Il ressemble beaucoup à un **tableau associatif** PHP — et c'est exactement comme ça qu'on le manipule.

---

## 🔄 PHP ↔ JSON : `json_encode` / `json_decode`

```php
<?php
// PHP  →  JSON (encoder)
$user = ["nom" => "Marie", "age" => 25, "roles" => ["admin", "user"]];
$json = json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo $json;

// JSON  →  PHP (décoder)
$texte = '{"nom":"Marie","age":25}';
$tableau = json_decode($texte, true);   // true = tableau associatif (sinon: objet)
echo $tableau["nom"];                    // Marie
```
- `JSON_PRETTY_PRINT` : indente joliment (lisible).
- `JSON_UNESCAPED_UNICODE` : garde les accents lisibles (`é` au lieu de `\u00e9`).
- `json_decode($texte, true)` : le `true` te donne un **tableau associatif** (le plus pratique).

---

## 🛡️ Gérer les erreurs JSON (Fail Fast)

Un JSON mal formé fait échouer le décodage. **Vérifie** :

```php
<?php
$json = json_encode($donnees);
if ($json === false) {
    throw new RuntimeException("Échec de l'encodage JSON.");
}

$donnees = json_decode($texte, true);
if (!is_array($donnees)) {
    throw new RuntimeException("JSON invalide ou corrompu.");
}
```
> 💡 Encore mieux (PHP 7.3+) : `json_decode($texte, true, flags: JSON_THROW_ON_ERROR)` lève
> directement une **`JsonException`** en cas de problème — du Fail Fast intégré.

---

## 🧩 Exemple complet : une mini-persistance JSON

Le motif que tu réutiliseras (et que tes stagiaires appliquent dans leurs projets) :

```php
<?php
declare(strict_types=1);

function charger(string $fichier): array
{
    if (!file_exists($fichier)) {
        return [];                       // 1er lancement : pas encore de données
    }
    $donnees = json_decode(file_get_contents($fichier), true);
    return is_array($donnees) ? $donnees : [];
}

function sauvegarder(array $donnees, string $fichier): void
{
    file_put_contents(
        $fichier,
        json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

$taches = charger("taches.json");
$taches[] = ["titre" => "Apprendre PHP", "fait" => false];
sauvegarder($taches, "taches.json");
```
> 🎯 **Bonnes pratiques** : un **chemin relatif** (jamais `C:/Users/...` codé en dur — ça casse
> sur toute autre machine !), et la **même** constante de nom de fichier pour lire **et** écrire.

---

## 🔎 À toi de chercher

> 1. Différence entre `json_decode($t, true)` (tableau) et `json_decode($t)` (objet `stdClass`).
> 2. Cherche `fopen` / `fgets` / `fclose` : la lecture **flux** (utile pour de gros fichiers
>    qu'on ne peut pas charger entièrement en mémoire).
> 3. Que fait `__DIR__` ? Comment l'utiliser pour construire un chemin **relatif au script**,
>    robuste quel que soit le dossier courant ?

---

## 🎓 Ce qu'il faut retenir

- Lire/écrire : `file_get_contents`, `file_put_contents` (+ `FILE_APPEND`), `file()`.
- **JSON** = format d'échange des API ; `json_encode` (PHP→JSON), `json_decode($t, true)` (JSON→tableau).
- **Vérifie** toujours les erreurs (fichier absent, JSON invalide) → Fail Fast.
- Persistance simple = **charger au démarrage / sauvegarder après chaque changement**, avec un
  **chemin relatif** (jamais de chemin absolu codé en dur).

👉 Leçon suivante : [Les dates](./06-dates.md)
