<?php

namespace App\Services;

class Auth
{
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = 'Not logged in.';
            http_response_code(401);
            header("Location: /auth/login-form.php");
            exit();
        }
    }
}
