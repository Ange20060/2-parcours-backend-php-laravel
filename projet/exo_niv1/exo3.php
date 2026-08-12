<?php

declare(strict_types=1);

$a = 0 == "a";      // false
$b ="1" == 1;        // true
$c = "1" === 1;       // false
$d = null == false;   // true
$e = [] == false;  // true

//Utiliser "===" permet de faire une comparaison de la valeur et du type

function test($mod)
{
  var_dump($mod);
  return $mod;
}

echo test($a);
echo test($b);
echo test($c);
echo test($d);
echo test($e);
