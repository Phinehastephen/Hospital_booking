<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../logic/availability_logic.php';

requireRole(ROLE_DOCTOR);

/* ---------------------------
   Get doctor ID
---------------------------- */
$stmt = $pdo->prepare("
    SELECT doctor_id 
    FROM doctors 
    WHERE user_id = :uid
");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$doctor = $stmt->fetch();

if (!$doctor) {
    die("Doctor profile not found.");
}

$doctorId = $doctor['doctor_id'];

/* ---------------------------
   Handle add availability
---------------------------- */
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['day'])) {
    $result = addAvailability(
        $doctorId,
        $_POST['day'],
        $_POST['start_time'],
        $_POST['end_time']
    );

    $message = ($result === true)
        ? "Availability added successfully."
        : $result;
}

/* ---------------------------
   Handle delete availability
---------------------------- */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("
        DELETE FROM doctor_availability
        WHERE availability_id = :id
        AND doctor_id = :doctor
    ");
    $stmt->execute([
        ':id' => $_GET['delete'],
        ':doctor' => $doctorId
    ]);

    header("Location: availability.php");
    exit;
}

/* ---------------------------
   Fetch availability
---------------------------- */
$availability = getDoctorAvailability($doctorId);
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="col-md-10 mx-auto">

        <h4 class="mb-3">My Availability</h4>

        <?php if ($message): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Add availability -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-3">Add Availability</h6>

                <form method="POST" class="row g-2">
                    <div class="col-md-3">
                        <select name="day" class="form-control" required>
                            <option value="">Day</option>
                            <?php foreach ($DAYS_OF_WEEK as $d): ?>
                                <option value="<?= $d ?>"><?= $d ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Availability list -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Weekly Availability</h6>

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($availability): ?>
                        <?php foreach ($availability as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['day_of_week']) ?></td>
                                <td><?= substr($a['start_time'], 0, 5) ?></td>
                                <td><?= substr($a['end_time'], 0, 5) ?></td>
                                <td>
                                    <span class="badge bg-success">
                                        Available
                                    </span>
                                </td>
                                <td>
                                    <a href="?delete=<?= $a['availability_id'] ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this availability?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No availability set
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>