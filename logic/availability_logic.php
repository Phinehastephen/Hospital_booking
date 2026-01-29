<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';

/**
 * Add doctor availability
 */
function addAvailability($doctorId, $day, $startTime, $endTime) {
    global $pdo;

    // Prevent duplicate availability for same day
    $check = $pdo->prepare("
        SELECT availability_id
        FROM doctor_availability
        WHERE doctor_id = :doctor
        AND day_of_week = :day
    ");
    $check->execute([
        ':doctor' => $doctorId,
        ':day' => $day
    ]);

    if ($check->fetch()) {
        return "Availability for this day already exists.";
    }

    $stmt = $pdo->prepare("
        INSERT INTO doctor_availability
        (doctor_id, day_of_week, start_time, end_time, status)
        VALUES (:doctor, :day, :start, :end, :status)
    ");
    $stmt->execute([
        ':doctor' => $doctorId,
        ':day' => $day,
        ':start' => $startTime,
        ':end' => $endTime,
        ':status' => AVAILABLE
    ]);

    return true;
}

/**
 * Fetch doctor availability
 */
function getDoctorAvailability($doctorId) {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM doctor_availability
        WHERE doctor_id = :doctor
        ORDER BY FIELD(day_of_week,'Mon','Tue','Wed','Thu','Fri','Sat')
    ");
    $stmt->execute([':doctor' => $doctorId]);

    return $stmt->fetchAll();
}
