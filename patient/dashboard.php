<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';

requireRole(ROLE_PATIENT);

// Get patient ID
$stmt = $pdo->prepare("SELECT patient_id, full_name FROM patients WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$patient = $stmt->fetch();

$patientId = $patient['patient_id'];

// Fetch next appointment
$stmt = $pdo->prepare("
    SELECT 
        a.appointment_date,
        a.appointment_time,
        a.status,
        d.full_name AS doctor_name
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.doctor_id
    WHERE a.patient_id = :pid
    ORDER BY a.appointment_date ASC, a.appointment_time ASC
    LIMIT 1
");
$stmt->execute([':pid' => $patientId]);
$nextAppointment = $stmt->fetch();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">

    <h4 class="mb-3" style="padding-top: 40px;">Welcome, <?= htmlspecialchars($patient['full_name']) ?></h4>

    <div class="row g-3">

        <!-- Book Appointment Card -->
          <div class="col-md-6">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Book Appointment</h6>
                    <p class="text-muted small">
                        Schedule a new hospital visit
                    </p>
                    <a href="<?= BASE_URL ?>/patient/book_appointment.php"
                       class="btn btn-primary btn-sm">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Next Appointment Card -->
          <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h6>My Appointments</h6>
                        <p class="text-muted small">
                            View status & history
                        </p>
                        <a href="<?= BASE_URL ?>/patient/my_appointments.php"
                        class="btn btn-outline-primary btn-sm">
                            View
                        </a>
                    </div>
                </div>
                    <?php if ($nextAppointment): ?>
                        <p class="mb-1">
                            <strong>Doctor:</strong>
                            <?= htmlspecialchars($nextAppointment['doctor_name']) ?>
                        </p>
                        <p class="mb-1">
                            <strong>Date:</strong>
                            <?= $nextAppointment['appointment_date'] ?>
                        </p>
                        <p class="mb-2">
                            <strong>Time:</strong>
                            <?= $nextAppointment['appointment_time'] ?>
                        </p>

                        <?php
                            $status = $nextAppointment['status'];
                            $badge = 'secondary';

                            if ($status === APPOINTMENT_PENDING) $badge = 'warning';
                            if ($status === APPOINTMENT_APPROVED) $badge = 'success';
                            if ($status === APPOINTMENT_COMPLETED) $badge = 'primary';
                            if ($status === APPOINTMENT_REJECTED) $badge = 'danger';
                        ?>

                        <span class="badge bg-<?= $badge ?>">
                            <?= ucfirst($status) ?>
                        </span>

                    <?php else: ?>
                        <p class="text-muted">No appointments scheduled.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>

</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
