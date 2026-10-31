<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public & Landing
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login/demo/{role}', [AuthController::class, 'quickLogin'])->name('login.demo');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Patient Registration
Route::get('/patients/register', fn () => view('patients.register'))->name('patients.register');
Route::post('/patients/register', [AuthController::class, 'registerPatient'])->name('patients.register.post');

// Appointments (Public bridge)
Route::get('/appointments', function () {
    if (Auth::check()) {
        if (Auth::user()->isPatient()) {
            return redirect()->route('patient.appointments');
        } elseif (Auth::user()->isDoctor()) {
            return redirect()->route('doctor.queue');
        } elseif (Auth::user()->isPharmacist()) {
            return redirect()->route('pharmacy.dispense');
        } else {
            return redirect()->route('admin.dashboard');
        }
    }

    $doctors = User::where('role', 'doctor')->where('is_active', true)->get();
    $recentAppointments = Appointment::with(['doctor', 'patient'])->latest()->take(6)->get();

    return view('appointments.index', compact('doctors', 'recentAppointments'));
})->name('appointments');

Route::post('/appointments', function (Request $request) {
    if (Auth::check()) {
        return app(PatientController::class)->storeAppointment($request);
    }

    // Guest fallback validation & guidance
    $request->validate([
        'patient_name' => 'required|string|max:100',
        'university_id' => 'required|string|max:50',
        'department' => 'required|string',
        'appointment_date' => 'required|date|after_or_equal:today',
        'time_slot' => 'required|string',
    ]);

    return redirect()->route('login')->with('info', "Please log in with your university account ({$request->university_id}) to confirm and track your appointment.");
});

// Prescription PDF / View (Authorized patient or medical staff)
Route::middleware('auth')->group(function () {
    Route::get('/prescriptions/{prescription}', [PatientController::class, 'showPrescription'])->name('patient.prescriptions.show');
    Route::get('/prescriptions/{prescription}/pdf', [PatientController::class, 'downloadPrescriptionPdf'])->name('patient.prescriptions.pdf');
});

// Patient Module
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
    Route::post('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
    Route::get('/queue', [PatientController::class, 'queue'])->name('queue');
    Route::post('/queue/join', [PatientController::class, 'joinQueue'])->name('queue.join');
    Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/prescriptions', [PatientController::class, 'prescriptions'])->name('prescriptions');
});

// Doctor Module
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/queue', [DoctorController::class, 'queue'])->name('queue');
    Route::post('/queue/{queue}/call', [DoctorController::class, 'callPatient'])->name('queue.call');
    Route::post('/queue/{queue}/complete', [DoctorController::class, 'completeConsultation'])->name('queue.complete');
    Route::post('/queue/{queue}/skip', [DoctorController::class, 'skipPatient'])->name('queue.skip');
    Route::get('/patients/{patient}/history', [DoctorController::class, 'patientHistory'])->name('patients.history');
    Route::get('/prescriptions/create', [DoctorController::class, 'createPrescription'])->name('prescriptions.create');
    Route::post('/prescriptions', [DoctorController::class, 'storePrescription'])->name('prescriptions.store');
});

// Pharmacy & Inventory Module
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('/dashboard', [PharmacyController::class, 'dashboard'])->name('dashboard');
    Route::get('/inventory', [PharmacyController::class, 'inventory'])->name('inventory');
    Route::post('/inventory', [PharmacyController::class, 'storeMedicine'])->name('inventory.store');
    Route::post('/inventory/{medicine}/stock', [PharmacyController::class, 'updateStock'])->name('inventory.stock');
    Route::get('/dispense', [PharmacyController::class, 'dispenseList'])->name('dispense');
    Route::post('/dispense/{prescription}', [PharmacyController::class, 'dispense'])->name('dispense.store');
});

// Administrator Module
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
});
