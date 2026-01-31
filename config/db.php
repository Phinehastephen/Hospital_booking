<?php
/**
 * Database connection using PDO
 * Clever Cloud compatible
 */

$host = getenv('MYSQL_ADDON_HOST');
$dbname = getenv('MYSQL_ADDON_DB');
$username = getenv('MYSQL_ADDON_USER');
$password = getenv('MYSQL_ADDON_PASSWORD');
$port = getenv('MYSQL_ADDON_PORT');

if (!$host || !$dbname || !$username) {
    die("Database environment variables not set.");
}

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed.");
}