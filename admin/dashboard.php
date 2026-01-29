<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

requireRole(ROLE_ADMIN);

/**Total doctors*/
$stmt = $pdo->query("SELECT COUNT(*) FROM doctors");
$doctorCount = $stmt->fetchColumn();

/** Total appointments*/
$stmt = $pdo->query("SELECT COUNT(*) FROM appointments");
$appointmentCount = $stmt->fetchColumn();

/** Pending doctors*/
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM doctors 
    WHERE status = :status
");
$stmt->execute([
    ':status' => DOCTOR_PENDING
]);
$pendingDoctorCount = $stmt->fetchColumn();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>


<div class="container mt-4">
    <h4 class="mb-4" style="padding-top:40px ;">Admin Dashboard</h4>
       <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Total Doctors</h6>
                    <h3><?= $doctorCount ?></h3>
                    <small class="text-muted">Registered doctors</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Total Appointments</h6>
                    <h3><?= $appointmentCount ?></h3>
                    <small class="text-muted">All bookings</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <h6>Pending Doctors</h6>
                    <h3><?= $pendingDoctorCount ?></h3>
                    <small class="text-muted">Awaiting approval</small>
                </div>
            </div>
        </div>
</div>