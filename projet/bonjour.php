<?php

declare(strict_types=1);

$note = 15;

if ($note > 18) {
    echo "Mention excellente";
} elseif ($note > 16) {
    echo "Mention bien";
} elseif ($note > 12) {
    echo "Mention assez bien";
} else {
    echo "Mention passable";
}

function estPalindrome(string $mot): bool
{
    return $mot === strrev($mot);
}
