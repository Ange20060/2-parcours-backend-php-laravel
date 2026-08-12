<?php

declare(strict_types=1);

match(true)
  {
    $note>=16 =>"Excellent",
    $note>=12 =>"Bien",
    $note<=10 =>"Passable",
    $note>10 =>"Isuffisant"
  };
