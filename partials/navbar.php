<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2"
           href="<?= BASE_URL ?>/index.php">
            <span class="logo-icon">🏥</span>
            <span class="logo-text">Marv</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-center gap-lg-2">

                <?php if ($_SESSION['role'] === ROLE_ADMIN): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/admin/appointments.php">Appointments</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === ROLE_DOCTOR): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/doctor/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/doctor/availability.php">Availability</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/doctor/appointments.php">Appointments</a>
                    </li>
                <?php endif; ?>

                <?php if ($_SESSION['role'] === ROLE_PATIENT): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/patient/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/patient/book_appointment.php">
                            Book
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/patient/my_appointments.php">
                            My Appointments
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item ms-lg-3">
                    <a href="<?= BASE_URL ?>/auth/logout.php"
                       class="btn btn-sm btn-danger px-3">
                        Logout
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
