@extends('layouts.app')
@section('title', 'Medicine Inventory Management')

@push('styles')
<style>
    .inv-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .filter-bar {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="page-title">Medicine Inventory Control &amp; Stock Monitoring</div>
<div class="page-subtitle">Central Pharmacy stock records, batch numbers, manufacturer details, expiry tracking, and restock controls.</div>

{{-- Top Controls --}}
<div class="inv-top-bar">
    <form action="{{ route('pharmacy.inventory') }}" method="GET" class="filter-bar">
        <input type="text" name="search" class="form-control" style="width:220px;" placeholder="Search name, generic, batch..." value="{{ request('search') }}">
        <select name="category" class="form-control" style="width:160px;">
            <option value="">&mdash; All Categories &mdash;</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <select name="filter" class="form-control" style="width:150px;">
            <option value="">&mdash; All Stock Status &mdash;</option>
            <option value="low_stock" {{ request('filter') === 'low_stock' ? 'selected' : '' }}>Low Stock Only</option>
            <option value="near_expiry" {{ request('filter') === 'near_expiry' ? 'selected' : '' }}>Near Expiry (&lt; 60d)</option>
        </select>
        <button type="submit" class="btn btn-green btn-sm">Filter</button>
        @if(request()->hasAny(['search', 'category', 'filter']))
            <a href="{{ route('pharmacy.inventory') }}" class="btn btn-outline btn-sm">Clear</a>
        @endif
    </form>

    <button type="button" onclick="document.getElementById('newMedicineCard').scrollIntoView({behavior:'smooth'})" class="btn btn-gold btn-sm">
        + Add New Medicine Batch
    </button>
</div>

{{-- Inventory Table --}}
<div class="card" style="margin-bottom:1.8rem;">
    <div class="card-header">
        <span>Pharmacy Stock Catalog ({{ $medicines->total() }})</span>
        <span style="font-size:11px;font-weight:400;text-transform:none;">Showing page {{ $medicines->currentPage() }} of {{ $medicines->lastPage() }}</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Generic &amp; Category</th>
                    <th>Form &amp; Strength</th>
                    <th>Batch #</th>
                    <th>Expiry Date</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                    <th>Quick Stock Adjust</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $med)
                    <tr>
                        <td>
                            <strong style="color:var(--text);font-size:13px;">{{ $med->name }}</strong>
                            <div style="font-size:11px;color:var(--muted);">{{ $med->manufacturer ?? 'Pharmaceutical Manufacturer' }}</div>
                        </td>
                        <td>
                            <div>{{ $med->generic_name }}</div>
                            <span class="badge badge-gray" style="font-size:9px;">{{ $med->category }}</span>
                        </td>
                        <td>{{ $med->dosage_form }} ({{ $med->strength }})</td>
                        <td><code>{{ $med->batch_number }}</code></td>
                        <td>
                            {{ $med->expiry_date->format('d M Y') }}
                            @if($med->isExpired())
                                <br><span class="badge badge-red" style="font-size:9px;">EXPIRED</span>
                            @elseif($med->isNearExpiry(60))
                                <br><span class="badge badge-yellow" style="font-size:9px;">Near Expiry</span>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size:14px;color:{{ $med->isLowStock() ? '#c0392b' : 'var(--green-dk)' }};">
                                {{ $med->stock_quantity }}
                            </strong>
                            <span style="font-size:11px;color:var(--muted);">{{ $med->unit }}</span>
                            <div style="font-size:10px;color:var(--muted);">Min: {{ $med->reorder_level }}</div>
                        </td>
                        <td>
                            @if($med->isExpired())
                                <span class="badge badge-red">Expired</span>
                            @elseif($med->isLowStock())
                                <span class="badge badge-red">Low Stock</span>
                            @elseif($med->isNearExpiry(60))
                                <span class="badge badge-yellow">Expiring Soon</span>
                            @else
                                <span class="badge badge-green">In Stock</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('pharmacy.inventory.stock', $med->id) }}" method="POST" style="display:flex;gap:4px;align-items:center;">
                                @csrf
                                <input type="hidden" name="action" value="add">
                                <input type="number" name="adjustment" value="20" min="1" style="width:50px;padding:2px 4px;font-size:11px;border:1px solid #ccc;border-radius:3px;">
                                <button type="submit" class="btn btn-outline btn-sm" style="padding:2px 6px;font-size:11px;" title="Add stock quantity">+ Add</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:1.6rem;color:var(--muted);">No medicines matched your search criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($medicines->hasPages())
    <div style="margin-bottom:1.5rem;">
        {{ $medicines->links() }}
    </div>
@endif

{{-- Add New Medicine Batch Form --}}
<div class="card" id="newMedicineCard">
    <div class="card-header">+ Register New Medicine / Stock Batch</div>
    <div class="card-body">
        <form action="{{ route('pharmacy.inventory.store') }}" method="POST">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
                <div>
                    <label class="form-label">Brand / Trade Name <span class="req">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Paracetamol 500mg" required>
                </div>
                <div>
                    <label class="form-label">Generic Name <span class="req">*</span></label>
                    <input type="text" name="generic_name" class="form-control" placeholder="e.g. Acetaminophen" required>
                </div>
                <div>
                    <label class="form-label">Category <span class="req">*</span></label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Analgesic, Antibiotic, Antacid" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:.8rem;margin-bottom:.8rem;">
                <div>
                    <label class="form-label">Dosage Form <span class="req">*</span></label>
                    <select name="dosage_form" class="form-control" required>
                        <option value="Tablet">Tablet</option>
                        <option value="Capsule">Capsule</option>
                        <option value="Syrup">Syrup</option>
                        <option value="Inhaler">Inhaler</option>
                        <option value="Injection">Injection</option>
                        <option value="Ointment">Ointment</option>
                        <option value="Eye Drops">Eye Drops</option>
                        <option value="Sachet">Sachet</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Strength <span class="req">*</span></label>
                    <input type="text" name="strength" class="form-control" placeholder="e.g. 500mg, 10mg" required>
                </div>
                <div>
                    <label class="form-label">Batch Number <span class="req">*</span></label>
                    <input type="text" name="batch_number" class="form-control" placeholder="e.g. BCH-2026-99" required>
                </div>
                <div>
                    <label class="form-label">Expiry Date <span class="req">*</span></label>
                    <input type="date" name="expiry_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:.8rem;margin-bottom:1rem;">
                <div>
                    <label class="form-label">Initial Stock Quantity <span class="req">*</span></label>
                    <input type="number" name="stock_quantity" class="form-control" min="0" value="100" required>
                </div>
                <div>
                    <label class="form-label">Reorder Alert Level <span class="req">*</span></label>
                    <input type="number" name="reorder_level" class="form-control" min="0" value="25" required>
                </div>
                <div>
                    <label class="form-label">Unit of Measure <span class="req">*</span></label>
                    <input type="text" name="unit" class="form-control" value="tablets" placeholder="tablets, bottles" required>
                </div>
                <div>
                    <label class="form-label">Manufacturer</label>
                    <input type="text" name="manufacturer" class="form-control" placeholder="e.g. Square, Beximco">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-green">Save Medicine to Inventory</button>
            </div>
        </form>
    </div>
</div>
@endsection
