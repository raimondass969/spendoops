<?php

namespace App\Services;

class Csrf
{
    public static function generateToken()
    {

        if (isset($_SESSION['csrf_token'])) {
            return $_SESSION['csrf_token'];
        }
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function validateToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }

        if ($_SESSION['csrf_token'] !== $token) {
            return false;
        }
        return true;
    }
}
