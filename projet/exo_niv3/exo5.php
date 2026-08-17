<?php

declare(strict_types=1);



trait Horodatable
{
    public function creerHorodatage(): string
    {
        return (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    }
}

class Article { use Horodatable; }
class Commentaire { use Horodatable; }

echo (new Article())->creerHorodatage() . PHP_EOL;
