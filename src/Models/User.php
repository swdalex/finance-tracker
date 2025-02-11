<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
        session_start();
    }

    public function register(string $email, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        return $stmt->execute(['email' => $email, 'password' => $hashedPassword]);
    }

    public function login(string $email, string $password): bool {
        $stmt = $this->pdo->prepare("SELECT id, password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }

    public function logout(): void {
        session_unset();
        session_destroy();
    }

    public function getId(): ?int {
        return $this->isAuthenticated() ? $_SESSION['user_id'] : null;
    }

    public function isAuthenticated(): bool {
        return isset($_SESSION['user_id']);
    }
}
