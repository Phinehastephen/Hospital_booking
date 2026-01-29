<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Doctor-only fields
    $specialization = $_POST['specialization'] ?? null;
    $phone = $_POST['phone'] ?? null;

    if (!$fullName || !$email || !$password || !$role) {
        $message = "All fields are required.";
    } else {
        // Check email uniqueness
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            $message = "Email already exists.";
        } else {
            // Create user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (email, password, role)
                VALUES (:email, :password, :role)
            ");
            $stmt->execute([
                ':email' => $email,
                ':password' => $hashedPassword,
                ':role' => $role
            ]);

            $userId = $pdo->lastInsertId();

            // Patient registration
            if ($role === ROLE_PATIENT) {
                $stmt = $pdo->prepare("
                    INSERT INTO patients (user_id, full_name)
                    VALUES (:uid, :name)
                ");
                $stmt->execute([
                    ':uid' => $userId,
                    ':name' => $fullName
                ]);

                header("Location: login.php?registered=1");
                exit;
            }

            // Doctor registration
            if ($role === ROLE_DOCTOR) {
                if (!$specialization || !$phone) {
                    $message = "Specialization and phone number are required for doctors.";
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO doctors 
                        (user_id, full_name, specialization, phone, status)
                        VALUES (:uid, :name, :spec, :phone, :status)
                    ");
                    $stmt->execute([
                        ':uid' => $userId,
                        ':name' => $fullName,
                        ':spec' => $specialization,
                        ':phone' => $phone,
                        ':status' => DOCTOR_PENDING
                    ]);

                    $message = "Registration successful. Await admin approval.";
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="auth-page bg-light">

<div class="container mt-5">
    <div class="col-md-5 mx-auto">
        
        <?php if ($message): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <div class="auth-container">
                <div class="auth-card" style="width: auto;">
                    <h4 class="mb-3" style="color: #007bff; font-size: 24px">Create Account</h4>
                    <form method="POST" style="justify-content: center;">
                        <input type="text" name="full_name" class="form-control mb-3" style="width: 80%;" placeholder="Full Name" required><br><br>
                        <input type="email" name="email" class="form-control mb-3" style="width: 80%;" placeholder="Email" required><br><br>
                        <input type="password" name="password" class="form-control mb-3" style="width: 80%;" placeholder="Password" required><br><br>
            
                        
                        <!-- Doctor-only fields -->
                        <div id="doctorFields" style="display:none;">
                            <input type="text" name="specialization" class="form-control mb-3" style="width: 80%;"
                            placeholder="Specialization (e.g. Cardiologist)"><br><br>
                            <input type="text" name="phone" class="form-control mb-3" style="width: 80%;"
                            placeholder="Phone Number">
                        </div><br>
                        
                        <select name="role" id="roleSelect" class="form-control mb-3" required>
                            <option value="">Register as</option>
                            <option value="<?= ROLE_PATIENT ?>">Patient</option>
                            <option value="<?= ROLE_DOCTOR ?>">Doctor</option>
                        </select>
                        <button class="btn btn-primary w-100">Register</button>
                    </form><br>
                    <div class="text-center mt-3">
                        <a href="login.php">Already have an account? Login</a>
                    </div>
                </div>
            </div>
    </div>
</div>
<script>
document.getElementById('roleSelect').addEventListener('change', function () {
    document.getElementById('doctorFields').style.display =
        this.value === 'doctor' ? 'block' : 'none';
});
</script>

</body>
</html>
