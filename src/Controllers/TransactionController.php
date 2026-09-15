<?php

namespace App\Controllers;

use App\Models\Transactions;
use PDO;



class TransactionController
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function deleteTransaction(int $transactionId, int $userId): bool
    {
        $newTransaction = new Transactions($this->pdo);
        return $newTransaction->deleteTransaction($transactionId, $userId);
    }
}
