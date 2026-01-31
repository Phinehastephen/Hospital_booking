<?php

// Clever Cloud environment variables (auto-injected)
$host = getenv('MYSQL_ADDON_HOST') ?: 'localhost';
$dbname = getenv('MYSQL_ADDON_DB') ?: 'project_marv';
$username = getenv('MYSQL_ADDON_USER') ?: 'root';
$password = getenv('MYSQL_ADDON_PASSWORD') ?: '';
$port = getenv('MYSQL_ADDON_PORT') ?: 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Log error internally (Clever Cloud logs)
    error_log("Database connection error: " . $e->getMessage());

    // Generic message for users
    die("Service temporarily unavailable. Please try again later.");
}
