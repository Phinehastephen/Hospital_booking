<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="bg-light">

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert alert-danger">
    <?= $_SESSION['error'] ?>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form method="POST" action="../logic/process_symptoms.php">

    <div class="mb-3">
        <label class="form-label">Describe Your Symptoms</label>
        <textarea name="symptoms" class="form-control" required></textarea>
    </div>

    <button class="btn btn-primary" type="submit">
        Find Available Doctors
    </button>

</form>