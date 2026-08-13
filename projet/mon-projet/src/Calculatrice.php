<?php

namespace App;

class Calculatrice
{
    public function additionner(float $a, float $b): float
    {
        return $a + $b;
    }
    public function mutiplier(float $a, float $b): float
    {
      return $a*$b;
    }
}
