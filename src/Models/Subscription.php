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

    public function getSubscriptionsForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT
                                    subscriptions.id,
                                    subscriptions.amount,
                                    subscriptions.name,
                                    subscriptions.billing_cycle,
                                    subscriptions.is_active,
                                    subscriptions.next_payment_date
                                    FROM subscriptions
                                    WHERE subscriptions.user_id = :user_id");
        $stmt->execute([
            'user_id' => $userId,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function setSubscriptionStatus(int $subscriptionId, int $userId, int $isActive): int
    {
        $stmt = $this->pdo->prepare("UPDATE subscriptions
                                    SET is_active = :is_active
                                    WHERE subscriptions.id = :subscription_id
                                    AND subscriptions.user_id = :user_id");

        $stmt->execute([
            'is_active' => $isActive,
            'subscription_id' => $subscriptionId,
            'user_id' => $userId
        ]);
        return $stmt->rowCount();
    }
}
