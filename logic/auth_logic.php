<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';


//  LOGIN FUNCTION
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


    /* CHECK DOCTOR APPROVAL */

    if ($user['role'] === 'doctor') {

        $stmt = $pdo->prepare("
            SELECT status
            FROM doctors
            WHERE user_id = :uid
        ");

        $stmt->execute([
            ':uid' => $user['user_id']
        ]);

        $doctor = $stmt->fetch();

        if (!$doctor) {
            return "Doctor record not found.";
        }

        if ($doctor['status'] !== 'approved') {
            return "Account rejected.";
        }
    }


    /* LOGIN SUCCESS */

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    return true;
}



//  LOGOUT FUNCTION
function logoutUser() {

    session_unset();
    session_destroy();

    header("Location: ../auth/login.php");
    exit;
}