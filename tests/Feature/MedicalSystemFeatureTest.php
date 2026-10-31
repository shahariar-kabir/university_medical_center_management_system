<?php

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('home page renders successfully with statistics', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('University Medical Centre');
    $response->assertSee('Registered Patients');
});

test('login page renders with quick demo role buttons', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Portal Login');
    $response->assertSee('Patient Portal');
    $response->assertSee('Doctor Consultation Desk');
});

test('quick login allows instant role-based authentication', function () {
    $response = $this->get(route('login.demo', 'patient'));

    $response->assertRedirect(route('patient.dashboard'));
    $this->assertAuthenticated();
});

test('patient can access dashboard, live queue, and view prescriptions', function () {
    $patientUser = User::where('role', 'patient')->first();
    $this->actingAs($patientUser);

    // Dashboard
    $this->get(route('patient.dashboard'))
        ->assertStatus(200)
        ->assertSee('Patient Portal');

    // Live Queue
    $this->get(route('patient.queue'))
        ->assertStatus(200)
        ->assertSee('Live Consultation Queue Tracking');

    // Prescriptions Portal
    $this->get(route('patient.prescriptions'))
        ->assertStatus(200)
        ->assertSee('Digital Prescription Portal');
});

test('patient can download digital prescription pdf', function () {
    $patientUser = User::where('role', 'patient')->first();
    $this->actingAs($patientUser);

    $prescription = Prescription::first();

    $response = $this->get(route('patient.prescriptions.pdf', $prescription->id));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('doctor can manage consultation queue and inspect patient history', function () {
    $doctorUser = User::where('role', 'doctor')->first();
    $this->actingAs($doctorUser);

    // Doctor queue
    $this->get(route('doctor.queue'))
        ->assertStatus(200)
        ->assertSee('Patient Consultation Queue Management');

    // Patient History
    $patient = Patient::first();
    $this->get(route('doctor.patients.history', $patient->id))
        ->assertStatus(200)
        ->assertSee($patient->full_name);
});

test('doctor can generate a digital prescription from stock', function () {
    $doctorUser = User::where('role', 'doctor')->first();
    $this->actingAs($doctorUser);

    $patient = Patient::first();
    $med = Medicine::first();

    $response = $this->post(route('doctor.prescriptions.store'), [
        'patient_id' => $patient->id,
        'diagnosis' => 'Acute Gastritis',
        'bp' => '120/80',
        'pulse' => '72',
        'temperature' => '98.4',
        'weight' => '65',
        'clinical_advice' => 'Avoid spicy food for 1 week.',
        'medicines' => [
            [
                'medicine_id' => $med->id,
                'medicine_name' => $med->name,
                'dosage' => '1+0+1',
                'duration' => '5 days',
                'instructions' => 'After meal',
                'quantity' => 10,
            ],
        ],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('prescriptions', [
        'patient_id' => $patient->id,
        'diagnosis' => 'Acute Gastritis',
    ]);
});

test('pharmacist can view inventory and dispense prescriptions', function () {
    $pharmacistUser = User::where('role', 'pharmacist')->first();
    $this->actingAs($pharmacistUser);

    // Inventory
    $this->get(route('pharmacy.inventory'))
        ->assertStatus(200)
        ->assertSee('Medicine Inventory Control');

    // Dispense Desk
    $this->get(route('pharmacy.dispense'))
        ->assertStatus(200)
        ->assertSee('Prescription Dispensing');

    // Dispense action
    $prescription = Prescription::where('status', 'active')->first();
    if ($prescription) {
        $this->post(route('pharmacy.dispense.store', $prescription->id))
            ->assertRedirect();

        $this->assertEquals('dispensed', $prescription->fresh()->status);
    }
});

test('admin can access operational analytics and user management', function () {
    $adminUser = User::where('role', 'admin')->first();
    $this->actingAs($adminUser);

    // Admin Dashboard
    $this->get(route('admin.dashboard'))
        ->assertStatus(200)
        ->assertSee('Executive Analytics Dashboard');

    // User Management
    $this->get(route('admin.users'))
        ->assertStatus(200)
        ->assertSee('User Management & Role Authorization');
});
