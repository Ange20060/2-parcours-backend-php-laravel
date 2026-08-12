# 🟢 Niveau 1 — Les bases de PHP

On installe l'environnement d'un développeur PHP professionnel, et on apprend les
**fondations du langage** : syntaxe, types, structures de contrôle et fonctions. On code
**proprement dès le premier jour** (typage, nommage clair) — c'est une habitude d'ingénieur.

> 🎯 **À la fin de ce niveau, tu sauras :**
> - Installer et vérifier **PHP**, **Composer**, **VSCode** et **Git**
> - Écrire et exécuter un script PHP en ligne de commande
> - Manipuler variables, types et opérateurs, avec le **typage strict**
> - Utiliser les conditions et les boucles
> - Écrire des **fonctions** typées, courtes et bien nommées

---

## 📖 Les leçons (dans l'ordre)

1. [L'environnement du développeur PHP](./01-environnement.md)
2. [Syntaxe, variables et types](./02-syntaxe-variables-types.md)
3. [Structures de contrôle](./03-structures-controle.md)
4. [Les fonctions](./04-fonctions.md)

Puis :
- 📝 [Les exercices](./exercices.md)
- ✅ [Les corrigés](./corriges.md)

---

## 📐 Principes à garder en tête dès ce niveau

Même sur des bases, on applique déjà :
- **[KISS](../Principes-Genie-Logiciel/02-KISS.md)** — écris simple.
- **[Explicite > Implicite](../Principes-Genie-Logiciel/08-explicite-vs-implicite.md)** — types
  et noms parlants partout.
- **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)** — `declare(strict_types=1);` en
  tête de **chaque** fichier.

---

## ✅ Checklist de fin de niveau

- [ ] `php --version` et `composer --version` répondent
- [ ] J'ai exécuté un script `.php` depuis le terminal
- [ ] J'utilise `declare(strict_types=1);` systématiquement
- [ ] Je type mes variables/fonctions et je nomme clairement
- [ ] Je maîtrise `if/elseif/else`, `match`, `for`, `foreach`, `while`
- [ ] J'écris des fonctions typées avec un `return` explicite
- [ ] Mon code est versionné sur **GitHub**

Tout coché 👉 [Niveau 2 : PHP intermédiaire](../Niveau-2-PHP-Intermediaire/)
