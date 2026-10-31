@extends('layouts.app')
@section('title', 'User Management & Role Authorization')

@section('content')
<div class="page-title">User Management &amp; Role Authorization</div>
<div class="page-subtitle">Configure medical personnel, assign roles (Admin, Doctor, Pharmacist, Patient), and manage access permissions.</div>

{{-- Top Filter & Add User Button --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem;gap:1rem;flex-wrap:wrap;">
    <form action="{{ route('admin.users') }}" method="GET" style="display:flex;gap:.5rem;flex-wrap:wrap;">
        <input type="text" name="search" class="form-control" style="width:220px;" placeholder="Search name, email, ID..." value="{{ request('search') }}">
        <select name="role" class="form-control" style="width:160px;">
            <option value="">&mdash; All Roles &mdash;</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            <option value="doctor" {{ request('role') === 'doctor' ? 'selected' : '' }}>Doctor</option>
            <option value="pharmacist" {{ request('role') === 'pharmacist' ? 'selected' : '' }}>Pharmacist</option>
            <option value="patient" {{ request('role') === 'patient' ? 'selected' : '' }}>Patient</option>
        </select>
        <button type="submit" class="btn btn-green btn-sm">Filter Users</button>
        @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm">Clear</a>
        @endif
    </form>

    <button type="button" onclick="document.getElementById('newUserFormCard').scrollIntoView({behavior:'smooth'})" class="btn btn-gold btn-sm">
        + Create New Staff / User Account
    </button>
</div>

{{-- Users Table --}}
<div class="card" style="margin-bottom:1.8rem;">
    <div class="card-header">
        <span>System Users Directory ({{ $users->total() }})</span>
        <span style="font-size:11px;font-weight:400;text-transform:none;">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email Address</th>
                    <th>Role &amp; Authorization</th>
                    <th>Department &amp; Designation</th>
                    <th>Status</th>
                    <th>Modify Authorization</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>
                            <strong>{{ $u->name }}</strong>
                            @if($u->university_id)
                                <div style="font-size:11px;color:var(--muted);">ID: {{ $u->university_id }}</div>
                            @endif
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @php
                                $roleBadges = [
                                    'admin' => 'badge-red',
                                    'doctor' => 'badge-blue',
                                    'pharmacist' => 'badge-gold',
                                    'patient' => 'badge-green',
                                ];
                            @endphp
                            <span class="badge {{ $roleBadges[$u->role] ?? 'badge-gray' }}" style="font-size:11px;">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>
                            <div>{{ $u->department ?? 'General' }}</div>
                            <small style="color:var(--muted);">{{ $u->designation ?? 'Member' }}</small>
                        </td>
                        <td>
                            @if($u->is_active)
                                <span class="badge badge-green">Active</span>
                            @else
                                <span class="badge badge-red">Deactivated</span>
                            @endif
                        </td>
                        <td>
                            {{-- Quick Role / Status Update Form --}}
                            <form action="{{ route('admin.users.update', $u->id) }}" method="POST" style="display:flex;gap:4px;align-items:center;">
                                @csrf
                                @method('PUT')
                                <select name="role" class="form-control" style="width:110px;padding:2px 4px;font-size:11px;">
                                    <option value="patient" {{ $u->role === 'patient' ? 'selected' : '' }}>Patient</option>
                                    <option value="doctor" {{ $u->role === 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="pharmacist" {{ $u->role === 'pharmacist' ? 'selected' : '' }}>Pharmacist</option>
                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <select name="is_active" class="form-control" style="width:85px;padding:2px 4px;font-size:11px;">
                                    <option value="1" {{ $u->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$u->is_active ? 'selected' : '' }}>Disable</option>
                                </select>
                                <button type="submit" class="btn btn-outline btn-sm" style="padding:2px 6px;font-size:11px;">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:1.6rem;color:var(--muted);">No users found matching query.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($users->hasPages())
    <div style="margin-bottom:1.5rem;">
        {{ $users->links() }}
    </div>
@endif

{{-- Create New User Card --}}
<div class="card" id="newUserFormCard">
    <div class="card-header">+ Create New Medical Staff or Administrative Account</div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:.8rem;margin-bottom:.8rem;">
                <div>
                    <label class="form-label">Full Name <span class="req">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Mahfuzur Rahman" required>
                </div>
                <div>
                    <label class="form-label">Email Address <span class="req">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="name@bu.edu.bd" required>
                </div>
                <div>
                    <label class="form-label">Assigned Role &amp; Access <span class="req">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="doctor">Doctor (Consultation Desk)</option>
                        <option value="pharmacist">Pharmacist (Inventory &amp; Dispensary)</option>
                        <option value="admin">System Administrator</option>
                        <option value="patient">Patient (Student / Staff)</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:.8rem;margin-bottom:1rem;">
                <div>
                    <label class="form-label">Password <span class="req">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Initial password" required minlength="6">
                </div>
                <div>
                    <label class="form-label">University / Employee ID</label>
                    <input type="text" name="university_id" class="form-control" placeholder="e.g. DOC-019">
                </div>
                <div>
                    <label class="form-label">Department / Clinic</label>
                    <input type="text" name="department" class="form-control" placeholder="e.g. Ophthalmology, Dental">
                </div>
                <div>
                    <label class="form-label">Official Designation</label>
                    <input type="text" name="designation" class="form-control" placeholder="e.g. Medical Officer">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-green">Create &amp; Authorize Account</button>
            </div>
        </form>
    </div>
</div>
@endsection
