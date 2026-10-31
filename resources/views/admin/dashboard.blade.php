@extends('layouts.app')
@section('title', 'System Reporting & Analytics')

@push('styles')
<style>
    .admin-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.5rem;
        align-items: start;
    }
    @media (max-width: 860px) {
        .admin-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-title">University Medical Centre &mdash; Executive Analytics Dashboard</div>
<div class="page-subtitle">Centralized operational reporting, department clinical load, medicine inventory monitoring, and user authorization controls.</div>

{{-- Operational Key Metrics --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-val">{{ $stats['total_patients'] }}</div>
        <div class="stat-label">Registered Patients</div>
        <div class="stat-sub">Students, staff &amp; faculty</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-val">{{ $stats['total_doctors'] + $stats['total_pharmacists'] }}</div>
        <div class="stat-label">Active Staff</div>
        <div class="stat-sub">{{ $stats['total_doctors'] }} Doctors, {{ $stats['total_pharmacists'] }} Pharmacists</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-val">{{ $stats['appointments_month'] }}</div>
        <div class="stat-label">Appointments This Month</div>
        <div class="stat-sub">{{ $stats['appointments_today'] }} booked for today</div>
    </div>
    <div class="stat-card">
        <div class="stat-val">{{ $stats['prescriptions_total'] }}</div>
        <div class="stat-label">Prescriptions Generated</div>
        <div class="stat-sub">{{ $stats['prescriptions_dispensed'] }} fulfilled by pharmacy</div>
    </div>
    <div class="stat-card red">
        <div class="stat-val">{{ $stats['low_stock_count'] }}</div>
        <div class="stat-label">Low Stock Medicines</div>
        <div class="stat-sub">Below reorder threshold</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-val">{{ $stats['near_expiry_count'] }}</div>
        <div class="stat-label">Near-Expiry Batches</div>
        <div class="stat-sub">Expiring within 60 days</div>
    </div>
</div>

<div class="admin-grid">
    {{-- Left: Department Breakdown & Recent Activity --}}
    <div>
        {{-- Department Utilization --}}
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-header">Department Clinical Load &amp; Utilization</div>
            <div class="card-body" style="padding:0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Department / Specialty</th>
                            <th>Total Visits Booked</th>
                            <th>Utilization Share</th>
                            <th>Operational Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departmentStats as $dept)
                            <tr>
                                <td><strong>{{ $dept->department }}</strong></td>
                                <td><strong>{{ $dept->count }}</strong> consultations</td>
                                <td>
                                    @php
                                        $totalApts = max(1, $stats['appointments_month']);
                                        $percent = round(($dept->count / $totalApts) * 100);
                                    @endphp
                                    <div style="background:#e8f5ee;border-radius:10px;height:14px;width:100%;overflow:hidden;">
                                        <div style="background:var(--green);height:100%;width:{{ min(100, $percent) }}%;"></div>
                                    </div>
                                    <div style="font-size:10px;color:var(--muted);margin-top:2px;">{{ $percent }}% of total</div>
                                </td>
                                <td><span class="badge badge-green">Operational</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;padding:1.2rem;color:var(--muted);">No department statistics recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Prescriptions Stream --}}
        <div class="card">
            <div class="card-header">
                <span>Recent System Prescriptions</span>
                <span style="font-size:11px;font-weight:400;text-transform:none;">Live audit trail</span>
            </div>
            <div class="card-body" style="padding:0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Rx ID</th>
                            <th>Patient</th>
                            <th>Prescribing Doctor</th>
                            <th>Diagnosis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPrescriptions as $rx)
                            <tr>
                                <td><strong>{{ $rx->prescription_number }}</strong></td>
                                <td>{{ $rx->patient->full_name }}</td>
                                <td>{{ $rx->doctor->name }}</td>
                                <td>{{ Str::limit($rx->diagnosis, 35) }}</td>
                                <td>
                                    <span class="badge {{ $rx->status === 'dispensed' ? 'badge-green' : 'badge-yellow' }}">
                                        {{ ucfirst($rx->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Sidebar: Administrative Controls & Quick Links --}}
    <div>
        <div class="card" style="margin-bottom:1.4rem;">
            <div class="card-header">User &amp; Security Management</div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:.6rem;">
                <a href="{{ route('admin.users') }}" class="btn btn-green" style="width:100%;">Manage Users &amp; Role Authorization &rarr;</a>
                <a href="{{ route('pharmacy.inventory') }}" class="btn btn-outline" style="width:100%;">Medicine Inventory Control</a>
                <a href="{{ route('doctor.queue') }}" class="btn btn-outline" style="width:100%;">View Live Consultation Queues</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">System Health Status</div>
            <div class="card-body" style="font-size:12px;line-height:1.7;">
                <div><strong>Framework:</strong> Laravel {{ app()->version() }}</div>
                <div><strong>PHP Version:</strong> {{ phpversion() }}</div>
                <div><strong>Database:</strong> SQLite Local Medical Database</div>
                <div><strong>PDF Engine:</strong> DomPDF v3.1 (Printable Export Active)</div>
                <div style="margin-top:.8rem;border-top:1px solid #ecf2ee;padding-top:.5rem;">
                    <strong>Security Mode:</strong> Role-Based Access Control (RBAC) Active
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
