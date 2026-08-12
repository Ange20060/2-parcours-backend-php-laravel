<?php
declare(strict_types=1);

$note = 15;

if ($note>18)
  {
    echo "Mention exellente";
  }elseif($note<=16)
  {
    echo "Metion Bien";
  }elseif($note >12)
  {
    echo "Mention Assez bien";
  }else
  {
    echo "Mention passable";
  }

  

   function estPalindrome(string $mot): bool
{
    return $mot === strrev($mot);
}
