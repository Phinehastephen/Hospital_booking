<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

requireRole(ROLE_PATIENT);

// Get patient_id
$stmt = $pdo->prepare("
    SELECT patient_id 
    FROM patients 
    WHERE user_id = :uid
");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$patient = $stmt->fetch();

$patientId = $patient['patient_id'];

// Fetch appointments
$stmt = $pdo->prepare("
    SELECT 
        a.appointment_date,
        a.appointment_time,
        a.status,
        d.full_name AS doctor_name,
        d.specialization
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.doctor_id
    WHERE a.patient_id = :patient
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$stmt->execute([':patient' => $patientId]);

$appointments = $stmt->fetchAll();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>My Appointments</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>

<div class="container mt-4">
    <h4>My Appointments</h4>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php if ($appointments): ?>
            <?php foreach ($appointments as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($a['appointment_time']) ?></td>
                    <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($a['specialization']) ?></td>
                    <td><?= htmlspecialchars($a['status']) ?></td>
                        <?php
                        $color = match ($a['status']) {
                            APPOINTMENT_PENDING => 'warning',
                            APPOINTMENT_APPROVED => 'success',
                            APPOINTMENT_REJECTED => 'danger',
                            APPOINTMENT_COMPLETED => 'primary',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $color ?>">
                            <?= htmlspecialchars(ucfirst($a['status'])) ?>
                        </span>
                    <td>
                       <?php
                        $color = match ($a['status']) {
                            APPOINTMENT_PENDING => 'warning',
                            APPOINTMENT_APPROVED => 'success',
                            APPOINTMENT_REJECTED => 'danger',
                            APPOINTMENT_COMPLETED => 'primary',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?= $color ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">
                    No appointments found
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>
</body>
</html>