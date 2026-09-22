<?php

use App\Models\Subscription;
use App\Services\Auth;
use App\Services\Csrf;

$pdo = require_once __DIR__ . '/../../bootstrap.php';
Auth::requireLogin();

const SUBLIST = 'Location: subscription-list.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(SUBLIST);
    exit();
}

if (!isset($_POST['subscription_id'], $_POST['csrf_token'], $_POST['action'])) {
    $_SESSION['error_message'] = "Missing require data.";
    header(SUBLIST);
    exit();
}

$subscriptionId = $_POST['subscription_id'];
$token = $_POST['csrf_token'];
$userId = $_SESSION['user_id'];
$isActive = $_POST['action'];

if (!in_array($isActive, ['0', '1'], true)) {
    exit('Invalid data.');
}

$isActive = (int)$isActive;

if (!Csrf::validateToken($token)) {
    exit("Invalid CSRF token.");
}


$subscription = new Subscription($pdo);
$result = $subscription->setSubscriptionStatus($subscriptionId, $userId, $isActive);
if ($result === 0) {
    $_SESSION['error_message'] = 'Ivyko klaida.';
    header(SUBLIST);
    exit();
}
if ($isActive === 1) {
    $_SESSION['success_message'] = 'Prenumeratas sekmingai aktyvuotas';
    header(SUBLIST);
    exit();
}
if ($isActive === 0) {
    $_SESSION['success_message'] = 'Prenumeratas sekmingai sustabdytas.';
    header(SUBLIST);
    exit();
}
