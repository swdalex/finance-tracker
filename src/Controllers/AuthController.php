<?php

declare(strict_types=1);

namespace App\Controllers;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

class AuthController extends Controller {
    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    public function register(Request $request, Response $response): Response {
        $data = json_decode($request->getBody()->getContents(), true);
        $email = isset($data['email']) ? filter_var($data['email'], FILTER_SANITIZE_EMAIL) : null;
        $password = isset($data['password']) ? htmlspecialchars($data['password']) : null;

        $error = '';
        $status = 200;
        if (!$email) {
            $error = 'Invalid email';
            $status = 400;
        }

        if (!$password || strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
            $status = 400;
        }

        try {
            if (!$this->user->register($email, $password)) {
                $error = 'Registration failed';
                $status = 500;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            $status = 500;
        }

        if ($error) {
            return $this->jsonResponse($response, ['error' => $error], $status);
        }

        return $this->jsonResponse($response, ['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request, Response $response): Response {
        $data = json_decode($request->getBody()->getContents(), true);
        $email = isset($data['email']) ? filter_var($data['email'], FILTER_SANITIZE_EMAIL) : null;
        $password = isset($data['password']) ? htmlspecialchars($data['password']) : null;

        $error = '';
        $status = 200;
        if (!$email) {
            $error = 'Invalid email';
            $status = 400;
        }

        if (!$password) {
            $error = 'Invalid input';
            $status = 400;
        }

        try {
            if (!$this->user->login($email, $password)) {
                $error = 'Invalid login credentials';
                $status = 500;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            $status = 500;
        }

        if ($error) {
            return $this->jsonResponse($response, ['error' => $error], $status);
        }

        return $this->jsonResponse($response, ['message' => 'User logged in successfully'], 200);
    }

    public function logout(Request $request, Response $response): Response {
        try {
            $this->user->logout();
        } catch (Exception $e) {
            return $this->jsonResponse($response, ['error' => $e->getMessage()], 500);
        }

        return $this->jsonResponse($response, ['message' => 'User logged out successfully'], 200);
    }
}
