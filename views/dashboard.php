<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\User;

$user = new User();

if (!$user->isAuthenticated()) {
    header("Location: login.html");
    exit;
}

echo "<h1>Welcome to your Finance Tracker</h1>";
echo "<a href='/src/logout.php'>Logout</a>";
