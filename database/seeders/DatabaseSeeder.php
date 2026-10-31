<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\MedicalQueue;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        // 1. Users & Roles
        $admin = User::updateOrCreate(
            ['email' => 'admin@bu.edu.bd'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'university_id' => 'ADMIN-001',
                'phone' => '0188888888',
                'department' => 'Administration',
                'designation' => 'System Director',
            ]
        );

        $doctor1 = User::updateOrCreate(
            ['email' => 'doctor@bu.edu.bd'],
            [
                'name' => 'Dr. Anisur Rahman',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'university_id' => 'DOC-010',
                'phone' => '01711002233',
                'department' => 'General Medicine',
                'designation' => 'Senior Medical Officer (MBBS, FCPS)',
            ]
        );

        $doctor2 = User::updateOrCreate(
            ['email' => 'doctor2@bu.edu.bd'],
            [
                'name' => 'Dr. Nusrat Sultana',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'university_id' => 'DOC-011',
                'phone' => '01711002244',
                'department' => 'Gynaecology',
                'designation' => 'Consultant Physician (MBBS, DGO)',
            ]
        );

        $doctor3 = User::updateOrCreate(
            ['email' => 'doctor3@bu.edu.bd'],
            [
                'name' => 'Dr. Abdur Karim',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'university_id' => 'DOC-012',
                'phone' => '01711002255',
                'department' => 'Dental',
                'designation' => 'Dental Surgeon (BDS, PGT)',
            ]
        );

        $pharmacist = User::updateOrCreate(
            ['email' => 'pharma@bu.edu.bd'],
            [
                'name' => 'Md. Rafiqul Islam',
                'password' => Hash::make('password'),
                'role' => 'pharmacist',
                'university_id' => 'PHARM-005',
                'phone' => '01822334455',
                'department' => 'Central Pharmacy',
                'designation' => 'Head Pharmacist (B.Pharm, M.Pharm)',
            ]
        );

        $patientUser = User::updateOrCreate(
            ['email' => 'patient@bu.edu.bd'],
            [
                'name' => 'Rahman Al-Hasan',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'university_id' => '2022-CSE-011',
                'phone' => '01911223344',
                'department' => 'Computer Science & Engineering',
                'designation' => 'Undergraduate Student (Year 3)',
            ]
        );

        // 2. Patients Profiles
        $p1 = Patient::updateOrCreate(
            ['university_id' => '2022-CSE-011'],
            [
                'user_id' => $patientUser->id,
                'first_name' => 'Rahman',
                'last_name' => 'Al-Hasan',
                'dob' => '2002-05-14',
                'gender' => 'Male',
                'blood_group' => 'B+',
                'nationality' => 'Bangladeshi',
                'role_category' => 'student',
                'department' => 'Computer Science & Engineering',
                'year_semester' => 'Year 3, Semester 5',
                'email' => 'patient@bu.edu.bd',
                'phone' => '01911223344',
                'emergency_name' => 'Md. Hasan Ali (Father)',
                'emergency_phone' => '01711223344',
                'address' => 'Room 312, Fazlul Huq Hall, University Campus',
                'allergies' => 'Penicillin, Shellfish',
                'conditions' => ['Asthma / Respiratory', 'Allergic Rhinitis'],
                'medications' => 'Salbutamol Inhaler as needed',
                'is_verified' => true,
            ]
        );

        $p2 = Patient::updateOrCreate(
            ['university_id' => '2023-BBA-044'],
            [
                'first_name' => 'Nadia',
                'last_name' => 'Akter',
                'dob' => '2003-08-22',
                'gender' => 'Female',
                'blood_group' => 'O+',
                'nationality' => 'Bangladeshi',
                'role_category' => 'student',
                'department' => 'Business Administration',
                'year_semester' => 'Year 2, Semester 3',
                'email' => 'nadia.akter@bu.edu.bd',
                'phone' => '01811998877',
                'emergency_name' => 'Farhana Akter (Mother)',
                'emergency_phone' => '01811998800',
                'address' => 'Begum Rokeya Hall, Campus',
                'allergies' => 'None',
                'conditions' => ['Anxiety / Depression'],
                'medications' => 'None',
                'is_verified' => true,
            ]
        );

        $p3 = Patient::updateOrCreate(
            ['university_id' => '2021-CSE-078'],
            [
                'first_name' => 'Tariq',
                'last_name' => 'Hossain',
                'dob' => '2001-11-09',
                'gender' => 'Male',
                'blood_group' => 'A+',
                'nationality' => 'Bangladeshi',
                'role_category' => 'student',
                'department' => 'Computer Science & Engineering',
                'year_semester' => 'Year 4, Semester 8',
                'email' => 'tariq.hossain@bu.edu.bd',
                'phone' => '01722883344',
                'emergency_name' => 'Abul Hossain (Uncle)',
                'emergency_phone' => '01722883300',
                'address' => 'Shahidullah Hall, Campus',
                'allergies' => 'Sulfa drugs',
                'conditions' => [],
                'medications' => 'None',
                'is_verified' => true,
            ]
        );

        $p4 = Patient::updateOrCreate(
            ['university_id' => 'FAC-ENG-009'],
            [
                'first_name' => 'Sumaiya',
                'last_name' => 'Begum',
                'dob' => '1988-04-12',
                'gender' => 'Female',
                'blood_group' => 'AB+',
                'nationality' => 'Bangladeshi',
                'role_category' => 'faculty',
                'department' => 'English Language & Literature',
                'year_semester' => 'Associate Professor',
                'email' => 'sumaiya.b@bu.edu.bd',
                'phone' => '01633554433',
                'emergency_name' => 'Dr. Kamrul Hasan (Spouse)',
                'emergency_phone' => '01633554400',
                'address' => 'Faculty Quarter Building B-4, Campus',
                'allergies' => 'Aspirin',
                'conditions' => ['Hypertension / High BP'],
                'medications' => 'Amlodipine 5mg daily',
                'is_verified' => true,
            ]
        );

        // 3. Medicines Inventory
        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'generic_name' => 'Acetaminophen',
                'category' => 'Analgesic / Antipyretic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'batch_number' => 'BCH-2026-081',
                'stock_quantity' => 240,
                'reorder_level' => 50,
                'unit' => 'tablets',
                'expiry_date' => $today->copy()->addMonths(18),
                'manufacturer' => 'Square Pharmaceuticals Ltd.',
                'instructions_default' => 'Take 1 tablet after meals for fever or pain.',
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin Trihydrate',
                'category' => 'Antibiotic',
                'dosage_form' => 'Capsule',
                'strength' => '500mg',
                'batch_number' => 'BCH-2026-112',
                'stock_quantity' => 95,
                'reorder_level' => 30,
                'unit' => 'capsules',
                'expiry_date' => $today->copy()->addMonths(12),
                'manufacturer' => 'Beximco Pharma',
                'instructions_default' => '1 capsule every 8 hours after food. Complete the full course.',
            ],
            [
                'name' => 'Ciprofloxacin 500mg',
                'generic_name' => 'Ciprofloxacin',
                'category' => 'Antibiotic (Fluoroquinolone)',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'batch_number' => 'BCH-2025-045',
                'stock_quantity' => 45,
                'reorder_level' => 25,
                'unit' => 'tablets',
                'expiry_date' => $today->copy()->addDays(28), // NEAR EXPIRY (< 30 days)
                'manufacturer' => 'Incepta Pharmaceuticals',
                'instructions_default' => '1 tablet twice daily for 5-7 days. Drink plenty of water.',
            ],
            [
                'name' => 'Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'category' => 'Antacid / PPI',
                'dosage_form' => 'Capsule',
                'strength' => '20mg',
                'batch_number' => 'BCH-2026-204',
                'stock_quantity' => 180,
                'reorder_level' => 40,
                'unit' => 'capsules',
                'expiry_date' => $today->copy()->addMonths(20),
                'manufacturer' => 'Square Pharmaceuticals Ltd.',
                'instructions_default' => '1 capsule 30 minutes before breakfast.',
            ],
            [
                'name' => 'Antacid Suspension 200ml',
                'generic_name' => 'Magaldrate + Simethicone',
                'category' => 'Antacid',
                'dosage_form' => 'Syrup',
                'strength' => '400mg + 20mg / 5ml',
                'batch_number' => 'BCH-2026-099',
                'stock_quantity' => 8, // LOW STOCK (<= 20)
                'reorder_level' => 20,
                'unit' => 'bottles',
                'expiry_date' => $today->copy()->addMonths(8),
                'manufacturer' => 'Renata Limited',
                'instructions_default' => '2 teaspoonfuls 1 hour after meals and at bedtime.',
            ],
            [
                'name' => 'Cetirizine 10mg',
                'generic_name' => 'Cetirizine Hydrochloride',
                'category' => 'Antihistamine / Allergy',
                'dosage_form' => 'Tablet',
                'strength' => '10mg',
                'batch_number' => 'BCH-2026-150',
                'stock_quantity' => 140,
                'reorder_level' => 30,
                'unit' => 'tablets',
                'expiry_date' => $today->copy()->addMonths(14),
                'manufacturer' => 'Eskayef Pharmaceuticals',
                'instructions_default' => '1 tablet at bedtime for allergy, runny nose, or itching.',
            ],
            [
                'name' => 'Salbutamol Inhaler 100mcg',
                'generic_name' => 'Salbutamol',
                'category' => 'Bronchodilator (Asthma)',
                'dosage_form' => 'Inhaler',
                'strength' => '100mcg/puff',
                'batch_number' => 'BCH-2025-077',
                'stock_quantity' => 6, // LOW STOCK & NEAR EXPIRY (< 45 days)
                'reorder_level' => 15,
                'unit' => 'inhalers',
                'expiry_date' => $today->copy()->addDays(42),
                'manufacturer' => 'Beximco Pharma',
                'instructions_default' => '1-2 puffs as needed for shortness of breath or wheezing.',
            ],
            [
                'name' => 'Metformin 500mg',
                'generic_name' => 'Metformin Hydrochloride',
                'category' => 'Antidiabetic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'batch_number' => 'BCH-2026-310',
                'stock_quantity' => 160,
                'reorder_level' => 35,
                'unit' => 'tablets',
                'expiry_date' => $today->copy()->addMonths(16),
                'manufacturer' => 'Square Pharmaceuticals Ltd.',
                'instructions_default' => '1 tablet with or after meals.',
            ],
            [
                'name' => 'Azithromycin 500mg',
                'generic_name' => 'Azithromycin',
                'category' => 'Macrolide Antibiotic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'batch_number' => 'BCH-2026-402',
                'stock_quantity' => 70,
                'reorder_level' => 25,
                'unit' => 'tablets',
                'expiry_date' => $today->copy()->addMonths(15),
                'manufacturer' => 'Square Pharmaceuticals Ltd.',
                'instructions_default' => '1 tablet daily for 3 days 1 hour before or 2 hours after meals.',
            ],
            [
                'name' => 'Oral Rehydration Salts (ORS)',
                'generic_name' => 'Sodium Chloride + Potassium Chloride + Glucose',
                'category' => 'Electrolyte Replacement',
                'dosage_form' => 'Sachet',
                'strength' => '20.5g/sachet',
                'batch_number' => 'BCH-2026-550',
                'stock_quantity' => 320,
                'reorder_level' => 50,
                'unit' => 'sachets',
                'expiry_date' => $today->copy()->addMonths(24),
                'manufacturer' => 'SMC Enterprise',
                'instructions_default' => 'Dissolve 1 sachet in 500ml drinking water. Drink after every loose stool.',
            ],
        ];

        $medicineModels = [];
        foreach ($medicines as $med) {
            $medicineModels[$med['name']] = Medicine::updateOrCreate(
                ['batch_number' => $med['batch_number']],
                $med
            );
        }

        // 4. Appointments
        $apt1 = Appointment::updateOrCreate(
            ['appointment_number' => 'APT-2026-0041'],
            [
                'patient_id' => $p1->id,
                'doctor_id' => $doctor1->id,
                'department' => 'General Medicine',
                'appointment_date' => $today,
                'time_slot' => '09:00',
                'reason' => 'Persistent cough, sore throat, and low-grade fever for 3 days.',
                'status' => 'confirmed',
                'admin_notes' => 'Token generated for walk-in consultation.',
            ]
        );

        $apt2 = Appointment::updateOrCreate(
            ['appointment_number' => 'APT-2026-0042'],
            [
                'patient_id' => $p2->id,
                'doctor_id' => $doctor1->id,
                'department' => 'General Medicine',
                'appointment_date' => $today,
                'time_slot' => '09:30',
                'reason' => 'Severe headache and dizziness following exam stress.',
                'status' => 'confirmed',
            ]
        );

        $apt3 = Appointment::updateOrCreate(
            ['appointment_number' => 'APT-2026-0043'],
            [
                'patient_id' => $p3->id,
                'doctor_id' => $doctor3->id,
                'department' => 'Dental',
                'appointment_date' => $today,
                'time_slot' => '10:00',
                'reason' => 'Molar toothache and gum bleeding.',
                'status' => 'confirmed',
            ]
        );

        $apt4 = Appointment::updateOrCreate(
            ['appointment_number' => 'APT-2026-0044'],
            [
                'patient_id' => $p4->id,
                'doctor_id' => $doctor2->id,
                'department' => 'Gynaecology',
                'appointment_date' => $today->copy()->addDays(1),
                'time_slot' => '11:00',
                'reason' => 'Routine follow-up and blood pressure check.',
                'status' => 'confirmed',
            ]
        );

        // 5. Medical Queues for today
        MedicalQueue::updateOrCreate(
            ['token_number' => 'T-101', 'queue_date' => $today],
            [
                'patient_id' => $p1->id,
                'doctor_id' => $doctor1->id,
                'appointment_id' => $apt1->id,
                'department' => 'General Medicine',
                'status' => 'waiting',
            ]
        );

        MedicalQueue::updateOrCreate(
            ['token_number' => 'T-102', 'queue_date' => $today],
            [
                'patient_id' => $p2->id,
                'doctor_id' => $doctor1->id,
                'appointment_id' => $apt2->id,
                'department' => 'General Medicine',
                'status' => 'in_consultation',
                'called_at' => Carbon::now()->subMinutes(10),
            ]
        );

        MedicalQueue::updateOrCreate(
            ['token_number' => 'T-103', 'queue_date' => $today],
            [
                'patient_id' => $p3->id,
                'doctor_id' => $doctor3->id,
                'appointment_id' => $apt3->id,
                'department' => 'Dental',
                'status' => 'waiting',
            ]
        );

        // 6. Prescriptions & Prescription Items
        $rx1 = Prescription::updateOrCreate(
            ['prescription_number' => 'RX-2026-0041'],
            [
                'patient_id' => $p1->id,
                'doctor_id' => $doctor1->id,
                'appointment_id' => $apt1->id,
                'diagnosis' => 'Acute Upper Respiratory Tract Infection (Pharyngitis)',
                'bp' => '120/80 mmHg',
                'pulse' => '76 bpm',
                'temperature' => '100.2 °F',
                'weight' => '68 kg',
                'clinical_advice' => "Drink warm fluids, saline gargle 3 times daily.\nAvoid cold drinks and dusty environments.\nRest for 3 days. Return if fever persists beyond 72 hours.",
                'status' => 'active', // Ready for dispensing
            ]
        );

        PrescriptionItem::updateOrCreate(
            [
                'prescription_id' => $rx1->id,
                'medicine_name' => 'Paracetamol 500mg',
            ],
            [
                'medicine_id' => $medicineModels['Paracetamol 500mg']->id,
                'dosage' => '1+0+1',
                'duration' => '5 days',
                'instructions' => 'After meal',
                'quantity' => 10,
                'is_dispensed' => false,
            ]
        );

        PrescriptionItem::updateOrCreate(
            [
                'prescription_id' => $rx1->id,
                'medicine_name' => 'Cetirizine 10mg',
            ],
            [
                'medicine_id' => $medicineModels['Cetirizine 10mg']->id,
                'dosage' => '0+0+1',
                'duration' => '5 days',
                'instructions' => 'At bedtime',
                'quantity' => 5,
                'is_dispensed' => false,
            ]
        );

        PrescriptionItem::updateOrCreate(
            [
                'prescription_id' => $rx1->id,
                'medicine_name' => 'Omeprazole 20mg',
            ],
            [
                'medicine_id' => $medicineModels['Omeprazole 20mg']->id,
                'dosage' => '1+0+0',
                'duration' => '7 days',
                'instructions' => 'Before breakfast',
                'quantity' => 7,
                'is_dispensed' => false,
            ]
        );

        // A second prescription (already dispensed in the past for history)
        $rx2 = Prescription::updateOrCreate(
            ['prescription_number' => 'RX-2026-0035'],
            [
                'patient_id' => $p1->id,
                'doctor_id' => $doctor1->id,
                'diagnosis' => 'Mild Bronchial Spasm / Asthmatic Cough',
                'bp' => '118/78 mmHg',
                'pulse' => '80 bpm',
                'temperature' => '98.6 °F',
                'weight' => '67 kg',
                'clinical_advice' => 'Keep inhaler with you at all times during cold mornings.',
                'status' => 'dispensed',
                'dispensed_at' => Carbon::now()->subDays(15),
                'dispensed_by' => $pharmacist->id,
            ]
        );

        PrescriptionItem::updateOrCreate(
            [
                'prescription_id' => $rx2->id,
                'medicine_name' => 'Salbutamol Inhaler 100mcg',
            ],
            [
                'medicine_id' => $medicineModels['Salbutamol Inhaler 100mcg']->id,
                'dosage' => '2 puffs',
                'duration' => 'As needed',
                'instructions' => 'Inhale through spacer when feeling wheezy',
                'quantity' => 1,
                'is_dispensed' => true,
            ]
        );
    }
}
