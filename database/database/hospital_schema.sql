-- ============================================================
-- Hospital Management System — Complete SQL Schema
-- Import: mysql -u root -p hospital_db < hospital_schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS hospital_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE hospital_db;

-- Users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Patients
CREATE TABLE patients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male','female','other') NOT NULL,
    blood_group VARCHAR(5) NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NULL,
    emergency_contact_name VARCHAR(100) NULL,
    emergency_contact_phone VARCHAR(20) NULL,
    emergency_contact_relation VARCHAR(50) NULL,
    medical_history TEXT NULL,
    allergies TEXT NULL,
    insurance_provider VARCHAR(100) NULL,
    insurance_number VARCHAR(100) NULL,
    status ENUM('active','inactive','discharged') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    INDEX idx_patient_id (patient_id),
    INDEX idx_phone (phone),
    INDEX idx_status (status)
);

-- Doctors
CREATE TABLE doctors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    qualification VARCHAR(200) NOT NULL,
    experience_years INT NOT NULL DEFAULT 0,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    license_number VARCHAR(50) UNIQUE NOT NULL,
    consultation_fee DECIMAL(10,2) DEFAULT 0,
    bio TEXT NULL,
    photo VARCHAR(255) NULL,
    status ENUM('active','inactive','on_leave') DEFAULT 'active',
    available_days JSON NULL,
    available_from TIME NULL,
    available_to TIME NULL,
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Appointments
CREATE TABLE appointments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_number VARCHAR(30) UNIQUE NOT NULL,
    patient_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration_minutes INT DEFAULT 30,
    type ENUM('consultation','follow_up','emergency','routine_checkup') DEFAULT 'consultation',
    status ENUM('scheduled','confirmed','in_progress','completed','cancelled','no_show') DEFAULT 'scheduled',
    reason TEXT NULL,
    notes TEXT NULL,
    diagnosis TEXT NULL,
    prescription TEXT NULL,
    fee DECIMAL(10,2) DEFAULT 0,
    payment_status ENUM('pending','paid','waived') DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    INDEX idx_date (appointment_date),
    INDEX idx_status (status)
);

-- Wards
CREATE TABLE wards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(100) NOT NULL,
    total_beds INT NOT NULL,
    description TEXT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Rooms
CREATE TABLE rooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) UNIQUE NOT NULL,
    ward_id BIGINT UNSIGNED NOT NULL,
    type ENUM('general','private','semi_private','icu','operation_theater') DEFAULT 'general',
    bed_count INT DEFAULT 1,
    rate_per_day DECIMAL(10,2) DEFAULT 0,
    status ENUM('available','occupied','maintenance') DEFAULT 'available',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (ward_id) REFERENCES wards(id) ON DELETE CASCADE
);

-- Admissions
CREATE TABLE admissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admission_number VARCHAR(30) UNIQUE NOT NULL,
    patient_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    room_id BIGINT UNSIGNED NOT NULL,
    admission_date DATETIME NOT NULL,
    discharge_date DATETIME NULL,
    admission_reason TEXT NOT NULL,
    diagnosis TEXT NULL,
    treatment TEXT NULL,
    discharge_notes TEXT NULL,
    status ENUM('admitted','discharged','transferred') DEFAULT 'admitted',
    total_charges DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- Bills
CREATE TABLE bills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bill_number VARCHAR(30) UNIQUE NOT NULL,
    patient_id BIGINT UNSIGNED NOT NULL,
    appointment_id BIGINT UNSIGNED NULL,
    admission_id BIGINT UNSIGNED NULL,
    bill_date DATE NOT NULL,
    due_date DATE NOT NULL,
    subtotal DECIMAL(10,2) DEFAULT 0,
    tax DECIMAL(10,2) DEFAULT 0,
    discount DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) DEFAULT 0,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    balance DECIMAL(10,2) DEFAULT 0,
    status ENUM('draft','sent','paid','partial','overdue','cancelled') DEFAULT 'draft',
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);

-- Bill Items
CREATE TABLE bill_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bill_id BIGINT UNSIGNED NOT NULL,
    description VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE
);

-- Payments
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_number VARCHAR(30) UNIQUE NOT NULL,
    bill_id BIGINT UNSIGNED NOT NULL,
    patient_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('cash','card','bank_transfer','insurance','online') DEFAULT 'cash',
    transaction_id VARCHAR(100) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Lab Tests
CREATE TABLE lab_tests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    test_number VARCHAR(30) UNIQUE NOT NULL,
    patient_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    test_name VARCHAR(100) NOT NULL,
    test_type VARCHAR(100) NOT NULL,
    ordered_date DATE NOT NULL,
    result_date DATE NULL,
    results TEXT NULL,
    result_file VARCHAR(255) NULL,
    status ENUM('ordered','sample_collected','processing','completed','cancelled') DEFAULT 'ordered',
    cost DECIMAL(10,2) DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- Medicines
