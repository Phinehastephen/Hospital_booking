<?php
require_once __DIR__ . '/../logic/registration_logic.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registerPatient($_POST);
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
    <title>Patient Registration</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <h4 class="mb-3">Patient Registration</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input class="form-control mb-2" name="full_name" placeholder="Full Name" required>
            <input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
            <input class="form-control mb-2" name="phone" placeholder="Phone" required>
            <select class="form-control mb-2" name="gender" required>
                <option value="">Gender</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
            <input class="form-control mb-2" name="age" type="number" placeholder="Age" required>
            <input class="form-control mb-2" name="password" type="password" placeholder="Password" required>

            <button class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</div>

</body>
</html>
