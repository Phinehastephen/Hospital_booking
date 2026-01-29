<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

requireRole(ROLE_DOCTOR);

// Get doctor_id
$stmt = $pdo->prepare("SELECT doctor_id FROM doctors WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$doctor = $stmt->fetch();
$doctorId = $doctor['doctor_id'];

// Approve / Reject logic
if (isset($_GET['action'], $_GET['id'])) {
    $status = ($_GET['action'] === 'approve')
        ? APPOINTMENT_APPROVED
        : APPOINTMENT_REJECTED;

    $stmt = $pdo->prepare("
        UPDATE appointments
        SET status = :status
        WHERE appointment_id = :id
        AND doctor_id = :doctor_id
    ");
    $stmt->execute([
        ':status' => $status,
        ':id' => (int) $_GET['id'],
        ':doctor_id' => $doctorId
    ]);

    header("Location: appointments.php");
    exit;
}

// Mark appointment as completed
if (isset($_GET['complete'])) {
    $appointmentId = (int) $_GET['complete'];

    $stmt = $pdo->prepare("
        UPDATE appointments
        SET status = :status
        WHERE appointment_id = :id
        AND doctor_id = :doctor_id
        AND status = :approved
    ");
    $stmt->execute([
        ':status' => APPOINTMENT_COMPLETED,
        ':id' => $appointmentId,
        ':doctor_id' => $doctorId,
        ':approved' => APPOINTMENT_APPROVED
    ]);

    header("Location: appointments.php");
    exit;
}

// Fetch appointments
$appointments = $pdo->prepare("
    SELECT a.*, p.full_name AS patient_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.patient_id
    WHERE a.doctor_id = :doctor_id
    ORDER BY a.appointment_date, a.appointment_time
");
$appointments->execute([':doctor_id' => $doctorId]);
$data = $appointments->fetchAll();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>

<div class="container mt-4">
    <h4 style="padding-top: 40px;">My Appointments</h4>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Patient</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['patient_name']) ?></td>
                <td><?= $a['appointment_date'] ?></td>
                <td><?= $a['appointment_time'] ?></td>
                <td><?= $a['status'] ?></td>
                <td>
                    <?php if ($a['status'] === APPOINTMENT_PENDING): ?>
                        <a href="?action=approve&id=<?= $a['appointment_id'] ?>" 
                        class="btn btn-success btn-sm">Approve</a>

                        <a href="?action=reject&id=<?= $a['appointment_id'] ?>" 
                        class="btn btn-danger btn-sm">Reject</a>

                    <?php elseif ($a['status'] === APPOINTMENT_APPROVED): ?>
                        <a href="?complete=<?= $a['appointment_id'] ?>" 
                        class="btn btn-primary btn-sm">
                        Mark Completed
                        </a>

                    <?php else: ?>
                        <span class="text-muted">No action</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
