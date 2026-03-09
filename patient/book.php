<?php
session_start();
?>

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