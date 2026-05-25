<?php

namespace Database\Seeders;

use App\Models\{Doctor, Patient, Appointment, Ward, Room, Bill, BillItem, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name'     => 'Hospital Admin',
            'email'    => 'admin@hospital.com',
            'password' => Hash::make('password'),
        ]);

        // Create Doctors
        $doctors = [
            ['first_name'=>'Rajesh','last_name'=>'Sharma','specialization'=>'Cardiology','qualification'=>'MBBS, MD (Cardiology)','experience_years'=>15,'phone'=>'9876543201','email'=>'rajesh.sharma@hospital.com','license_number'=>'MCI-001','consultation_fee'=>800,'status'=>'active','available_days'=>['Monday','Tuesday','Wednesday','Thursday','Friday'],'available_from'=>'09:00','available_to'=>'17:00'],
            ['first_name'=>'Priya','last_name'=>'Patel','specialization'=>'Pediatrics','qualification'=>'MBBS, MD (Pediatrics)','experience_years'=>10,'phone'=>'9876543202','email'=>'priya.patel@hospital.com','license_number'=>'MCI-002','consultation_fee'=>600,'status'=>'active','available_days'=>['Monday','Tuesday','Thursday','Friday','Saturday'],'available_from'=>'10:00','available_to'=>'18:00'],
            ['first_name'=>'Amit','last_name'=>'Verma','specialization'=>'Orthopedics','qualification'=>'MBBS, MS (Orthopedics)','experience_years'=>12,'phone'=>'9876543203','email'=>'amit.verma@hospital.com','license_number'=>'MCI-003','consultation_fee'=>700,'status'=>'active','available_days'=>['Tuesday','Wednesday','Thursday','Saturday'],'available_from'=>'08:00','available_to'=>'16:00'],
            ['first_name'=>'Sunita','last_name'=>'Gupta','specialization'=>'Gynecology','qualification'=>'MBBS, MD (Gynecology)','experience_years'=>18,'phone'=>'9876543204','email'=>'sunita.gupta@hospital.com','license_number'=>'MCI-004','consultation_fee'=>750,'status'=>'active','available_days'=>['Monday','Wednesday','Friday'],'available_from'=>'09:00','available_to'=>'15:00'],
            ['first_name'=>'Vikram','last_name'=>'Singh','specialization'=>'Neurology','qualification'=>'MBBS, DM (Neurology)','experience_years'=>20,'phone'=>'9876543205','email'=>'vikram.singh@hospital.com','license_number'=>'MCI-005','consultation_fee'=>1000,'status'=>'active','available_days'=>['Monday','Tuesday','Wednesday','Thursday'],'available_from'=>'11:00','available_to'=>'19:00'],
        ];

        foreach ($doctors as $d) {
            Doctor::create($d);
        }

        // Create Patients
        $patients = [
            ['first_name'=>'Rahul','last_name'=>'Kumar','date_of_birth'=>'1985-03-15','gender'=>'male','blood_group'=>'O+','phone'=>'9898989801','email'=>'rahul.kumar@email.com','address'=>'45 MG Road','city'=>'Indore','state'=>'Madhya Pradesh','postal_code'=>'452001','emergency_contact_name'=>'Seema Kumar','emergency_contact_phone'=>'9898989802','emergency_contact_relation'=>'Spouse','medical_history'=>'Hypertension (controlled)','allergies'=>'Penicillin','status'=>'active'],
            ['first_name'=>'Meera','last_name'=>'Joshi','date_of_birth'=>'1992-07-22','gender'=>'female','blood_group'=>'A+','phone'=>'9898989803','email'=>'meera.joshi@email.com','address'=>'12 Vijay Nagar','city'=>'Bhopal','state'=>'Madhya Pradesh','postal_code'=>'462001','emergency_contact_name'=>'Anil Joshi','emergency_contact_phone'=>'9898989804','emergency_contact_relation'=>'Father','medical_history'=>'None','allergies'=>'None','insurance_provider'=>'Star Health','insurance_number'=>'SH-123456','status'=>'active'],
            ['first_name'=>'Suresh','last_name'=>'Agrawal','date_of_birth'=>'1970-11-08','gender'=>'male','blood_group'=>'B+','phone'=>'9898989805','email'=>'suresh.agrawal@email.com','address'=>'88 Rajwada Area','city'=>'Indore','state'=>'Madhya Pradesh','postal_code'=>'452002','emergency_contact_name'=>'Kavita Agrawal','emergency_contact_phone'=>'9898989806','emergency_contact_relation'=>'Spouse','medical_history'=>'Diabetes Type 2, Hypertension','allergies'=>'Sulfa drugs','status'=>'active'],
            ['first_name'=>'Anjali','last_name'=>'Tiwari','date_of_birth'=>'2000-05-14','gender'=>'female','blood_group'=>'AB+','phone'=>'9898989807','email'=>'anjali.tiwari@email.com','address'=>'34 Civil Lines','city'=>'Jabalpur','state'=>'Madhya Pradesh','postal_code'=>'482001','emergency_contact_name'=>'Ramesh Tiwari','emergency_contact_phone'=>'9898989808','emergency_contact_relation'=>'Father','medical_history'=>'Asthma','allergies'=>'Dust, pollen','status'=>'active'],
            ['first_name'=>'Mohan','last_name'=>'Yadav','date_of_birth'=>'1955-09-30','gender'=>'male','blood_group'=>'O-','phone'=>'9898989809','email'=>'mohan.yadav@email.com','address'=>'7 Station Road','city'=>'Ujjain','state'=>'Madhya Pradesh','postal_code'=>'456001','emergency_contact_name'=>'Ram Yadav','emergency_contact_phone'=>'9898989810','emergency_contact_relation'=>'Son','medical_history'=>'Heart disease, Arthritis','allergies'=>'NSAIDs','insurance_provider'=>'HDFC Ergo','insurance_number'=>'HE-789012','status'=>'active'],
        ];

        foreach ($patients as $p) {
            Patient::create($p);
        }

        // Create Wards and Rooms
        $ward1 = Ward::create(['name'=>'General Ward A','type'=>'General','total_beds'=>20,'status'=>'active']);
        $ward2 = Ward::create(['name'=>'ICU','type'=>'Intensive Care','total_beds'=>10,'status'=>'active']);

        Room::create(['room_number'=>'101','ward_id'=>$ward1->id,'type'=>'general','bed_count'=>4,'rate_per_day'=>500,'status'=>'available']);
        Room::create(['room_number'=>'102','ward_id'=>$ward1->id,'type'=>'semi_private','bed_count'=>2,'rate_per_day'=>1200,'status'=>'occupied']);
        Room::create(['room_number'=>'103','ward_id'=>$ward1->id,'type'=>'private','bed_count'=>1,'rate_per_day'=>2500,'status'=>'available']);
        Room::create(['room_number'=>'ICU-01','ward_id'=>$ward2->id,'type'=>'icu','bed_count'=>1,'rate_per_day'=>5000,'status'=>'occupied']);

        // Create Sample Appointments
        $appointmentData = [
            ['patient_id'=>1,'doctor_id'=>1,'appointment_date'=>today(),'appointment_time'=>'09:00','type'=>'consultation','status'=>'scheduled','reason'=>'Chest pain and shortness of breath','fee'=>800,'payment_status'=>'pending'],
            ['patient_id'=>2,'doctor_id'=>2,'appointment_date'=>today(),'appointment_time'=>'10:00','type'=>'routine_checkup','status'=>'completed','reason'=>'Annual checkup','fee'=>600,'payment_status'=>'paid'],
            ['patient_id'=>3,'doctor_id'=>1,'appointment_date'=>today(),'appointment_time'=>'11:00','type'=>'follow_up','status'=>'confirmed','reason'=>'Follow-up for hypertension','fee'=>800,'payment_status'=>'pending'],
            ['patient_id'=>4,'doctor_id'=>4,'appointment_date'=>now()->addDay(),'appointment_time'=>'09:30','type'=>'consultation','status'=>'scheduled','reason'=>'Irregular periods','fee'=>750,'payment_status'=>'pending'],
            ['patient_id'=>5,'doctor_id'=>3,'appointment_date'=>now()->addDays(2),'appointment_time'=>'14:00','type'=>'consultation','status'=>'scheduled','reason'=>'Knee pain and stiffness','fee'=>700,'payment_status'=>'pending'],
        ];

        foreach ($appointmentData as $a) {
            Appointment::create($a);
        }

        // Create a sample bill
        $bill = Bill::create([
            'patient_id'  => 2,
            'appointment_id' => 2,
            'bill_date'   => today(),
            'due_date'    => today()->addDays(30),
            'subtotal'    => 1100,
            'tax'         => 0,
            'discount'    => 0,
            'total'       => 1100,
            'paid_amount' => 1100,
            'balance'     => 0,
            'status'      => 'paid',
        ]);

        BillItem::create(['bill_id'=>$bill->id,'description'=>'Consultation Fee','category'=>'Consultation','quantity'=>1,'unit_price'=>600,'total'=>600]);
        BillItem::create(['bill_id'=>$bill->id,'description'=>'Blood Test (CBC)','category'=>'Lab Test','quantity'=>1,'unit_price'=>300,'total'=>300]);
        BillItem::create(['bill_id'=>$bill->id,'description'=>'ECG','category'=>'Procedure','quantity'=>1,'unit_price'=>200,'total'=>200]);

        $this->command->info('✅ Hospital Management System seeded successfully!');
        $this->command->info('🔑 Login: admin@hospital.com / password');
    }
}
