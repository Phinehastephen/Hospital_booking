<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';

requireRole(ROLE_ADMIN);

// Approve doctor
if (isset($_GET['approve'])) {
    $doctorId = (int) $_GET['approve'];

    $stmt = $pdo->prepare("
        UPDATE doctors 
        SET approval_status = :status 
        WHERE doctor_id = :id
    ");
    $stmt->execute([
        ':status' => DOCTOR_APPROVED,
        ':id' => $doctorId
    ]);

    header("Location: manage_doctors.php");
    exit;
}

// Fetch doctors
$doctors = $pdo->query("
    SELECT d.*, s.name AS specialization
    FROM doctors d
    JOIN specializations s ON d.specialization_id = s.specialization_id
")->fetchAll();
?>

<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>

<div class="container mt-4">
    <h4>Doctors</h4>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Specialization</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($doctors as $doc): ?>
            <tr>
                <td><?= htmlspecialchars($doc['full_name']) ?></td>
                <td><?= htmlspecialchars($doc['specialization']) ?></td>
                <td><?= $doc['approval_status'] ?></td>
                <td>
                    <?php if ($doc['approval_status'] === DOCTOR_PENDING): ?>
                        <a href="?approve=<?= $doc['doctor_id'] ?>" 
                           class="btn btn-sm btn-success">
                           Approve
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Approved</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
