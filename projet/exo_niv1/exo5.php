<?php

declare(strict_types=1);

function fizzbuzz(int $n): string
{
    return match (true) {
        $n % 15 === 0 => "FizzBuzz",   
        $n % 3 === 0  => "Fizz",
        $n % 5 === 0  => "Buzz",
        default       => (string) $n,
    };
}

for ($i = 1; $i <= 30; $i++) {
    echo fizzbuzz($i) . PHP_EOL;
}
