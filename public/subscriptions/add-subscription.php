<?php

use App\Controllers\SubscriptionController;
use App\Services\Auth;
use App\Services\Csrf;

const ADD_SUB_FORM = 'Location: add-subscription-form.php';

$pdo = require_once __DIR__ . '/../../bootstrap.php';

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Header(ADD_SUB_FORM);
    exit();
}

if (!isset(
    $_POST['subscription_name'],
    $_POST['subscription_price'],
    $_POST['subscription_billing_cycle'],
    $_POST['subscription_next_payment_date'],
    $_POST['csrf_token'],
)) {
    $_SESSION['error_message'] = 'Missing required data.';
    header(ADD_SUB_FORM);
    exit();
}

$subscriptionName = $_POST['subscription_name'];
$subscriptionPrice = $_POST['subscription_price'];
$subscriptionBillingCycle = $_POST['subscription_billing_cycle'];
$subscriptionNextPaymentDate = $_POST['subscription_next_payment_date'];
$token = $_POST['csrf_token'];
$userId = $_SESSION['user_id'];

if (!Csrf::validateToken($token)) {
    $_SESSION['error_message'] = 'Invalid CSRF token';
    header(ADD_SUB_FORM);
    exit();
}

$newSubscription = new SubscriptionController($pdo);
try {
    $createdSubscription = $newSubscription->createSubscription($userId, $subscriptionName, $subscriptionPrice, $subscriptionBillingCycle, $subscriptionNextPaymentDate);
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'duomenų bazės klaida';
    header(ADD_SUB_FORM);
    exit();
} catch (InvalidArgumentException $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header(ADD_SUB_FORM);
    exit();
}

$_SESSION['success_message'] = "Prenumeratas sukurtas sekmingai.";
header("location: subscription-list.php");
exit();
