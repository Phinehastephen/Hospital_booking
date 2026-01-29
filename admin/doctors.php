<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

requireRole(ROLE_ADMIN);

// Handle approve / reject
if (isset($_GET['action'], $_GET['id'])) {
    $doctorId = (int) $_GET['id'];
    $status = null;

    if ($_GET['action'] === 'approve') {
        $status = DOCTOR_APPROVED;
    } elseif ($_GET['action'] === 'reject') {
        $status = DOCTOR_REJECTED;
    }

    if ($status) {
        $stmt = $pdo->prepare("
            UPDATE doctors
            SET status = :status
            WHERE doctor_id = :id
        ");
        $stmt->execute([
            ':status' => $status,
            ':id' => $doctorId
        ]);
    }

    header("Location: doctors.php");
    exit;
}

// Fetch doctors
$stmt = $pdo->prepare("
    SELECT 
        doctor_id,
        full_name,
        specialization,
        phone,
        status
    FROM doctors
    ORDER BY status ASC, full_name
");
$stmt->execute();
$doctors = $stmt->fetchAll();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<link rel="stylesheet" href="../assets/css/main.css">

<div class="container mt-4">
    <h4 style="text-align: center; padding:60px">Doctor Management</h4>
    <table class="table table-bordered table-hover mt-3" style="justify-self: center; width: 80%;">
        <thead class="table-light" >
            <tr >
                <th>Name</th>
                <th>Specialization</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php if (!empty($doctors)): ?>
            <?php foreach ($doctors as $d): ?>
                <?php
                $color = match ($d['status']) {
                    DOCTOR_PENDING => 'warning',
                    DOCTOR_APPROVED => 'success',
                    DOCTOR_REJECTED => 'danger',
                    default => 'secondary'
                };
                ?>
                <tr>
                    <td><?= htmlspecialchars($d['full_name']) ?></td>
                    <td><?= htmlspecialchars($d['specialization']) ?></td>
                    <td><?= htmlspecialchars($d['phone']) ?></td>
                    <td>
                        <span class="badge bg-<?= $color ?>">
                            <?= ucfirst($d['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($d['status'] === DOCTOR_PENDING): ?>
                            <a href="?action=approve&id=<?= $d['doctor_id'] ?>"
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Approve this doctor?')">
                               Approve
                            </a>

                            <a href="?action=reject&id=<?= $d['doctor_id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Reject this doctor?')">
                               Reject
                            </a>
                        <?php else: ?>
                            <span class="text-muted">No action</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No doctors found
                </td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
