<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/constants.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

switch ($_SESSION['role']) {
    case ROLE_ADMIN:
        header("Location: admin/dashboard.php");
        break;

    case ROLE_DOCTOR:
        header("Location: doctor/dashboard.php");
        break;

    case ROLE_PATIENT:
        header("Location: patient/dashboard.php");
        break;

    default:
        header("Location: auth/login.php");
}
exit;
?>