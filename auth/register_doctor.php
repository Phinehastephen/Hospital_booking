<?php
require_once __DIR__ . '/../logic/registration_logic.php';
require_once __DIR__ . '/../config/db.php';

$error = '';

// Fetch specializations
$specs = $pdo->query("SELECT * FROM specializations")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registerDoctor($_POST);
    if ($result === true) {
        header("Location: login.php");
        exit;
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Registration</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <h4 class="mb-3">Doctor Registration</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input class="form-control mb-2" name="full_name" placeholder="Full Name" required>
            <input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
            <input class="form-control mb-2" name="phone" placeholder="Phone" required>

            <select class="form-control mb-2" name="specialization_id" required>
                <option value="">Select Specialization</option>
                <?php foreach ($specs as $s): ?>
                    <option value="<?= $s['specialization_id'] ?>">
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input class="form-control mb-2" name="password" type="password" placeholder="Password" required>

            <button class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</div>

</body>
</html>