CREATE TABLE medicines (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    generic_name VARCHAR(100) NULL,
    category VARCHAR(100) NOT NULL,
    manufacturer VARCHAR(100) NULL,
    unit VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    expiry_date DATE NULL,
    status ENUM('active','inactive','out_of_stock') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Prescriptions
CREATE TABLE prescriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    appointment_id BIGINT UNSIGNED NOT NULL,
    patient_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    medicine_id BIGINT UNSIGNED NOT NULL,
    dosage VARCHAR(100) NOT NULL,
    frequency VARCHAR(100) NOT NULL,
    duration_days INT NOT NULL,
    instructions TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id) ON DELETE CASCADE
);

-- Staff
CREATE TABLE staff (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    staff_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    join_date DATE NOT NULL,
    salary DECIMAL(10,2) DEFAULT 0,
    shift ENUM('morning','afternoon','night','rotating') DEFAULT 'morning',
    status ENUM('active','inactive','on_leave') DEFAULT 'active',
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Departments
CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    head_doctor_id BIGINT UNSIGNED NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (head_doctor_id) REFERENCES doctors(id) ON DELETE SET NULL
);

-- ============================================================
-- Sample Data
-- ============================================================

INSERT INTO users (name, email, password, created_at, updated_at) VALUES
('Hospital Admin', 'admin@hospital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

INSERT INTO doctors (doctor_id, first_name, last_name, specialization, qualification, experience_years, phone, email, license_number, consultation_fee, status, available_days, available_from, available_to, created_at, updated_at) VALUES
('DOC-00001', 'Rajesh', 'Sharma', 'Cardiology', 'MBBS, MD (Cardiology)', 15, '9876543201', 'rajesh.sharma@hospital.com', 'MCI-001', 800.00, 'active', '["Monday","Tuesday","Wednesday","Thursday","Friday"]', '09:00:00', '17:00:00', NOW(), NOW()),
('DOC-00002', 'Priya', 'Patel', 'Pediatrics', 'MBBS, MD (Pediatrics)', 10, '9876543202', 'priya.patel@hospital.com', 'MCI-002', 600.00, 'active', '["Monday","Tuesday","Thursday","Friday","Saturday"]', '10:00:00', '18:00:00', NOW(), NOW()),
('DOC-00003', 'Amit', 'Verma', 'Orthopedics', 'MBBS, MS (Orthopedics)', 12, '9876543203', 'amit.verma@hospital.com', 'MCI-003', 700.00, 'active', '["Tuesday","Wednesday","Thursday","Saturday"]', '08:00:00', '16:00:00', NOW(), NOW()),
('DOC-00004', 'Sunita', 'Gupta', 'Gynecology', 'MBBS, MD (Gynecology)', 18, '9876543204', 'sunita.gupta@hospital.com', 'MCI-004', 750.00, 'active', '["Monday","Wednesday","Friday"]', '09:00:00', '15:00:00', NOW(), NOW()),
('DOC-00005', 'Vikram', 'Singh', 'Neurology', 'MBBS, DM (Neurology)', 20, '9876543205', 'vikram.singh@hospital.com', 'MCI-005', 1000.00, 'active', '["Monday","Tuesday","Wednesday","Thursday"]', '11:00:00', '19:00:00', NOW(), NOW());

INSERT INTO patients (patient_id, first_name, last_name, date_of_birth, gender, blood_group, phone, email, address, city, state, postal_code, emergency_contact_name, emergency_contact_phone, emergency_contact_relation, medical_history, allergies, insurance_provider, insurance_number, status, created_at, updated_at) VALUES
('PAT-00001','Rahul','Kumar','1985-03-15','male','O+','9898989801','rahul.kumar@email.com','45 MG Road','Indore','Madhya Pradesh','452001','Seema Kumar','9898989802','Spouse','Hypertension (controlled)','Penicillin',NULL,NULL,'active',NOW(),NOW()),
('PAT-00002','Meera','Joshi','1992-07-22','female','A+','9898989803','meera.joshi@email.com','12 Vijay Nagar','Bhopal','Madhya Pradesh','462001','Anil Joshi','9898989804','Father','None','None','Star Health','SH-123456','active',NOW(),NOW()),
('PAT-00003','Suresh','Agrawal','1970-11-08','male','B+','9898989805','suresh.agrawal@email.com','88 Rajwada Area','Indore','Madhya Pradesh','452002','Kavita Agrawal','9898989806','Spouse','Diabetes Type 2, Hypertension','Sulfa drugs',NULL,NULL,'active',NOW(),NOW()),
('PAT-00004','Anjali','Tiwari','2000-05-14','female','AB+','9898989807','anjali.tiwari@email.com','34 Civil Lines','Jabalpur','Madhya Pradesh','482001','Ramesh Tiwari','9898989808','Father','Asthma','Dust, pollen',NULL,NULL,'active',NOW(),NOW()),
('PAT-00005','Mohan','Yadav','1955-09-30','male','O-','9898989809','mohan.yadav@email.com','7 Station Road','Ujjain','Madhya Pradesh','456001','Ram Yadav','9898989810','Son','Heart disease, Arthritis','NSAIDs','HDFC Ergo','HE-789012','active',NOW(),NOW());
