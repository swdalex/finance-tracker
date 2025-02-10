<?php
$dsn = "pgsql:host=postgres;port=5432;dbname=finance_db";
$user = "finance_user";
$pass = "finance_pass";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected to PostgreSQL successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
