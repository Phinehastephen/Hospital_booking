<?php
include 'config.php'; // database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];

    $sql = "INSERT INTO bookings (service, date, time, name, phone, payment_method)
            VALUES ('$service', '$date', '$time', '$name', '$phone', '$payment_method')";

    if ($conn->query($sql) === TRUE) {
        echo "Booking confirmed successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>