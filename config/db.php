<?php
/**
 * Database connection using PDO
 * Supports local development and Clever Cloud
 */

$host = getenv('MYSQL_ADDON_HOST') ?: 'localhost';
$dbname = getenv('MYSQL_ADDON_DB') ?: 'project(marv)';
$username = getenv('MYSQL_ADDON_USER') ?: 'root';
$password = getenv('MYSQL_ADDON_PASSWORD') ?: '';
$port = getenv('MYSQL_ADDON_PORT') ?: 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // show errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // real prepared statements
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // In production, log this instead of displaying it
    die("Database connection failed: " . $e->getMessage());
}
