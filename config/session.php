<?php
/**
 * Session initialization and helper functions
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get logged-in user role
 */
function userRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Redirect user based on role
 */
function redirectByRole($role) {
    switch ($role) {
        case ROLE_PATIENT:
            header("Location: ../patient/dashboard.php");
            exit;
        case ROLE_DOCTOR:
            header("Location: ../doctor/dashboard.php");
            exit;
        case ROLE_ADMIN:
            header("Location: ../admin/dashboard.php");
            exit;
        default:
            header("Location: ../index.php");
            exit;
    }
}

/**
 * Protect pages by role
 */
function requireRole($requiredRole) {
    if (!isLoggedIn() || userRole() !== $requiredRole) {
        header("Location: ../auth/login.php");
        exit;
    }
}
