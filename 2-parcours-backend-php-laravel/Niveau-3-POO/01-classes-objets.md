# Leçon 3.1 — Classes et objets

> 🎯 **Objectif** : comprendre les **classes** (des « moules ») et les **objets** (les instances
> fabriquées avec le moule) — la façon de modéliser le monde en programmation orientée objet.

---

## 🧠 L'idée : un moule et ses instances

Une **classe** décrit **ce qu'est** une chose (ses données) et **ce qu'elle sait faire** (ses
actions). Un **objet** est un exemplaire concret créé à partir de cette classe.

> 🍪 Analogie : la classe est le **moule à cookies**, chaque objet est un **cookie** fabriqué
> avec. Le moule existe une fois ; on fabrique autant de cookies qu'on veut.

---

## 🧱 Définir une classe

```php
<?php
declare(strict_types=1);

class Voiture
{
    // Propriétés (les données de l'objet), typées
    public string $marque;
    public string $couleur;
    public int $vitesse = 0;   // valeur par défaut

    // Méthode (une action)
    public function accelerer(int $increment): void
    {
        $this->vitesse += $increment;
    }
}
```

- **Propriété** = une variable qui appartient à l'objet.
- **Méthode** = une fonction qui appartient à l'objet.
- **`$this`** = « l'objet courant », celui sur lequel la méthode est appelée.

---

## 🏭 Créer et utiliser un objet

```php
<?php
$maVoiture = new Voiture();        // on fabrique un objet (une instance)

$maVoiture->marque = "Peugeot";    // -> pour accéder aux propriétés/méthodes
$maVoiture->couleur = "bleue";

$maVoiture->accelerer(30);
echo $maVoiture->vitesse;          // 30

$autreVoiture = new Voiture();     // un AUTRE objet, indépendant
$autreVoiture->accelerer(50);
echo $autreVoiture->vitesse;       // 50 (chaque objet a SON état)
```

> 💡 On utilise la **flèche `->`** pour accéder à ce qui appartient à un objet. Chaque objet a
> **son propre état** : modifier `$maVoiture` ne touche pas `$autreVoiture`.

---

## 🛠️ Le constructeur : initialiser à la création

Le **constructeur** (`__construct`) s'exécute automatiquement à la création de l'objet. Il sert
à donner les valeurs de départ — et évite d'oublier des champs.

```php
<?php
class Voiture
{
    public function __construct(
        public string $marque,
        public string $couleur,
        public int $vitesse = 0,
    ) {}

    public function accelerer(int $increment): void
    {
        $this->vitesse += $increment;
    }
}

$maVoiture = new Voiture("Peugeot", "bleue");
echo $maVoiture->marque;   // Peugeot
```

> ✨ **Promotion de propriétés** (PHP 8) : déclarer `public string $marque` **directement dans
> le constructeur** crée **et** initialise la propriété en une seule ligne. Très concis — tu le
> verras partout, y compris dans Laravel.

---

## 🎯 Méthodes qui calculent et retournent

Comme les fonctions, une méthode peut **retourner** une valeur :

```php
<?php
class Rectangle
{
    public function __construct(
        private float $largeur,
        private float $hauteur,
    ) {}

    public function aire(): float
    {
        return $this->largeur * $this->hauteur;
    }
}

$r = new Rectangle(4, 5);
echo $r->aire();   // 20
```

---

## 🆚 Classe vs objet — le vocabulaire

| Terme                      | Sens                                             |
| -------------------------- | ------------------------------------------------ |
| **Classe**           | Le plan / le moule (défini une fois)            |
| **Objet / instance** | Un exemplaire concret (`new Classe()`)         |
| **Propriété**      | Une donnée de l'objet                           |
| **Méthode**         | Une action de l'objet                            |
| **`$this`**        | L'objet courant, à l'intérieur d'une méthode  |
| **Constructeur**     | Méthode`__construct` appelée à la création |

---

## 🔎 À toi de chercher

> 1. Que se passe-t-il si tu appelles `new Voiture()` sans passer les arguments requis par le
>    constructeur ? (Lien avec **[Fail Fast](../Principes-Genie-Logiciel/07-fail-fast.md)**.)
> 2. Cherche la différence entre une **propriété d'instance** et une **propriété statique**
>    (`static`) partagée par toutes les instances.
> 3. Que fait la méthode magique `__toString()` ? (Afficher un objet comme une chaîne.)

---

## 🎓 Ce qu'il faut retenir

- Une **classe** est un moule ; un **objet** (`new Classe()`) en est une instance.
- **Propriétés** = données, **méthodes** = actions, **`$this`** = l'objet courant.
- Le **constructeur** (`__construct`) initialise l'objet ; la **promotion de propriétés**
  (PHP 8) déclare et initialise en une ligne.
- Chaque objet a **son propre état**, indépendant des autres.

👉 Leçon suivante : [Encapsulation et visibilité](./02-encapsulation.md)
