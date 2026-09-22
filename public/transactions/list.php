<?php

use App\Models\Transactions;
use App\Services\Auth;
use App\Services\Csrf;

$pdo = require_once __DIR__ . '/../../bootstrap.php';
Auth::requireLogin();
$token = Csrf::generateToken();
$userId = $_SESSION['user_id'];

$transactionsList = new Transactions($pdo);
try {
    $transactions = $transactionsList->getAllTransactions($userId);
} catch (PDOException) {
    http_response_code(500);
    echo 'Nepavyko gauti transakcijų.';
    exit();
}

?>


<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Transaction list</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/css/output.css">
</head>

<body class="bg-slate-100 font-manrope p-2">


    <header class="flex justify-between items-center max-w-5xl mx-auto w-full px-2 py-4">
        <div class="flex gap-2 items-center">
            <img src="../assets/images/spendoops-mark.svg"
                alt=""
                class="h-9 w-9">
            <span class="text-2xl font-semibold tracking-tight">Spend<span class="text-violet-500">Oops</span></span>
        </div>
        <div class="flex gap-4 items-center">
            <a class="bg-violet-500 text-white rounded-xl shadow-2xs px-4 py-2"
                href="add-transaction-form.php">Pridėti transakcija</a>
            <a href="../auth/logout.php"> Atsijungti</a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto w-full bg-white rounded-xl shadow-lg px-2 py-4 mt-2 flex flex-col gap-2">
        <h1>Transakcijos</h1>
        <input type="hidden" name="csrf_token" value="<?php echo $token ?>">
        <?php if (empty($transactions)): ?>
            <p>Transakcijų kol kas nėra</p>
        <?php else: ?>

            <?php foreach ($transactions as $transaction): ?>

                <div class="flex justify-between items-center p-4 border-b"
                    id='transaction-<?= (int) $transaction['transaction_id'] ?>'>

                    <div>
                        <p class="font-semibold">
                            <?= htmlspecialchars($transaction['DESCRIPTION']) ?>
                        </p>
                        <span class="text-sm text-gray-600">
                            <?= htmlspecialchars($transaction['transaction_type']) ?>
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="font-semibold">
                            <?= htmlspecialchars($transaction['amount']) ?> €
                        </span>

                        <button class="cursor-pointer"
                            onclick="deleteTransaction(<?= (int) $transaction['transaction_id'] ?>)"> Ištrinti</button>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </main>
    <script src="transactions.js"></script>
</body>

</html>
