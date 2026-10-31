<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalQueue;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();

        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => User::where('role', 'doctor')->where('is_active', true)->count(),
            'total_pharmacists' => User::where('role', 'pharmacist')->where('is_active', true)->count(),
            'appointments_month' => Appointment::whereDate('appointment_date', '>=', $startOfMonth)->count(),
            'appointments_today' => Appointment::whereDate('appointment_date', $today)->count(),
            'queues_today' => MedicalQueue::whereDate('queue_date', $today)->count(),
            'prescriptions_total' => Prescription::count(),
            'prescriptions_dispensed' => Prescription::where('status', 'dispensed')->count(),
            'medicines_total' => Medicine::where('is_active', true)->count(),
            'low_stock_count' => Medicine::where('is_active', true)->whereColumn('stock_quantity', '<=', 'reorder_level')->count(),
            'near_expiry_count' => Medicine::where('is_active', true)->whereDate('expiry_date', '<=', $today->copy()->addDays(60))->count(),
        ];

        // Department breakdown
        $departmentStats = Appointment::selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        $recentAppointments = Appointment::with(['patient', 'doctor'])->latest()->take(6)->get();
        $recentPrescriptions = Prescription::with(['patient', 'doctor'])->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'departmentStats', 'recentAppointments', 'recentPrescriptions'));
    }

    public function users(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('university_id', 'like', "%{$s}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:200|unique:users,email',
            'role' => 'required|in:admin,doctor,pharmacist,patient',
            'password' => 'required|string|min:6',
            'university_id' => 'nullable|string|max:50|unique:users,university_id',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'university_id' => $validated['university_id'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users')->with('success', "User account for {$validated['name']} ({$validated['role']}) created successfully.");
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,doctor,pharmacist,patient',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        $user->update($validated);

        return back()->with('success', "User {$user->name} updated successfully.");
    }
}
