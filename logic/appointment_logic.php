<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';

/**
 * Book appointment for a selected doctor
 */
function bookAppointment($patientId, $doctorId, $date, $time) {
    global $pdo;

    // Get day of week from date (Mon, Tue, Wed...)
    $day = date('D', strtotime($date));

    // 1️⃣ Check doctor availability
    $stmt = $pdo->prepare("
        SELECT availability_id
        FROM doctor_availability
        WHERE doctor_id = :doctor
        AND day_of_week = :day
        AND :time BETWEEN start_time AND end_time
        AND status = :available
    ");
    $stmt->execute([
        ':doctor' => $doctorId,
        ':day' => $day,
        ':time' => $time,
        ':available' => AVAILABLE
    ]);

    if (!$stmt->fetch()) {
        return "Doctor is not available at the selected time.";
    }

    // 2️⃣ Prevent double booking
    $stmt = $pdo->prepare("
        SELECT appointment_id
        FROM appointments
        WHERE doctor_id = :doctor
        AND appointment_date = :date
        AND appointment_time = :time
        AND (status = :pending OR status = :approved)
    ");
    $stmt->execute([
        ':doctor' => $doctorId,
        ':date' => $date,
        ':time' => $time,
        ':pending' => APPOINTMENT_PENDING,
        ':approved' => APPOINTMENT_APPROVED
    ]);

    if ($stmt->fetch()) {
        return "This time slot is already booked.";
    }

    // 3️⃣ Insert appointment
    $stmt = $pdo->prepare("
        INSERT INTO appointments 
        (patient_id, doctor_id, appointment_date, appointment_time, status)
        VALUES (:patient, :doctor, :date, :time, :status)
    ");
    $stmt->execute([
        ':patient' => $patientId,
        ':doctor' => $doctorId,
        ':date' => $date,
        ':time' => $time,
        ':status' => APPOINTMENT_PENDING
    ]);

    return true;
}
