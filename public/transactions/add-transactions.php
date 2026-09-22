<?php

use App\Models\Transactions;
use App\Services\Auth;
use App\Services\Csrf;


const ADD_TRANSACTION_FORM = 'Location: add-transaction-form.php';
$pdo = require_once __DIR__ . '/../../bootstrap.php';

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_type = $_POST['transaction_type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $categoryId = $_POST['category_id'];
    $userId = $_SESSION['user_id'];
    $token = $_POST['csrf_token'];

    if (!Csrf::validateToken($token)) {
        exit('Invalid CSRF token');
    }

    $transaction = new Transactions($pdo);
    try {
        $transactionInfo = $transaction->addTransaction($transaction_type, $amount, $description, $categoryId, $userId);
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'duomenų bazės klaida';
        header('Location: add-transaction-form.php');
        exit();
    } catch (InvalidArgumentException $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header(ADD_TRANSACTION_FORM);
        exit();
    }
    if (!$transactionInfo) {
        $_SESSION['error_message'] = 'Kategorija nerasta arba nepriklauso vartotojui.';
        header(ADD_TRANSACTION_FORM);
        exit();
    } else {
        $_SESSION['success_message'] = 'Transakcija sekmingai prideta!';
        header('Location: /transactions/list.php');
        exit();
    }
}
