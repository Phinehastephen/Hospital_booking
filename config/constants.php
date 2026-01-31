<?php
// ===== USER ROLES =====
define('ROLE_PATIENT', 'patient');
define('ROLE_DOCTOR', 'doctor');
define('ROLE_ADMIN', 'admin');

// ===== USER STATUS =====
define('STATUS_ACTIVE', 'active');
define('STATUS_INACTIVE', 'inactive');

// ===== DOCTOR APPROVAL STATUS =====
// Doctor approval statuses
define('DOCTOR_PENDING', 'pending');
define('DOCTOR_APPROVED', 'approved');
define('DOCTOR_REJECTED', 'rejected');


// ===== APPOINTMENT STATUS =====
define('APPOINTMENT_PENDING', 'pending');
define('APPOINTMENT_APPROVED', 'approved');
define('APPOINTMENT_REJECTED', 'rejected');
define('APPOINTMENT_COMPLETED', 'completed');

// ===== AVAILABILITY STATUS =====
define('AVAILABLE', 'available');
define('UNAVAILABLE', 'unavailable');

// ===== DAYS OF WEEK =====
$DAYS_OF_WEEK = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// ===== APP SETTINGS =====
define('APP_NAME', 'Marv Hospital Appointment System');
define('BASE_URL', ''); // change on hosting if needed
