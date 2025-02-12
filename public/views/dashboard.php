<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\User;

$user = new User();

if (!$user->isAuthenticated()) {
    header("Location: /views/login.html");
    exit;
}

echo "<h1>Welcome to your Finance Tracker</h1>";
echo "<a href='/views/transactions.php'>Transactions</a><br />";
echo "<a href='/views/reports.php'>Reports</a><br />";
echo "<a href='/index.php?route=logout'>Logout</a>";
