<?php

declare(strict_types=1);

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller {
    private Transaction $transaction;
    private User $user;

    public function __construct() {
        $this->transaction = new Transaction();
        $this->user = new User();
    }

    public function createTransaction(Request $request, Response $response): Response {
        if (!$this->user->isAuthenticated()) {
            return $this->jsonResponse($response, ['error' => 'Access denied'], 403);
        }

        $data = json_decode($request->getBody()->getContents(), true);
        $user_id = $this->user->getId();
        $category_id = isset($data['category_id']) ? (int) filter_var($data['category_id'], FILTER_SANITIZE_NUMBER_INT) : null;
        $amount = isset($data['amount']) ? (float) filter_var($data['amount'], FILTER_SANITIZE_NUMBER_FLOAT) : null;
        $description = isset($data['description']) ? htmlspecialchars($data['description']) : '';
        $date = isset($data['date']) ? htmlspecialchars($data['date']) : null;

        if ($this->transaction->createTransaction($user_id, $category_id, $amount, $description, $date)) {
            header("Location: /views/transactions.php");
        } else {
            echo "Transaction creation failed.";
        }
    }

    public function deleteTransaction(Request $request, Response $response): Response {
        if (!$this->user->isAuthenticated()) {
            return $this->jsonResponse($response, ['error' => 'Access denied'], 403);
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
