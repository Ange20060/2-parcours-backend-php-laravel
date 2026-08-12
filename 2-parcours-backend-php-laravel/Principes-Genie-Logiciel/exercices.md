# 🏋️ Principes — Exercices transversaux de refactoring

Ces exercices te font **appliquer plusieurs principes à la fois** sur du code réaliste. Pour
chacun : identifie **quels principes** sont violés, puis **refactorise**. Chaque exercice
indique son **🎯 But**. Corrigés dans [corriges.md](./corriges.md).

> 💡 Un refactoring **ne change pas le comportement** : il change la **forme** du code pour le
> rendre plus propre. Le résultat doit produire exactement les mêmes sorties.

---

## Exercice 1 — Le code spaghetti 🍝
> 🎯 **But** : repérer et corriger des violations de **SoC**, **DRY** et **Fail Fast**.

```php
<?php
function traiterCommande($data) {
    $tva = 0.20;
    $total = $data['prix'] * $data['qte'] * (1 + $tva);
    $pdo = new PDO('mysql:host=localhost;dbname=shop', 'root', '');
    $pdo->query("INSERT INTO commandes (total) VALUES ($total)");
    mail($data['email'], "Commande", "Total : " . $data['prix'] * $data['qte'] * (1 + 0.20));
    echo "<h1>Merci ! Total : " . $total . "</h1>";
}
```
**Consignes :**
1. Liste les **responsabilités** mélangées (SoC).
2. Repère la **duplication** du calcul TTC (DRY).
3. Ajoute une **validation** en entrée (Fail Fast) : `prix > 0`, `qte > 0`, email valide.
4. Propose une découpe en **fonctions/classes** (juste les signatures suffisent).

---

## Exercice 2 — Les booléens magiques 🎭
> 🎯 **But** : appliquer **Explicite > Implicite**.

```php
<?php
$user = creerUtilisateur("marie@x.fr", true, false, true);
```
Rends cet appel **lisible** (arguments nommés, enums ou constantes) et propose une signature
de fonction claire pour `creerUtilisateur`.

---

## Exercice 3 — Le `switch` qui grossit 🔀
> 🎯 **But** : appliquer **Open/Closed** et **Composition > Héritage**.

```php
<?php
function calculerFrais(string $type, float $poids): float {
    if ($type === 'standard')  return $poids * 2;
    if ($type === 'express')   return $poids * 5;
    if ($type === 'point_relais') return $poids * 1.5;
    // demain : 'international', 'drone'... on modifie encore cette fonction
    return 0;
}
```
Refactorise pour qu'**ajouter** un mode de livraison ne nécessite **pas** de modifier le code
existant (indice : une interface `ModeLivraison`).

---

## Exercice 4 — La classe qui fait tout 🐙
> 🎯 **But** : appliquer **Responsabilité unique (S de SOLID)** et **faible couplage**.

```php
<?php
class Utilisateur {
    public function sauvegarder() { /* SQL en dur */ }
    public function envoyerEmail() { /* mail() */ }
    public function genererFacturePDF() { /* PDF */ }
    public function calculerStatistiques() { /* stats */ }
}
```
Identifie les responsabilités et propose un **découpage** en classes cohérentes.

---

## Exercice 5 — La sur-ingénierie 🏗️
> 🎯 **But** : appliquer **KISS** et **YAGNI** (le refactoring inverse : **simplifier**).

Un collègue a écrit une hiérarchie de 6 classes abstraites + 3 interfaces + une factory, pour
un besoin réel qui est : **calculer le prix TTC d'un produit en euros**. Décris comment tu le
**ramènerais à l'essentiel**, et quels principes justifient ta décision.

---

## Exercice 6 — Refactoring guidé complet 🌟
> 🎯 **But** : combiner **DRY + SSOT + Fail Fast + Explicite + SoC** sur un cas réaliste.

```php
<?php
function inscrire($nom, $email, $age) {
    if ($age > 0) {
        $r1 = $nom . " a été inscrit avec l'email " . $email;
        file_put_contents('users.txt', $r1 . "\n", FILE_APPEND);
        echo $nom . " a été inscrit avec l'email " . $email;
        return true;
    }
    return false;
}
```
Refactorise en respectant : validation stricte (Fail Fast), pas de duplication du message
(DRY), séparation « écrire dans le fichier » / « afficher » (SoC), typage explicite.

---

👉 Corrigés : [corriges.md](./corriges.md)
