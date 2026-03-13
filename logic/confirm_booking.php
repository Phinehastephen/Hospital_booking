<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../logic/mail_logic.php';
require_once __DIR__ . '/../logic/notification_logic.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['user_id'])) {
        die("User not logged in");
    }

    $doctor_id = $_POST['doctor_id'];
    $time = $_POST['time'];
    $date = $_POST['date']; 


    // get patient_id
    $stmt = $pdo->prepare("
        SELECT patient_id
        FROM patients
        WHERE user_id = :uid
    ");

    $stmt->execute([
        ':uid' => $_SESSION['user_id']
    ]);

    $patient = $stmt->fetch();

    if (!$patient) {
        die("Patient not found");
    }

    $patient_id = $patient['patient_id'];


    /* CHECK IF TIME ALREADY BOOKED */

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM appointments
        WHERE doctor_id = ?
        AND appointment_date = ?
        AND appointment_time = ?
    ");

    $stmt->execute([
        $doctor_id,
        $date,
        $time
    ]);

    $count = $stmt->fetchColumn();

    if ($count > 0) {

        $_SESSION['error'] =
            "This period of time has already been booked by another patient.";

        header("Location: ../patient/book.php");
        exit;
    }


    // INSERT APPOINTMENT

    $stmt = $pdo->prepare("
        INSERT INTO appointments
        (doctor_id, patient_id, appointment_date, appointment_time, status)
        VALUES (?, ?, ?, ?, 'pending')
    ");

    $stmt->execute([
        $doctor_id,
        $patient_id,
        $date,
        $time
    ]);


    
    //    GET PATIENT EMAIL

    $stmt = $pdo->prepare("
        SELECT email
        FROM users
        WHERE user_id = ?
    ");

    $stmt->execute([$_SESSION['user_id']]);

    $user = $stmt->fetch();

    $email = $user['email'];


        //    GET DOCTOR INFO

    $stmt = $pdo->prepare("
        SELECT name, specialization, user_id
        FROM doctors
        WHERE doctor_id = ?
    ");

    $stmt->execute([$doctor_id]);

    $doctor = $stmt->fetch();


    //    SEND EMAIL

    $message = "
Hello,

You have an appointment booked with
Dr {$doctor['name']} ({$doctor['specialization']})

Date: $date
Time: $time
";

    sendMail(
        $email,
        "Appointment Booked",
        $message
    );


    //    SEND WEBSITE NOTIFICATION TO DOCTOR

    addNotification(
        $doctor['user_id'],
        "New appointment booked for $date at $time"
    );


    $_SESSION['success'] = "Appointment booked successfully";

    header("Location: ../patient/dashboard.php");
    exit;
}