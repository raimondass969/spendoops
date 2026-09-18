<?php

namespace App\Models;

use PDO;

class Subscription
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function addNewSubscription(int $userId, string $name, string $amount, string $billingCycle, string $nextPaymentDate): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO subscriptions (user_id, name, amount, billing_cycle, next_payment_date) VALUES (:user_id,:name, :amount, :billing_cycle, :next_payment_date)");
        $stmt->execute([
            'user_id' => $userId,
            'name' => $name,
            'amount' => $amount,
            'billing_cycle' => $billingCycle,
            'next_payment_date' => $nextPaymentDate,
        ]);
        return (int) $this->pdo->lastInsertId();
    }
}
