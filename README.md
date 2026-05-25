# 🏥 Hospital Management System

A full-featured Hospital Management System built with **Laravel 10** + **MySQL** + **Bootstrap 5**.

---

## 📋 Features

| Module | Features |
|--------|----------|
| **Dashboard** | Live stats, today's appointments, revenue charts, recent patients |
| **Patients** | Register, search, view full medical records, history |
| **Doctors** | Manage specialists, availability, consultation fees |
| **Appointments** | Book, edit, track status, clinical notes, diagnosis |
| **Billing** | Generate invoices, line items, tax/discount, print |
| **Payments** | Record partial/full payments, payment history |
| **Admissions** | Room booking, discharge management |
| **Lab Tests** | Order tests, track results |
| **Pharmacy** | Medicine inventory, prescriptions |
| **Staff** | Nurses, receptionists, technicians |
| **Wards & Rooms** | Bed management, occupancy tracking |

---

## 🗄️ Database Schema

```
patients          — Patient records with medical history
doctors           — Doctors with specializations & availability
appointments      — Scheduling, diagnosis, prescriptions
admissions        — In-patient management
wards             — Hospital ward definitions
rooms             — Room/bed inventory
bills             — Invoice headers
bill_items        — Invoice line items
payments          — Payment transactions
lab_tests         — Test orders & results
medicines         — Pharmacy inventory
prescriptions     — Medicine prescriptions
staff             — Non-doctor hospital staff
departments       — Hospital departments
users             — System users (auth)
```

---

## ⚙️ Installation

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL 8.0+
- Node.js (optional, for assets)

### Step 1: Clone / Extract
```bash
cd /var/www
# Extract the ZIP or clone
unzip hospital-management.zip
cd hospital-mgmt
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:
```
DB_DATABASE=hospital_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### Step 4: Create Database
```sql
CREATE DATABASE hospital_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5: Run Migrations & Seed
```bash
php artisan migrate
php artisan db:seed
```

### Step 6: Start Development Server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔑 Default Login

| Field | Value |
|-------|-------|
| Email | admin@hospital.com |
| Password | password |

---

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── PatientController.php
│   ├── DoctorController.php
│   ├── AppointmentController.php
│   └── BillingController.php
├── Models/
│   ├── Patient.php
│   ├── Doctor.php
│   ├── Appointment.php
│   ├── Admission.php
│   ├── Bill.php, BillItem.php, Payment.php
│   ├── LabTest.php, Medicine.php, Prescription.php
│   ├── Ward.php, Room.php
│   └── Staff.php
database/
├── migrations/          — 8 migration files
└── seeders/             — DatabaseSeeder with sample data
resources/views/
├── layouts/app.blade.php
├── dashboard/
├── patients/
├── doctors/
├── appointments/
└── billing/
routes/web.php
```

---

## 🎨 Tech Stack

- **Backend:** Laravel 10, PHP 8.1+
- **Database:** MySQL with Eloquent ORM + SoftDeletes
- **Frontend:** Bootstrap 5.3, Bootstrap Icons, Chart.js
- **Auth:** Laravel Breeze (included)

---

## 📊 Auto-Generated IDs

| Entity | Format | Example |
|--------|--------|---------|
| Patient | `PAT-XXXXX` | PAT-00001 |
| Doctor | `DOC-XXXXX` | DOC-00001 |
| Appointment | `APT-YYYYMMDD-XXXX` | APT-20240115-0001 |
| Bill | `BILL-YYYY-XXXXX` | BILL-2024-00001 |
| Payment | `PAY-YYYYMMDD-XXXX` | PAY-20240115-0001 |

---

## 🔧 Adding Laravel Auth (Breeze)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
```

---

## 📄 License

MIT License — free to use and modify.
