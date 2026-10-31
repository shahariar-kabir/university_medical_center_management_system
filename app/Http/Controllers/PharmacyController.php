<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Prescription;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PharmacyController extends Controller
{
    public function dashboard(): View
    {
        $today = Carbon::today();
        $sixtyDaysAhead = $today->copy()->addDays(60);

        $totalMedicines = Medicine::where('is_active', true)->count();

        $lowStockMedicines = Medicine::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orderBy('stock_quantity')
            ->get();

        $nearExpiryMedicines = Medicine::where('is_active', true)
            ->whereDate('expiry_date', '<=', $sixtyDaysAhead)
            ->orderBy('expiry_date')
            ->get();

        $pendingPrescriptions = Prescription::with(['patient', 'doctor', 'items'])
            ->where('status', 'active')
            ->latest()
            ->get();

        $dispensedTodayCount = Prescription::where('status', 'dispensed')
            ->whereDate('dispensed_at', $today)
            ->count();

        return view('pharmacy.dashboard', compact(
            'totalMedicines',
            'lowStockMedicines',
            'nearExpiryMedicines',
            'pendingPrescriptions',
            'dispensedTodayCount'
        ));
    }

    public function inventory(Request $request): View
    {
        $query = Medicine::query();

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('generic_name', 'like', "%{$s}%")
                    ->orWhere('batch_number', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->input('filter') === 'low_stock') {
            $query->whereColumn('stock_quantity', '<=', 'reorder_level');
        } elseif ($request->input('filter') === 'near_expiry') {
            $query->whereDate('expiry_date', '<=', Carbon::today()->addDays(60));
        }

        $medicines = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Medicine::select('category')->distinct()->pluck('category');

        return view('pharmacy.inventory', compact('medicines', 'categories'));
    }

    public function storeMedicine(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'generic_name' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'dosage_form' => 'required|string|max:50',
            'strength' => 'required|string|max:50',
            'batch_number' => 'required|string|max:50|unique:medicines,batch_number',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:30',
            'expiry_date' => 'required|date',
            'manufacturer' => 'nullable|string|max:200',
            'instructions_default' => 'nullable|string|max:500',
        ]);

        Medicine::create($validated);

        return redirect()->route('pharmacy.inventory')->with('success', "Medicine {$validated['name']} (Batch {$validated['batch_number']}) added to inventory.");
    }

    public function updateStock(Request $request, Medicine $medicine): RedirectResponse
    {
        $validated = $request->validate([
            'adjustment' => 'required|integer',
            'action' => 'required|in:add,set',
        ]);

        if ($validated['action'] === 'add') {
            $medicine->increment('stock_quantity', $validated['adjustment']);
        } else {
            $medicine->update(['stock_quantity' => max(0, $validated['adjustment'])]);
        }

        return back()->with('success', "Stock for {$medicine->name} updated to {$medicine->stock_quantity} {$medicine->unit}.");
    }

    public function dispenseList(): View
    {
        $prescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])
            ->where('status', 'active')
            ->latest()
            ->paginate(15);

        return view('pharmacy.dispense-list', compact('prescriptions'));
    }

    public function dispense(Prescription $prescription): RedirectResponse
    {
        $pharmacist = Auth::user();

        // Deduct inventory for each item
        foreach ($prescription->items as $item) {
            if ($item->medicine_id) {
                $med = Medicine::find($item->medicine_id);
                if ($med) {
                    $med->decrement('stock_quantity', min($med->stock_quantity, $item->quantity));
                }
            }
            $item->update(['is_dispensed' => true]);
        }

        $prescription->update([
            'status' => 'dispensed',
            'dispensed_at' => Carbon::now(),
            'dispensed_by' => $pharmacist->id,
        ]);

        return back()->with('success', "Prescription {$prescription->prescription_number} dispensed successfully! Medicine stock adjusted.");
    }
}
