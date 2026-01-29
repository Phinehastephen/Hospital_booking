<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';

/**
 * LOGIN FUNCTION
 */
function loginUser($email, $password) {
    global $pdo;

    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email,
    ]);

    $user = $stmt->fetch();

    if (!$user) {
        return "Invalid email or account inactive.";
    }

    if (!password_verify($password, $user['password'])) {
        return "Incorrect password.";
    }

    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    return true;
}

/**
 * LOGOUT FUNCTION
 */
function logoutUser() {
    session_unset();
    session_destroy();
    header("Location: ../auth/login.php");
    exit;
}
