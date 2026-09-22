<?php

use App\Models\Subscription;
use App\Services\Auth;
use App\Services\Csrf;

$pdo = require_once __DIR__ . '/../../bootstrap.php';

Auth::requireLogin();

$userId = $_SESSION['user_id'];

$subscriptions = new Subscription($pdo);
$userSubscriptions = $subscriptions->getSubscriptionsForUser($userId);
$token = Csrf::generateToken();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpendOops subscriptions</title>

</head>

<body>

    <?php if (isset($_SESSION['error_message'])) : ?>
        <p><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])) : ?>
        <p><?= htmlspecialchars($_SESSION['success_message']) ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <h1>Subscriptions</h1>

    <?php if (empty($userSubscriptions)): ?>
        <p>No subscriptions found.</p>
    <?php else: ?>

        <ul>
            <?php foreach ($userSubscriptions as $subscription): ?>
                <li>
                    <form method="POST" action="update-subscription-status.php">
                        <input type="hidden" name="csrf_token" value="<?php echo $token ?>">
                        <input type="hidden" name="subscription_id" value="<?php echo $subscription['id'] ?>">

                        <?= htmlspecialchars($subscription['name']) ?>
                        -
                        <?= htmlspecialchars($subscription['amount']) ?>
                        -
                        <?= htmlspecialchars($subscription['billing_cycle']) ?>
                        -
                        <?= htmlspecialchars($subscription['next_payment_date']) ?>
                        -
                        <?php if ((int)$subscription['is_active'] === 1): ?>
                            Active
                            <button type="submit" name='action' value='0'>Padaryti neaktyvia prenumerata.</button>
                        <?php else: ?>
                            Inactive
                            <button type="submit" name="action" value="1">Aktyvuoti prenumerata.</button>
                        <?php endif; ?>
                    </form>
                </li>

            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>

</html>
