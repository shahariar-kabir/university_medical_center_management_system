<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();

        $stats = [
            'staff_count' => User::whereIn('role', ['doctor', 'pharmacist'])->where('is_active', true)->count(),
            'patient_count' => Patient::count(),
            'appointments_month' => Appointment::whereDate('appointment_date', '>=', $startOfMonth)->count(),
            'medicines_count' => Medicine::where('is_active', true)->count(),
        ];

        $doctors = User::where('role', 'doctor')->where('is_active', true)->get();

        return view('home', compact('stats', 'doctors'));
    }
}
