<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

requireRole(ROLE_DOCTOR);

// Get doctor ID
$stmt = $pdo->prepare("
    SELECT doctor_id 
    FROM doctors 
    WHERE user_id = :uid
");
$stmt->execute([
    ':uid' => $_SESSION['user_id']
]);
$doctor = $stmt->fetch();

if (!$doctor) {
    die("Doctor record not found.");
}

$doctorId = $doctor['doctor_id'];

// Count appointments
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM appointments 
    WHERE doctor_id = :doctor
");
$stmt->execute([
    ':doctor' => $doctorId
]);
$appointmentCount = $stmt->fetchColumn();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">

    <h4 class="mb-4" style="padding-top: 40px;">Doctor Dashboard</h4>

    <!-- Status legend -->
    <div class="mb-3">
        <span class="badge bg-warning me-2">Pending</span>
        <span class="badge bg-success me-2">Approved</span>
        <span class="badge bg-primary">Completed</span>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-1">Total Appointments</h6>
                    <h3 class="fw-bold text-success">
                        <?= $appointmentCount ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mt-2">

        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Pending Appointments</h6>
                    <p class="text-muted small">Need your response</p>
                    <a href="<?= BASE_URL ?>/doctor/appointments.php"
                    class="btn btn-sm btn-primary">
                    Review
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Set Availability</h6>
                    <p class="text-muted small">Manage schedule</p>
                    <a href="<?= BASE_URL ?>/doctor/availability.php"
                    class="btn btn-sm btn-outline-primary">
                    Update
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
