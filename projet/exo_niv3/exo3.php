<?php

declare(strict_types=1);

interface Forme
{
    public function aire(): float;
}

class Cercle implements Forme
{
    public function __construct(private float $rayon) {}
    public function aire(): float { return pi() * $this->rayon ** 2; }
}

class Rectangle implements Forme
{
    public function __construct(private float $largeur, private float $hauteur) {}
    public function aire(): float { return $this->largeur * $this->hauteur; }
}

class Triangle implements Forme   // ajouté SANS toucher afficherAire (Open/Closed)
{
    public function __construct(private float $base, private float $hauteur) {}
    public function aire(): float { return $this->base * $this->hauteur / 2; }
}

function afficherAire(Forme $forme): void
{
    echo "Aire : " . round($forme->aire(), 2) . PHP_EOL;
}

afficherAire(new Cercle(3));
afficherAire(new Rectangle(4, 5));
afficherAire(new Triangle(6, 2));
