<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
class Database {
    private static ?PDO $pdo = null;

    private function __construct() {
        // Singleton. Prevent direct object creation
    }

    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            $dsn = "pgsql:host=postgres;port=5432;dbname=finance_db";
            $user = "finance_user";
            $password = "finance_pass";

            try {
                self::$pdo = new PDO(
                    $dsn,
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
