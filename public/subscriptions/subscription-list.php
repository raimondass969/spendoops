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
    <link rel="stylesheet" href="/css/output.css">

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

    <main class="flex flex-1 w-full justify-between items-center px-4 py-6">
        <div>Subscriptions</div>
        <a class="" href="add-subscription-form.php">+ Add new subscription</a>
    </main>

    <?php if (empty($userSubscriptions)): ?>
        <h1>You don't have any subscriptions yet.</h1>
        <p>Track your recurring payments and keep an eye on upcoming charges.</p>
        <a class="" href="add-subscription-form.php">+ Add new subscription</a>
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
                            <button class="bg-violet-600 text-black px-3 py-2 rounded-md hover:bg-violet-700"
                                type="submit" name='action' value='0'>Padaryti neaktyvia prenumerata.</button>
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
