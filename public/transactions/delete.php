<?php

use App\Controllers\TransactionController;
use App\Services\Auth;
use App\Services\Csrf;

$pdo = require_once __DIR__ . '/../../bootstrap.php';

header('Content-type: application/json');

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Ivyko klaida']);
    exit();
}

$token = $_SERVER['HTTP_CSRF_TOKEN'] ?? null;
if (!$token) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF token not find']);
    exit();
}
if (!Csrf::validateToken($token)) {
    http_response_code(403);
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
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid transaction id']);
    exit();
}

$userId = $_SESSION['user_id'];

$transaction = new TransactionController($pdo);

try {
    $deleteTransaction = $transaction->deleteTransaction($validatedTransactionId, $userId);
    if ($deleteTransaction) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Transakcija istrinta sekmingai']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Transakcija nerasta']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ivyko serverio klaida trinant transakcija']);
}
