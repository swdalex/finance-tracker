<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

class AuthController {
    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    public function register(): void {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /views/register.html");
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (!$email || !$password) {
            echo "Invalid input.";
            return;
        }

        if ($this->user->register($email, $password)) {
            header("Location: /views/login.html?success=registered");
        } else {
            echo "Registration failed.";
        }
    }

    public function login(): void {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /views/login.html");
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (!$email || !$password) {
            echo "Invalid input.";
            return;
        }

        if ($this->user->login($email, $password)) {
            header("Location: /views/dashboard.php");
        } else {
            echo "Invalid login credentials.";
        }
    }

    public function logout(): void {
        $this->user->logout();
        header("Location: /views/login.html");
        exit;
    }
}
