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

    /**
     * Get total income and expenses for a given month.
     */
    public function getMonthlySummary(int $user_id, string $month): array {
        $stmt = $this->pdo->prepare("
            SELECT
                SUM(CASE WHEN c.type = 'income' THEN t.amount ELSE 0 END) AS total_income,
                SUM(CASE WHEN c.type = 'expense' THEN t.amount ELSE 0 END) AS total_expense
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = :user_id
              AND DATE_TRUNC('month', t.transaction_date) = DATE_TRUNC('month', :month::date)
        ");

        $stmt->execute(['user_id' => $user_id, 'month' => $month]);
        return $stmt->fetch() ?: ['total_income' => 0, 'total_expense' => 0];
    }

    /**
     * Get total expenses per category for a given month.
     */
    public function getCategoryBreakdown(int $user_id, string $month): array {
        $stmt = $this->pdo->prepare("
            SELECT c.name AS category, SUM(t.amount) AS total_spent
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = :user_id
              AND c.type = 'expense'
              AND DATE_TRUNC('month', t.transaction_date) = DATE_TRUNC('month', :month::date)
            GROUP BY c.name
            ORDER BY total_spent DESC
        ");

        $stmt->execute(['user_id' => $user_id, 'month' => $month]);
        return $stmt->fetchAll();
    }

    /**
     * Get balance trend over the past few months.
     */
    public function getBalanceTrend(int $user_id, int $months): array {
        $stmt = $this->pdo->prepare("
            SELECT
                DATE_TRUNC('month', t.transaction_date) AS month,
                SUM(CASE WHEN c.type = 'income' THEN t.amount ELSE -t.amount END) AS balance
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = :user_id
            GROUP BY month
            ORDER BY month DESC
            LIMIT :months
        ");

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

}
