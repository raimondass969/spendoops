<?php

namespace App\Controllers;

use App\Models\Subscription;
use DateTime;
use InvalidArgumentException;
use PDO;

class SubscriptionController
{
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createSubscription(int $userId, string $name, string $amount, string $billingCycle, string $nextPaymentDate): int
    {
        $date = DateTime::createFromFormat('Y-m-d', $nextPaymentDate);

        if (trim($name) === '') {
            throw new InvalidArgumentException('Subscription name is required.');
        }

        if (!is_numeric($amount) || $amount <= 0) {
            throw new InvalidArgumentException('Amount must be a number. And greater than zero.');
        }

        if ($billingCycle !== 'MONTHLY' && $billingCycle !== 'YEARLY') {
            throw new InvalidArgumentException('Billing cycle must be MONTHLY or YEARLY.');
        }
        if ($date === false || $date->format('Y-m-d') !== $nextPaymentDate) {
            throw new InvalidArgumentException('Bad date format.');
        }

        $subscription = new Subscription($this->pdo);
        return $subscription->addNewSubscription($userId, $name, $amount, $billingCycle, $nextPaymentDate);
    }
}
