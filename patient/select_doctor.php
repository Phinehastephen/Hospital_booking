<?php session_start(); ?>

<h4>Available Doctors Matching Your Symptoms</h4>

<?php foreach ($_SESSION['matched_doctors'] as $doctor): ?>

<div class="card mb-3">
    <div class="card-body">
        <h5><?= htmlspecialchars($doctor['name']) ?></h5>
        <p>Specialty: <?= htmlspecialchars($doctor['specialty']) ?></p>

        <form method="POST" action="../logic/confirm_booking.php">
            <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
            <input type="hidden" name="symptoms" value="<?= htmlspecialchars($_SESSION['symptoms']) ?>">
            <button class="btn btn-primary">Select Doctor</button>
        </form>
    </div>
</div>

<?php endforeach; ?>