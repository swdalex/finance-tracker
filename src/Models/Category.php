<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Category {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function createCategory(int $user_id, string $name, string $type): bool {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO categories (user_id, name, type) VALUES (:user_id, :name, :type)");
            return $stmt->execute([
                'user_id' => $user_id,
                'name' => $name,
                'type' => $type
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCategories(int $user_id): array {
        $stmt = $this->pdo->prepare("SELECT id, name, type FROM categories WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
}
