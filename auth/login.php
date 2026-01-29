<?php
require_once __DIR__ . '/../logic/auth_logic.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 
    $result = loginUser($email, $password);

    if ($result === true) {
        redirectByRole($_SESSION['role']);
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Marv Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class=" auth-page bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <div class="auth-container">
                    <div class="auth-card">
                    <h4 class="text-center mb-3" style="color: #007bff; font-size: 24px">Login</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Email        </label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div><br>
                
                        <button class="btn btn-primary w-100">Login</button><br> <br>
                        <a href="register.php" class="btn btn-primary w-100">Register</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
