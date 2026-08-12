<?php
declare(strict_types=1);


function calculateResult(int|float $a, int|float $b, string $operation): int|float
{
    return match ($operation) {
        '+' => $a + $b,
        '-' => $a - $b,
        '*' => $a * $b,
        default => throw new InvalidArgumentException("Opération non supportée : {$operation}"),
    };
}

function displayResult(int|float $a, int|float $b, string $operation): void
{
    $result = calculateResult($a, $b, $operation);
    echo "Résultat : {$result}" . PHP_EOL;
}

displayResult(10, 4, '+');
