@extends('layouts.app')
@section('title', 'Pharmacy & Inventory Desk')

@push('styles')
<style>
    .pharma-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 860px) {
        .pharma-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-title">Central Pharmacy &amp; Inventory Desk</div>
<div class="page-subtitle">Monitor medicine inventory, track near-expiry batches, receive digital prescriptions, and fulfill patient dispensations.</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-val">{{ $totalMedicines }}</div>
        <div class="stat-label">Active Medicines</div>
        <div class="stat-sub">Formulations in catalog</div>
    </div>
    <div class="stat-card red">
        <div class="stat-val">{{ $lowStockMedicines->count() }}</div>
        <div class="stat-label">Low Stock Alerts</div>
        <div class="stat-sub">At or below reorder level</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-val">{{ $nearExpiryMedicines->count() }}</div>
        <div class="stat-label">Near-Expiry Batches</div>
        <div class="stat-sub">Expiring within 60 days</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-val">{{ $pendingPrescriptions->count() }}</div>
        <div class="stat-label">Pending Prescriptions</div>
        <div class="stat-sub">Ready for dispensing</div>
    </div>
</div>

<div style="display:flex;gap:.8rem;margin-bottom:1.4rem;flex-wrap:wrap;">
    <a href="{{ route('pharmacy.inventory') }}" class="btn btn-green">Full Medicine Inventory</a>
    <a href="{{ route('pharmacy.dispense') }}" class="btn btn-gold">Dispense Pending Prescriptions ({{ $pendingPrescriptions->count() }})</a>
</div>

{{-- Two Alert Tables --}}
<div class="pharma-grid">
    {{-- Near Expiry Alerts --}}
    <div class="card" style="border-top:3px solid var(--gold);">
        <div class="card-header" style="background:#856404;">
            <span>Near-Expiry Stock Alerts (&lt; 60 Days)</span>
            <span style="font-size:11px;">{{ $nearExpiryMedicines->count() }} Batches</span>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Batch</th>
                        <th>Stock</th>
                        <th>Expiry Date</th>
                        <th>Urgency</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nearExpiryMedicines as $med)
                        <tr>
                            <td>
                                <strong>{{ $med->name }}</strong>
                                <div style="font-size:11px;color:var(--muted);">{{ $med->dosage_form }} ({{ $med->strength }})</div>
                            </td>
                            <td><code>{{ $med->batch_number }}</code></td>
                            <td><strong>{{ $med->stock_quantity }}</strong> {{ $med->unit }}</td>
                            <td>{{ $med->expiry_date->format('d M Y') }}</td>
                            <td>
                                @if($med->isExpired())
                                    <span class="badge badge-red">EXPIRED</span>
                                @elseif($med->expiry_date->diffInDays(now()) <= 30)
                                    <span class="badge badge-red">{{ (int)$med->expiry_date->diffInDays(now()) }}d left</span>
                                @else
                                    <span class="badge badge-yellow">{{ (int)$med->expiry_date->diffInDays(now()) }}d left</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:1.4rem;color:var(--muted);">No near-expiry batches detected.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Low Stock Alerts --}}
    <div class="card" style="border-top:3px solid #c0392b;">
        <div class="card-header" style="background:#721c24;">
            <span>Low Stock Alerts (Stock &le; Reorder Level)</span>
            <span style="font-size:11px;">{{ $lowStockMedicines->count() }} Items</span>
        </div>
        <div class="card-body" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Current Stock</th>
                        <th>Reorder Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockMedicines as $med)
                        <tr>
                            <td>
                                <strong>{{ $med->name }}</strong>
                                <div style="font-size:11px;color:var(--muted);">{{ $med->category }}</div>
                            </td>
                            <td>
                                <strong style="color:#c0392b;font-size:14px;">{{ $med->stock_quantity }}</strong> {{ $med->unit }}
                            </td>
                            <td>{{ $med->reorder_level }} {{ $med->unit }}</td>
                            <td>
                                <form action="{{ route('pharmacy.inventory.stock', $med->id) }}" method="POST" style="display:flex;gap:4px;">
                                    @csrf
                                    <input type="hidden" name="action" value="add">
                                    <input type="number" name="adjustment" value="50" style="width:55px;padding:2px 4px;font-size:11px;border:1px solid #ccc;border-radius:3px;">
                                    <button type="submit" class="btn btn-outline btn-sm" style="padding:2px 6px;font-size:10px;">Restock</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:1.4rem;color:var(--muted);">All medicine stocks are above minimum threshold.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pending Prescriptions Queue --}}
<div class="card">
    <div class="card-header">
        <span>Prescriptions Ready for Dispensation ({{ $pendingPrescriptions->count() }})</span>
        <a href="{{ route('pharmacy.dispense') }}" style="color:#fff;font-size:11px;text-decoration:underline;">Full Dispensing Counter &rarr;</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Rx Number</th>
                    <th>Patient Name</th>
                    <th>Prescribing Doctor</th>
                    <th>Prescribed Items</th>
                    <th>Issued At</th>
                    <th>Fulfillment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingPrescriptions as $rx)
                    <tr>
                        <td><strong style="color:var(--green);">{{ $rx->prescription_number }}</strong></td>
                        <td>
                            <strong>{{ $rx->patient->full_name }}</strong><br>
                            <small style="color:var(--muted);">ID: {{ $rx->patient->university_id }}</small>
                        </td>
                        <td>{{ $rx->doctor->name }}</td>
                        <td>
                            <ul style="padding-left:1rem;margin:0;font-size:12px;">
                                @foreach($rx->items as $item)
                                    <li>{{ $item->medicine_name }} &times; <strong>{{ $item->quantity }}</strong> ({{ $item->dosage }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="font-size:11px;color:var(--muted);">{{ $rx->created_at->diffForHumans() }}</td>
                        <td>
                            <form action="{{ route('pharmacy.dispense.store', $rx->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-gold btn-sm">Dispense &amp; Deduct Stock</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:1.4rem;color:var(--muted);">All current prescriptions have been dispensed.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
