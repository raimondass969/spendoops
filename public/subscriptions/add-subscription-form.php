<?php

use App\Services\Csrf;

$pdo = require_once __DIR__ . '/../../bootstrap.php';
$token = Csrf::generateToken();

if (isset($_SESSION['success_message'])) {
    $successMessage = htmlspecialchars($_SESSION['success_message']);
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $errorMessage =  htmlspecialchars($_SESSION['error_message']);
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription</title>
</head>

<body>

    <form method="POST" action="add-subscription.php">
        <input type="hidden" value="<?php echo $token ?>" name="CSRF_TOKEN">
        <div>
            <label for="subscription_name">Iveskite subsciption pavadinima</label>
            <input id="subscription_name" name="subscription_name" type="text">
        </div>
        <div>
            <label for="subscription_price">Iveskite suma: </label>
            <input id="subscription_price" name="subscription_price" type="number" step="0.01">
        </div>
        <div>
            <label for="subscription_billing_cycle">Mokejimo sutartis</label>
            <select id="subscription_billing_cycle" name="subscription_billing_cycle">
                <option value="YEARLY">Metinis</option>
                <option value="MONTHLY">Menesinis</option>
            </select>
        </div>
        <div>
            <label for="subscription_next_payment_date">Kita mokejimo data</label>
            <input id="subscription_next_payment_date" name="subscription_next_payment_date" type="date">
        </div>
        <button type="submit">Prideti</button>
    </form>

    <?php if (isset($successMessage)): ?>
        <p><?= $successMessage ?></p>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <p><?= $errorMessage ?></p>
    <?php endif; ?>
</body>

</html>
