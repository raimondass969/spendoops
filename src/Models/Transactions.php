<?php

namespace App\Models;

use PDO;
use InvalidArgumentException;

class Transactions
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addTransaction(string $transaction_type, string $amount, string $description, int $categoryId, int $userId): bool
    {

        if (!is_numeric($amount)) {
            throw new InvalidArgumentException("Negalima reiksme");
        }

        if (!in_array($transaction_type, ['INCOME', 'EXPENSE'])) {
            throw new InvalidArgumentException('Galimos reiksmes INCOME arba EXPENSE ');
        }


        $stmt = $this->pdo->prepare('SELECT id FROM Categories WHERE id= :category_id AND user_id= :user_id');

        $stmt->execute([
            'category_id' => $categoryId,
            'user_id' => $userId
        ]);

        $category = $stmt->fetch();
        if (!$category) {
            return false;
        }

        $stmt = $this->pdo->prepare('INSERT INTO transactions(transaction_type,amount,description, category_id) VALUES (:transaction_type, :amount, :description, :category_id)');

        $stmt->execute([
            'transaction_type' => $transaction_type,
            'amount' => $amount,
            'description' => $description,
            'category_id' => $categoryId,
        ]);
        return true;
    }
    public function getAllTransactions(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT
                                        transactions.id AS transaction_id,
                                        transactions.amount,
                                        transactions.transaction_type,
                                        transactions.DESCRIPTION,
                                        categories.NAME
                                     FROM transactions
                                     JOIN categories ON transactions.category_id = categories.id
                                     WHERE categories.user_id = :user_id");
        $stmt->execute([
            'user_id' => $userId
        ]);
        return $stmt->fetchAll();
    }

    public function deleteTransaction(int $id, int $userId): bool
    {

        $stmt = $this->pdo->prepare(
            "DELETE transactions
                FROM transactions
                JOIN categories
                    ON transactions.category_id = categories.id
                WHERE transactions.id= :id
                    AND categories.user_id = :user_id"

        );
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
        return $stmt->rowCount() > 0;
    }
}
