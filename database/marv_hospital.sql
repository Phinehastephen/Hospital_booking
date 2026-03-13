CREATE DATABASE marv_hospital;
USE marv_hospital;


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','patient','doctor') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE patients (
    patient_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);


CREATE TABLE doctors (
    doctor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);


CREATE TABLE doctor_availability (
    availability_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    day_of_week ENUM('Mon','Tue','Wed','Thu','Fri','Sat'),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('available','unavailable') DEFAULT 'available',

    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
        ON DELETE CASCADE
);


CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('pending','approved','rejected','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
        ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
        ON DELETE CASCADE
);


INSERT INTO users (email, password, role)
VALUES (
  'admin@hospital.com',
  '$2y$10$Mb4L/04KuRSe8mN8/b/T8eW7MSalIU/OGMwYkka8VXlK64dcm38yu',
  'admin'
);   "admin123" password

CREATE TABLE symptom_specialty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    weight INT DEFAULT 1
);


INSERT INTO symptom_specialty (keyword, specialty, weight) VALUES
('chest pain', 'Cardiology', 3),
('heart', 'Cardiology', 2),
('palpitations', 'Cardiology', 3),
('mouth', 'Dentist', 2),
('tooth', 'Dentist', 2),
('shortness of breath', 'Pulmonology', 2),
('cough', 'Pulmonology', 1),
('headache', 'Neurology', 2),
('migraine', 'Neurology', 3),
('skin rash', 'Dermatology', 2),
('acne', 'Dermatology', 1),
('pregnancy', 'Gynecology', 3),
('fracture', 'Orthopedics', 3),
('joint pain', 'Orthopedics', 2),
('stomach pain', 'Gastroenterology', 2),
('ulcer', 'Gastroenterology', 3);

CREATE TABLE bookings (
id INT AUTO_INCREMENT PRIMARY KEY,
service VARCHAR(100),
date DATE,
time VARCHAR(20),
name VARCHAR(100),
phone VARCHAR(20),
payment_method VARCHAR(50),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id)
);