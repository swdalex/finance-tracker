<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Transaction;

class TransactionController {
    private Transaction $transaction;

    public function __construct() {
        $this->transaction = new Transaction();
    }

    public function createTransaction(): void {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo "Invalid request";
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /views/login.html");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $category_id = (int) $_POST['category_id'];
        $amount = (float) $_POST['amount'];
        $description = $_POST['description'];
        $date = $_POST['transaction_date'];

        if ($this->transaction->createTransaction($user_id, $category_id, $amount, $description, $date)) {
            header("Location: /views/transactions.php");
        } else {
            echo "Transaction creation failed.";
        }
    }

    public function deleteTransaction(): void {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo "Invalid request";
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /views/login.html");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $transaction_id = (int) $_POST['transaction_id'];

        if ($this->transaction->deleteTransaction($transaction_id, $user_id)) {
            header("Location: /views/transactions.php");
        } else {
            echo "Failed to delete transaction.";
        }
    }
}
