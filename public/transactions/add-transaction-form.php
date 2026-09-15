<?php

use App\Services\Csrf;
use App\Models\Category;

$pdo = require_once __DIR__ . '/../../bootstrap.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /auth/login-form.php');
    exit();
}

$token = Csrf::generateToken();

$category = new Category($pdo);
try {
    $userCategories = $category->getCategoriesForUser($_SESSION['user_id']);
} catch (PDOException $e) {
    $_SESSION['error_message'] = 'duomenų bazės klaida';
    $userCategories = [];
}
?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <title>Pridėti transakciją | SpendOops</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/css/output.css">
</head>

<body class="bg-slate-100 font-manrope p-4">

    <?php if (isset($_SESSION['error_message'])) : ?>
        <p><?= htmlspecialchars($_SESSION['error_message']) ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <main class="max-w-xl mx-auto">
        <div class="mb-6 ">
            <a href="list.php"> ← Atgal į transakcijas </a>
        </div>

        <form class="bg-white rounded-2xl shadow-lg p-6 gap-3"
            method="POST"
            action="add-transactions.php">
            <input type="hidden" name="csrf_token" value="<?php echo $token ?>">
            <div class="">
                <h1 class="flex justify-center px-2">Pridėti transakciją</h1>
            </div>
            <div class="flex flex-col gap-1 mb-4">
                <label for='transaction_type'>Transakcijos tipas: </label>
                <select
                    class="border rounded-lg px-3 py-2 w-full"
                    name='transaction_type'
                    id='transaction_type'>
                    <option value='INCOME'>Income</option>
                    <option value='EXPENSE'>Expense</option>
                </select>
            </div>

            <div class="flex flex-col gap-1 mb-4 w-full">
                <label for='amount'>Įveskite sumą €: </label>
                <input
                    class="border rounded-lg px-3 py-2 w-full"
                    id='amount'
                    name='amount'
                    step="0.01"
                    type='number'>
            </div>

            <div class="flex flex-col gap-1 mb-4">
                <label for='description'>Įveskite transakcijos aprašymą: </label>
                <input
                    class="border rounded-lg px-3 py-2 w-full"
                    id='description'
                    name='description'
                    type='text'>
            </div>

            <div class="flex flex-col gap-1 mb-4 ">

                <label for='category_id'>Pasirinkite kategoriją: </label>
                <select
                    class="border rounded-lg px-3 py-2 w-full"
                    name='category_id' id='category_id'>
                    <?php foreach ($userCategories as $category): ?>
                        <option value="<?= (int) $category['id'] ?>">
                            <?= htmlspecialchars($category['NAME']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button
                class="bg-violet-500 text-white rounded-lg shadow-xs py-2 px-3 w-full"
                type="submit">Pridėti transakciją</button>
        </form>
    </main>
</body>

</html>
