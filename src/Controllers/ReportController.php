<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Transaction;

class ReportController {
    private Transaction $transaction;

    public function __construct() {
        $this->transaction = new Transaction();
    }

    public function monthlySummary(): void {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /views/login.html");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $month = $_GET['month'] ?? date('Y-m-01'); // Default: current month

        $summary = $this->transaction->getMonthlySummary($user_id, $month);
        header('Content-Type: application/json');
        echo json_encode($summary);
    }

    public function categoryBreakdown(): void {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /views/login.html");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $month = $_GET['month'] ?? date('Y-m-01');

        $categories = $this->transaction->getCategoryBreakdown($user_id, $month);
        header('Content-Type: application/json');
        echo json_encode($categories);
    }

    public function balanceTrend(): void {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /views/login.html");
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $months = (int) ($_GET['months'] ?? 6);

        $trend = $this->transaction->getBalanceTrend($user_id, $months);
        header('Content-Type: application/json');
        echo json_encode($trend);
    }
}
