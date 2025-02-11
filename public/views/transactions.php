<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;

$user = new User();

if (!$user->isAuthenticated()) {
    header("Location: login.html");
    exit;
}

$transaction = new Transaction();
$category = new Category();
$user_id = $user->getId();

$transactions = $transaction->getTransactions($user_id);
$categories = $category->getCategories($user_id);

?>

<!DOCTYPE html>
<html>
<head><title>Transactions</title></head>
<body>
<h2>Transactions</h2>

<form action="/index.php?route=create_transaction" method="POST">
    <select name="category_id">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?> (<?= $cat['type'] ?>)</option>
        <?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="amount" required placeholder="Amount">
    <input type="text" name="description" required placeholder="Description">
    <input type="date" name="transaction_date" required>
    <button type="submit">Add Transaction</button>
</form>

<h3>Transaction History</h3>
<ul>
    <?php foreach ($transactions as $t): ?>
        <li>
            <?= htmlspecialchars($t['transaction_date']) ?> -
            <?= htmlspecialchars($t['category']) ?> -
            €<?= htmlspecialchars($t['amount']) ?> -
            <?= htmlspecialchars($t['description']) ?>
            <form action="/index.php?route=delete_transaction" method="POST" style="display:inline;">
                <input type="hidden" name="transaction_id" value="<?= $t['id'] ?>">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
