# Leçon 1.1 — L'environnement du développeur PHP

> 🎯 **Objectif** : installer et vérifier les 4 outils du développeur PHP moderne (**PHP**,
> **Composer**, **VSCode**, **Git**), et exécuter ton premier script.

---

## 🧰 Les 4 outils

| Outil | Rôle |
|---|---|
| **PHP** | L'interpréteur du langage (exécute ton code) |
| **Composer** | Le gestionnaire de dépendances (installe des bibliothèques, et Laravel) |
| **VSCode** | L'éditeur de code |
| **Git** | Le versionnement (obligatoire dès maintenant) |

---

## ⬇️ Étape 1 — Installer PHP (8.2 ou plus)

- **Linux (Fedora/RHEL)** : `sudo dnf install php php-cli`
- **Linux (Ubuntu/Debian)** : `sudo apt install php-cli`
- **macOS** : via [Homebrew](https://brew.sh) → `brew install php`
- **Windows** : le plus simple est un pack comme [Laragon](https://laragon.org/) (fournit PHP,
  Composer, MySQL prêts à l'emploi), ou télécharger PHP depuis [windows.php.net](https://windows.php.net/download).

Vérifie :
```bash
php --version
```
Tu dois voir `PHP 8.2.x` (ou plus). Si la version est < 8.1, mets à jour : on utilise des
fonctionnalités modernes (enums, types, arguments nommés).

---

## ⬇️ Étape 2 — Installer Composer

Composer est **indispensable** en PHP moderne : il gère les bibliothèques et l'**autoloading**
(chargement automatique des classes). Laravel s'installe via Composer.

- Suis le guide officiel : **https://getcomposer.org/download/**
- Objectif : pouvoir lancer `composer` de partout.

Vérifie :
```bash
composer --version
```

> 🔎 **À toi de chercher** : que contient le fichier `composer.json` d'un projet ? À quoi sert
> le dossier `vendor/` ? (On s'en servira dès le Niveau 2.)

---

## ⬇️ Étape 3 — VSCode + extensions

Installe [VSCode](https://code.visualstudio.com), puis les extensions :
- **PHP Intelephense** (autocomplétion, analyse) — la plus importante.
- **PHP Debug** (débogage, plus tard).

> 💡 Configure Intelephense pour signaler les erreurs de type : c'est un allié pour respecter
> **[Explicite > Implicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)**.

---

## ⬇️ Étape 4 — Git

Si ce n'est pas déjà fait :
```bash
git --version
git config --global user.name "Ton Nom"
git config --global user.email "toi@exemple.com"
```
Crée un compte **GitHub** : tous tes projets de ce parcours y seront versionnés.

---

## ▶️ Étape 5 — Ton premier script PHP

Crée un dossier de projet, puis un fichier `bonjour.php` :

```php
<?php

declare(strict_types=1);

echo "Bonjour, développeur backend !" . PHP_EOL;
```

Exécute-le **en ligne de commande** (c'est ainsi qu'on teste du code backend) :
```bash
php bonjour.php
```
Sortie attendue :
```
Bonjour, développeur backend !
```

**Décortiquons :**
- `<?php` : ouvre le code PHP (dans un fichier `.php` pur, on **n'écrit jamais** la balise
  fermante `?>` — c'est une convention pour éviter des bugs d'espaces).
- `declare(strict_types=1);` : **active le typage strict**. À mettre en **tête de chaque
  fichier** de tout le parcours. C'est du [Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)
  gratuit : PHP refusera une string là où un `int` est attendu.
- `echo` : affiche.
- `PHP_EOL` : le saut de ligne portable (End Of Line).
- `;` : chaque instruction se termine par un point-virgule.

---

## 🌐 Un mot : PHP et le web

PHP peut aussi être servi par un serveur web pour générer des pages. Un serveur de test intégré
existe :
```bash
php -S localhost:8000
```
Puis ouvre `http://localhost:8000` dans le navigateur. On approfondira le **web** au Niveau 5.
Pour l'instant, on travaille surtout **en ligne de commande** pour se concentrer sur le langage.

---

## 🔎 À toi de chercher

> 1. Quelle est la différence entre `php monfichier.php` (CLI) et l'exécution via un serveur web ?
> 2. Cherche « php.ini » : à quoi sert ce fichier de configuration ?
> 3. Initialise un dépôt Git dans ton dossier de projet et fais un premier commit
>    (« Ajoute le script bonjour.php »).

---

## 🎓 Ce qu'il faut retenir

- Outils : **PHP 8.2+**, **Composer**, **VSCode (+Intelephense)**, **Git**.
- Un fichier PHP pur commence par `<?php` et **sans** balise fermante.
- **Toujours** `declare(strict_types=1);` en tête de fichier.
- On exécute un script backend avec `php fichier.php`.

👉 Leçon suivante : [Syntaxe, variables et types](./02-syntaxe-variables-types.md)
