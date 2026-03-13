<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendMail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // YOUR GMAIL
        $mail->Username = 'yourgmail@gmail.com';

        // Gmail App Password
        $mail->Password = 'your_app_password';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourgmail@gmail.com', 'Marv Hospital');

        $mail->addAddress($to);

        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();

    } catch (Exception $e) {

        echo "Mail error: " . $mail->ErrorInfo;
    }
}