<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\TransactionController;

// Get the route from the query string
$route = $_GET['route'] ?? '';

// Define route handling
$response = match ($route) {
    'register' => (new AuthController())->register(),
    'login' => (new AuthController())->login(),
    'logout' => (new AuthController())->logout(),
    'create_transaction' => (new TransactionController())->createTransaction(),
    'delete_transaction' => (new TransactionController())->deleteTransaction(),
    default => '404 Not Found',
};

echo $response;
