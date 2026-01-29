<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../logic/appointment_logic.php';

requireRole(ROLE_PATIENT);

/* ---------------------------
   Get patient ID
---------------------------- */
$stmt = $pdo->prepare("
    SELECT patient_id 
    FROM patients 
    WHERE user_id = :uid
");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$patient = $stmt->fetch();

if (!$patient) {
    die("Patient profile not found.");
}

$patientId = $patient['patient_id'];

/* ---------------------------
   Fetch approved doctors
---------------------------- */
$stmt = $pdo->prepare("
    SELECT doctor_id, full_name, specialization
    FROM doctors
    WHERE status = :status
    ORDER BY full_name
");
$stmt->execute([':status' => DOCTOR_APPROVED]);
$doctors = $stmt->fetchAll();

/* ---------------------------
   Fetch availability (if doctor selected)
---------------------------- */
$availability = [];

if (!empty($_POST['doctor_id'])) {
    $stmt = $pdo->prepare("
        SELECT day_of_week, start_time, end_time
        FROM doctor_availability
        WHERE doctor_id = :doctor
        AND status = :available
        ORDER BY FIELD(day_of_week,'Mon','Tue','Wed','Thu','Fri','Sat','Sun')
    ");
    $stmt->execute([
        ':doctor' => $_POST['doctor_id'],
        ':available' => AVAILABLE
    ]);
    $availability = $stmt->fetchAll();
}

/* ---------------------------
   Handle booking
---------------------------- */
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_date'])) {
    $result = bookAppointment(
        $patientId,
        $_POST['doctor_id'],
        $_POST['appointment_date'],
        $_POST['appointment_time']
    );

    $message = ($result === true)
        ? "Appointment booked successfully. Await doctor approval."
        : $result;
}
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="col-md-6 mx-auto">

        <h4 class="mb-3">Book Appointment</h4>

        <?php if ($message): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <!-- Doctor -->
            <select name="doctor_id" class="form-control mb-3" required onchange="this.form.submit()">
                <option value="">Select Doctor</option>
                <?php foreach ($doctors as $d): ?>
                    <option value="<?= $d['doctor_id'] ?>"
                        <?= (!empty($_POST['doctor_id']) && $_POST['doctor_id'] == $d['doctor_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['full_name']) ?> (<?= htmlspecialchars($d['specialization']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Availability display -->
            <?php if ($availability): ?>
                <div class="alert alert-secondary">
                    <strong>Doctor Availability:</strong>
                    <ul class="mb-0">
                        <?php foreach ($availability as $a): ?>
                            <li>
                                <?= $a['day_of_week'] ?> :
                                <?= substr($a['start_time'], 0, 5) ?> -
                                <?= substr($a['end_time'], 0, 5) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (!empty($_POST['doctor_id'])): ?>
                <div class="alert alert-warning">
                    This doctor has no availability set.
                </div>
            <?php endif; ?>

            <!-- Date & Time -->
            <input type="date" name="appointment_date" class="form-control mb-3" required>
            <input type="time" name="appointment_time" class="form-control mb-3" required>

            <button class="btn btn-primary w-100">
                Book Appointment
            </button>
        </form>

    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>