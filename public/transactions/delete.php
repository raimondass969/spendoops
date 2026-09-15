<?php

use App\Controllers\TransactionController;
use App\Services\Csrf;

$pdo = require_once __DIR__ . '/../../bootstrap.php';

header('Content-type: application/json');
if (!isset($_SESSION['logged_in']) ||  $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Neprisijunges']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'message' => 'Ivyko klaida']);
    exit();
}

$token = $_SERVER['HTTP_CSRF_TOKEN'] ?? null;
if (!$token) {
    echo json_encode(['success' => false, 'message' => 'CSRF token not find']);
    exit();
}
if (!Csrf::validateToken($token)) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit();
}

$transactionId = $_GET['id'] ?? null;

$validatedTransactionId = filter_var(
    $transactionId,
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'min_range' => 1
        ]
    ]
);

if ($validatedTransactionId === false) {
    echo json_encode(['success' => false, 'message' => 'Invalid transaction id']);
    exit();
}

$userId = $_SESSION['user_id'];

$transaction = new TransactionController($pdo);
$deleteTransaction = $transaction->deleteTransaction($validatedTransactionId, $userId);

if ($deleteTransaction) {
    echo json_encode(['success' => true, 'message' => 'Transakcija istrinta sekmingai!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ivyko klaida trinant transakcija!']);
}
