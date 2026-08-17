<?php
function virement(PDO $pdo, int $de, int $vers, float $montant): void
{
    $pdo->beginTransaction();
    try {
        $debit = $pdo->prepare("UPDATE comptes SET solde = solde - :m WHERE id = :id");
        $debit->execute([':m' => $montant, ':id' => $de]);

        $credit = $pdo->prepare("UPDATE comptes SET solde = solde + :m WHERE id = :id");
        $credit->execute([':m' => $montant, ':id' => $vers]);

        $pdo->commit();          // tout a réussi → on valide
    } catch (Throwable $e) {
        $pdo->rollBack();        // une opération a échoué → on annule TOUT
        throw $e;                // on remonte l'erreur (Fail Fast)
    }
}
