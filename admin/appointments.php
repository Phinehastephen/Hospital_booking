<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

requireRole(ROLE_ADMIN);

$stmt = $pdo->prepare("
    SELECT 
        a.appointment_date,
        a.appointment_time,
        a.status,

        p.full_name AS patient_name,
        p.phone AS patient_phone,

        d.full_name AS doctor_name,
        d.specialization

    FROM appointments a
    JOIN patients p ON a.patient_id = p.patient_id
    JOIN doctors d ON a.doctor_id = d.doctor_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$stmt->execute();

$appointments = $stmt->fetchAll();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>All Appointments</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <h4 style="text-align: center;">Appointment Records</h4>

    <table class="table table-bordered table-striped mt-3" style="justify-self: center; width: 80%;">
        <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Patient</th>
            <th>Patient Phone</th>
            <th>Doctor</th>
            <th>Specialization</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>

        <?php if ($appointments): ?>
            <?php foreach ($appointments as $a): ?>
                <?php
                $color = match ($a['status']) {
                    APPOINTMENT_PENDING => 'warning',
                    APPOINTMENT_APPROVED => 'success',
                    APPOINTMENT_REJECTED => 'danger',
                    APPOINTMENT_COMPLETED => 'primary',
                    default => 'secondary'
                };
                ?>
                <tr>
                    <td><?= htmlspecialchars($a['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($a['appointment_time']) ?></td>
                    <td><?= htmlspecialchars($a['patient_name']) ?></td>
                    <td><?= htmlspecialchars($a['patient_phone']) ?></td>
                    <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($a['specialization']) ?></td>
                    <td>
                        <span class="badge bg-<?= $color ?>">
                            <?= ucfirst($a['status']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">
                    No appointments found
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

</body>
</html>
