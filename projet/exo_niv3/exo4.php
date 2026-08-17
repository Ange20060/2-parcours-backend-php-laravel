<?php

declare(strict_types=1);

abstract class Employe
{
    public function __construct(protected string $nom) {}

    abstract public function salaire(): float;

    public function presentation(): string
    {
        return "{$this->nom} gagne " . $this->salaire() . " € / mois.";
    }
}
class Developpeur extends Employe
{
    public function salaire(): float { return 3500; }
}

class Manager extends Employe
{
    public function salaire(): float { return 4500; }
}

echo (new Developpeur("Marie"))->presentation() . PHP_EOL;
