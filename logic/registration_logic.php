<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';

/**
 * PATIENT REGISTRATION
 */
function registerPatient($data) {
    global $pdo;

    // Check if email already exists
    $check = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
    $check->execute([':email' => $data['email']]);
    if ($check->fetch()) {
        return "Email already exists.";
    }

    try {
        $pdo->beginTransaction();

        // Insert into users table
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password, role, status)
            VALUES (:email, :password, :role, :status)
        ");
        $stmt->execute([
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => ROLE_PATIENT,
            ':status' => STATUS_ACTIVE
        ]);

        $userId = $pdo->lastInsertId();

        // Insert into patients table
        $stmt = $pdo->prepare("
            INSERT INTO patients (user_id, full_name, phone, gender, age)
            VALUES (:user_id, :full_name, :phone, :gender, :age)
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':full_name' => $data['full_name'],
            ':phone' => $data['phone'],
            ':gender' => $data['gender'],
            ':age' => $data['age']
        ]);

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        return "Registration failed. Try again.";
    }
}

/**
 * DOCTOR REGISTRATION
 */
function registerDoctor($data) {
    global $pdo;

    // Check if email already exists
    $check = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
    $check->execute([':email' => $data['email']]);
    if ($check->fetch()) {
        return "Email already exists.";
    }

    try {
        $pdo->beginTransaction();

        // Insert into users
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password, role, status)
            VALUES (:email, :password, :role, :status)
        ");
        $stmt->execute([
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => ROLE_DOCTOR,
            ':status' => STATUS_ACTIVE
        ]);

        $userId = $pdo->lastInsertId();

        // Insert into doctors table
        $stmt = $pdo->prepare("
            INSERT INTO doctors (user_id, specialization_id, full_name, phone, approval_status)
            VALUES (:user_id, :specialization_id, :full_name, :phone, :approval_status)
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':specialization_id' => $data['specialization_id'],
            ':full_name' => $data['full_name'],
            ':phone' => $data['phone'],
            ':approval_status' => DOCTOR_PENDING
        ]);

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        return "Registration failed. Try again.";
    }
}
