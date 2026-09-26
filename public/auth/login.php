<?php

use App\Services\Csrf;
use App\Models\User;


$pdo = require_once __DIR__ . '/../../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /auth/login-form.php');
    exit();
}

$token = $_POST['csrf_token'];

if (!Csrf::validateToken($token)) {
    exit('Invalid token');
}

$email = $_POST['email'];
$password = $_POST['password'];


$user = new User($pdo);
try {
    $confirmedUser = $user->userLogin($email, $password);
} catch (PDOException) {
    http_response_code(500);
    exit("Prisijungimas nepavyko");
}

if (!$confirmedUser) {
    exit("Neteisingas el. pastas arba slaptazodis");
}

session_regenerate_id(true);

$_SESSION['logged_in'] = true;
$_SESSION['email'] = $email;
$_SESSION['user_id'] = $confirmedUser['id'];

header('Location: /transactions/list.php');
exit();
