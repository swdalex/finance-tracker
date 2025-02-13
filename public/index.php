<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Controllers\AuthController;
use App\Controllers\TransactionController;
use App\Controllers\ReportController;

$app = AppFactory::create();

// Register route for dashboard redirect
$app->get('/', function (Request $request, Response $response) {
    return $response->withHeader('Location', '/views/dashboard.php')->withStatus(302);
});

// Authentication Routes
$app->post('/register', [new AuthController(), 'register']);
$app->post('/login', [new AuthController(), 'login']);
$app->post('/logout', [new AuthController(), 'logout']);

// Transaction Routes
$app->post('/transactions/create', [new TransactionController(), 'createTransaction']);
$app->post('/transactions/delete', [new TransactionController(), 'deleteTransaction']);

// Report Routes
$app->get('/reports/monthly_summary', [new ReportController(), 'monthlySummary']);
$app->get('/reports/category_breakdown', [new ReportController(), 'categoryBreakdown']);
$app->get('/reports/balance_trend', [new ReportController(), 'balanceTrend']);

// Error Handling (404)
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Run the application
$app->run();
