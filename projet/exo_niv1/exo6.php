<?php

declare(strict_types=1);



$notes = [12, 8, 15, 17, 9, 14];

function sommeNotes(array $notes): int
{
  if (count($notes) === 0) {
    throw new InvalidArgumentException("Impossible : liste de notes vide.");
  }
  $sum = 0;
  foreach ($notes as $note) {
    $sum += $note;
  }
  return $sum;
}

function moyenneNotes(array $notes): float
{
  if (count($notes) === 0) {
    throw new InvalidArgumentException("Impossible : liste de notes vide.");
  }
  $moy = 0;
  $tot = count($notes);
  foreach ($notes as $note) {
    $sum += $note;
  }
  $moy = $sum / $tot;
  return $moy;
}
function meilleureNote(array $notes): int
{
  if (count($notes) === 0) {
    throw new InvalidArgumentException("Impossible : liste de notes vide.");
  }
  $meill = 0;
  foreach ($notes as $note) {
    $meill === $note;
    if ($note > $meill) {
      $meill = $note;
    }
  }
  return $meill;
}
