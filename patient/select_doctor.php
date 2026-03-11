<?php session_start(); ?>

<h4>Available Doctors Matching Your Symptoms</h4>

<?php foreach ($_SESSION['matched_doctors'] as $doctor): ?>

<div class="card mb-3">
    <div class="card-body">

        <h5><?= htmlspecialchars($doctor['full_name']) ?></h5>

        <p>
            Specialty:
            <?= htmlspecialchars($doctor['specialization']) ?>
        </p>

        <p>
            Day:
            <?= htmlspecialchars($doctor['day_of_week']) ?>
        </p>

        <p>
            Time:
            <?= htmlspecialchars($doctor['start_time']) ?>
            -
            <?= htmlspecialchars($doctor['end_time']) ?>
        </p>

        <form method="POST" action="../logic/confirm_booking.php">

        <input type="hidden"
            name="doctor_id"
            value="<?= $doctor['doctor_id'] ?>">

        <label>Select Date</label>
        <input type="date"
            name="date"
            class="form-control"
            required>

        <label class="mt-2">
        Available time (<?= $doctor['start_time'] ?> - <?= $doctor['end_time'] ?>)
        </label>

        <input type="time"
            name="time"
            min="<?= $doctor['start_time'] ?>"
            max="<?= $doctor['end_time'] ?>"
            class="form-control"
            required>

        <button class="btn btn-primary mt-2">
        Book Appointment
        </button>

        </form>
    </div>
</div>

<?php endforeach; ?>