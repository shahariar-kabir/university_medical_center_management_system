<?php

use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn () => view('home'));

// Patient Registration
Route::get('/patients/register', fn () => view('patients.register'));
Route::post('/patients/register', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'first_name'    => 'required|string|max:100',
        'last_name'     => 'required|string|max:100',
        'dob'           => 'required|date',
        'gender'        => 'required|string',
        'university_id' => 'required|string|max:50',
        'role'          => 'required|string',
        'email'         => 'required|email|max:200',
        'phone'         => 'required|string|max:20',
    ]);

    return redirect('/patients/register')
        ->with('success', "Patient {$request->first_name} {$request->last_name} registered successfully!");
});

// Appointments
Route::get('/appointments', fn () => view('appointments.index'));
Route::post('/appointments', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'university_id' => 'required|string|max:50',
        'patient_name'  => 'required|string|max:200',
        'department'    => 'required|string',
        'date'          => 'required|date|after_or_equal:today',
        'time'          => 'required|string',
    ]);

    return redirect('/appointments')
        ->with('success', "Appointment booked for {$request->patient_name} on {$request->date} at {$request->time}!");
});
