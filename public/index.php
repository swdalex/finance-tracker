<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\TransactionController;
use App\Controllers\ReportController;

// Get the route from the query string
$route = $_GET['route'] ?? '';

// Define route handling
$response = match ($route) {
    '' => call_user_func(function (): void {
        header("Location: /views/dashboard.php");
        exit();
    }),
    'register' => (new AuthController())->register(),
    'login' => (new AuthController())->login(),
    'logout' => (new AuthController())->logout(),
    'create_transaction' => (new TransactionController())->createTransaction(),
    'delete_transaction' => (new TransactionController())->deleteTransaction(),
    'monthly_summary' => (new ReportController())->monthlySummary(),
    'category_breakdown' => (new ReportController())->categoryBreakdown(),
    'balance_trend' => (new ReportController())->balanceTrend(),
    default => '404 Not Found',
};

echo $response;
