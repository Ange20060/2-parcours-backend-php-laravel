# 10 — La Règle du Boy Scout

> 🎯 **Le principe** : laisse le code **plus propre** que tu ne l'as trouvé. À chaque passage
> dans un fichier, améliore une petite chose.
>
> 💥 **Ce que ça tue** : la **dette technique** et la **pourriture du code** qui s'accumulent
> jusqu'à rendre le projet infernal à maintenir.

---

## L'idée, empruntée aux scouts

> « Laisse le terrain de camping plus propre que tu ne l'as trouvé. »

Appliqué au code : tu n'as pas besoin de **tout** refactoriser d'un coup. Mais **chaque fois**
que tu ouvres un fichier pour une modification, tu en profites pour l'améliorer **un peu** :

- Renommer une variable obscure (`$x` → `$montantTTC`).
- Extraire un bout de logique dans une fonction bien nommée.
- Supprimer du code mort ou un commentaire périmé.
- Corriger une indentation, ajouter un type manquant.

---

## Exemple concret

Tu dois corriger un petit bug dans cette fonction. Tu remarques d'autres soucis autour.

### Avant (ce que tu trouves)
```php
<?php
function calc($d) {
    $r = 0;
    foreach ($d as $i) {
        $r = $r + $i['p'] * $i['q'];  // le bug : on ne gère pas les quantités nulles
    }
    return $r;
}
```

### Après (tu corriges le bug **et** tu nettoies un peu)
```php
<?php
function calculerTotalPanier(array $lignes): float
{
    $total = 0.0;
    foreach ($lignes as $ligne) {
        if ($ligne['quantite'] <= 0) {
            continue; // le bug corrigé
        }
        $total += $ligne['prix'] * $ligne['quantite'];
    }
    return $total;
}
```

Tu n'as pas réécrit toute l'appli : juste rendu **ce** fichier meilleur. Multiplié par toute
l'équipe, sur des mois, ça fait la **différence entre un projet sain et un projet pourri**.

---

## ⚠️ Le bon dosage

- ✅ Améliore ce que tu **touches déjà** pour ta tâche.
- ❌ Ne pars pas refactoriser 40 fichiers non liés « tant que tu y es » : ça noie ta revue de
  code et mélange les changements. Reste **dans le périmètre** de ta modification.
- 💡 Sépare idéalement les commits : un commit « correction du bug », un commit « nettoyage ».

---

## 🔗 Liens avec les autres principes

- C'est l'**habitude quotidienne** qui maintient vivants **tous** les autres principes.
- Elle nourrit la **[cohésion et le faible couplage](./06-cohesion-couplage.md)** dans la durée.

---

## 🏋️ Mini-exercice

Voici un fichier « sale ». Applique la règle du Boy Scout : **3 améliorations** minimum
(nommage, types, code mort…), **sans** changer le comportement.

```php
<?php
function f($t) {
    $z = array();          // à quoi sert ce tableau ?
    $s = 0;
    for ($k = 0; $k < count($t); $k++) {
        $s = $s + $t[$k];
    }
    $moy = $s / count($t);
    return $moy;
}
```

> Corrigé dans [corriges.md](./corriges.md).
