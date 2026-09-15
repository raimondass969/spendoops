<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Category;
use PDO;
use Throwable;

class RegisterController
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function register(string $email, string $username, string $password): bool
    {

        $user = new User($this->pdo);
        try {
            $this->pdo->beginTransaction();
            $newUserId = $user->userRegister($email, $username, $password);

            $defaultCategories = [
                'Food',
                'Transport',
                'Other'
            ];

            $newCategory = new Category($this->pdo);
            // Create default categories for new users
            foreach ($defaultCategories as $category) {
                $newCategory->addCategory($newUserId, $category);
            }
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return false;
        }
    }
}
