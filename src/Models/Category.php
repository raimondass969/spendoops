<?php

namespace App\Models;

use PDO;

class Category
{

    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addCategory(int $userId, string $name): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO categories (user_id,NAME) values (:user_id, :NAME) ");
        $stmt->execute([
            'user_id' => $userId,
            'NAME' => $name,
        ]);
    }

    public function getCategoriesForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE user_id = :user_id");
        $stmt->execute([
            'user_id' => $userId,
        ]);
        return  $stmt->fetchAll();
    }
}
