<?php

declare(strict_types=1);

function calculerAge(string $dateNaissance): int
{
	try {
		$birth = DateTimeImmutable::createFromFormat('d/m/Y', $dateNaissance);
		$now = new DateTimeImmutable();
		$diff = $now->diff($birth);
		return (int)$diff->y;
	} catch (Exception $e) {
		return 0;
	}
}

function joursAvant(string $dateFuture): int
{
  $date = new DateTimeImmutable();
  $prediction = new DateTimeImmutable($dateFuture);

  $resultat = $date->diff($prediction);
  return (int)$resultat->days;

}

$dateNaissance = "20/06/2006";

$age=calculerAge($dateNaissance);

echo $age;

$dateFuture= "02/06/2027";

$future = joursAvant($dateFuture);
echo $future;
