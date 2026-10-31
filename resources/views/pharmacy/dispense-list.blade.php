@extends('layouts.app')
@section('title', 'Prescription Dispensing Desk')

@section('content')
<div class="page-title">Prescription Dispensing &amp; Fulfillment Counter</div>
<div class="page-subtitle">Verify doctor clinical orders, confirm stock availability, and dispense subsidised medicines to university members.</div>

<div class="card">
    <div class="card-header">
        <span>Prescriptions Awaiting Dispensation ({{ $prescriptions->total() }})</span>
        <a href="{{ route('pharmacy.inventory') }}" style="color:#fff;font-size:11px;text-decoration:underline;">View Medicine Stock &rarr;</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Prescription #</th>
                    <th>Patient Name &amp; ID</th>
                    <th>Prescribing Doctor</th>
                    <th>Items to Dispense</th>
                    <th>Stock Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $rx)
                    <tr>
                        <td>
                            <strong style="color:var(--green);font-size:14px;">{{ $rx->prescription_number }}</strong>
                            <div style="font-size:11px;color:var(--muted);">{{ $rx->created_at->format('d M Y, h:i A') }}</div>
                        </td>
                        <td>
                            <strong>{{ $rx->patient->full_name }}</strong><br>
                            <span style="font-size:11px;color:var(--muted);">{{ $rx->patient->university_id }} ({{ ucfirst($rx->patient->role_category) }})</span>
                            @if($rx->patient->allergies)
                                <div style="font-size:10px;color:#c0392b;font-weight:700;">Allergy: {{ $rx->patient->allergies }}</div>
                            @endif
                        </td>
                        <td>
                            {{ $rx->doctor->name }}<br>
                            <small style="color:var(--muted);">{{ $rx->doctor->department }}</small>
                        </td>
                        <td>
                            <table style="width:100%;font-size:12px;border-collapse:collapse;">
                                @foreach($rx->items as $item)
                                    <tr style="border-bottom:1px dashed #eee;">
                                        <td style="padding:3px 0;"><strong>{{ $item->medicine_name }}</strong></td>
                                        <td style="padding:3px 6px;color:var(--muted);">{{ $item->dosage }} &bull; {{ $item->duration }}</td>
                                        <td style="padding:3px 0;text-align:right;">Qty: <strong>{{ $item->quantity }}</strong></td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                        <td>
                            @php
                                $allInStock = true;
                                foreach($rx->items as $item) {
                                    if ($item->medicine && $item->medicine->stock_quantity < $item->quantity) {
                                        $allInStock = false;
                                        break;
                                    }
                                }
                            @endphp
                            @if($allInStock)
                                <span class="badge badge-green">In Stock</span>
                            @else
                                <span class="badge badge-red">Partial / Low Stock</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;flex-direction:column;gap:5px;">
                                <form action="{{ route('pharmacy.dispense.store', $rx->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-green btn-sm" style="width:100%;">
                                        Dispense &amp; Deduct Stock
                                    </button>
                                </form>
                                <a href="{{ route('patient.prescriptions.show', $rx->id) }}" class="btn btn-outline btn-sm" style="width:100%;text-align:center;">
                                    View Full Rx
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2rem;color:var(--muted);">
                            All pending prescriptions have been dispensed! No active orders currently waiting.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($prescriptions->hasPages())
    <div style="margin-top:1rem;">
        {{ $prescriptions->links() }}
    </div>
@endif
@endsection
