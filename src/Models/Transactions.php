<?php

namespace App\Models;

use PDO;
use InvalidArgumentException;
use PDOException;

class Transactions
{

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addTransaction($transaction_type, $amount, $description, $categoryId, $userId)
    {

        if (!in_array($transaction_type, ['INCOME', 'EXPENSE'])) {
            throw new InvalidArgumentException('Galimos reiksmes INCOME arba EXPENSE ');
        }

        try {
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
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function getAllTransactions($userId)
    {

        try {
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
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteTransaction($id, $userId)
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
