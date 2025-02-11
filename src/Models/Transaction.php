<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Transaction {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function createTransaction(int $user_id, int $category_id, float $amount, string $description, string $date): bool {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO transactions (user_id, category_id, amount, description, transaction_date) 
                                         VALUES (:user_id, :category_id, :amount, :description, :transaction_date)");
            return $stmt->execute([
                'user_id' => $user_id,
                'category_id' => $category_id,
                'amount' => $amount,
                'description' => $description,
                'transaction_date' => $date
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getTransactions(int $user_id): array {
        $stmt = $this->pdo->prepare("SELECT t.id, c.name AS category, t.amount, t.description, t.transaction_date 
                                     FROM transactions t 
                                     JOIN categories c ON t.category_id = c.id
                                     WHERE t.user_id = :user_id ORDER BY t.transaction_date DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function deleteTransaction(int $transaction_id, int $user_id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM transactions WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $transaction_id, 'user_id' => $user_id]);
    }
}
