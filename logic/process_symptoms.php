<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/match_specialty.php';

if (!isset($_POST['symptoms']) || empty(trim($_POST['symptoms']))) {

    $_SESSION['error'] = "Please enter your symptoms.";
    header("Location: ../patient/book.php");
    exit;
}

$symptoms = trim($_POST['symptoms']);

$matchedSpecialty = matchSpecialty($pdo, $symptoms);

echo $matchedSpecialty;
exit;

if (!$matchedSpecialty) {

    $_SESSION['error'] = "We could not determine the appropriate specialty from your symptoms.";

    header("Location: ../patient/book.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT d.*, a.day_of_week, a.start_time, a.end_time
    FROM doctors d
    JOIN doctor_availability a
        ON d.doctor_id = a.doctor_id
    WHERE LOWER(d.specialization) = LOWER(?)
    AND a.status = 'available'
");

$stmt->execute(["%$matchedSpecialty%"]);

$doctors = $stmt->fetchAll();

if (empty($doctors)) {

    $_SESSION['error'] =
        "No doctors are currently available for this specialty.";

    header("Location: ../patient/book.php");
    exit;
}

$_SESSION['matched_doctors'] = $doctors;
$_SESSION['symptoms'] = $symptoms;

header("Location: ../patient/select_doctor.php");
exit;