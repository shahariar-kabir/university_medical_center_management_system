@extends('layouts.app')
@section('title', 'Home')

@push('styles')
<style>
    /* ── Hero Banner ── */
    .hero-banner {
        background: linear-gradient(135deg, var(--green-dk) 0%, var(--green) 100%);
        color: #fff;
        border-radius: var(--radius);
        padding: 2rem 2.2rem;
        margin-bottom: 1.4rem;
        border-left: 5px solid var(--gold);
        display: grid;
        grid-template-columns: 1fr 270px;
        gap: 1.8rem;
        align-items: center;
    }
    .hero-banner h1 {
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: .6rem;
        letter-spacing: -.01em;
    }
    .hero-banner p {
        font-size: 13px;
        line-height: 1.6;
        color: rgba(255,255,255,.9);
        margin-bottom: 1.2rem;
    }
    .hero-note {
        font-size: 11px;
        color: rgba(255,255,255,.68);
        margin-top: .6rem;
    }
    .hero-right {
        background: rgba(0,0,0,.22);
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 5px;
        padding: 1.1rem;
        font-size: 12px;
        line-height: 1.7;
    }
    .hero-right strong { display: block; font-size: 13px; color: #fff; margin-bottom: 4px; }

    /* ── 4 Module Gateways ── */
    .module-gateways {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
        margin-bottom: 1.6rem;
    }
    .module-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.1rem;
        box-shadow: var(--shadow);
        border-top: 4px solid var(--green);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .module-card.gold  { border-top-color: var(--gold); }
    .module-card.blue  { border-top-color: #2980b9; }
    .module-card.red   { border-top-color: #c0392b; }
    .module-card h3 { font-size: 14px; font-weight: 700; color: var(--green-dk); margin-bottom: .4rem; }
    .module-card p  { font-size: 12px; color: var(--muted); margin-bottom: .9rem; line-height: 1.5; flex-grow: 1; }

    /* ── Two Column Content ── */
    .home-grid {
        display: grid;
        grid-template-columns: 1fr 310px;
        gap: 1.5rem;
        align-items: start;
    }
    .section-head {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--green);
        border-bottom: 2px solid var(--green);
        padding-bottom: 4px;
        margin-bottom: .9rem;
    }

    .services-list {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 1.4rem;
    }
    .svc-row {
        padding: .75rem 1rem;
        border-bottom: 1px solid #ecf2ee;
    }
    .svc-row:last-child { border-bottom: none; }
    .svc-row:nth-child(even) { background: #f9fbfa; }
    .svc-info h4 { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
    .svc-info p  { font-size: 12px; color: var(--muted); font-weight: 400; }

    @media (max-width: 860px) {
        .hero-banner { grid-template-columns: 1fr; }
        .home-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- Hero Banner --}}
<div class="hero-banner">
    <div>
        <h1>University Medical Centre</h1>
        <p>Centralized digital healthcare platform for students, faculty, and university personnel. Manage your patient profile, book doctor consultations, track live waiting queues, view and download prescriptions, and access pharmacy dispensing services.</p>
        <div style="display:flex;gap:.7rem;flex-wrap:wrap;align-items:center;">
            @auth
                @if(Auth::user()->isPatient())
                    <a href="{{ route('patient.dashboard') }}" class="btn btn-gold">Go to My Patient Dashboard</a>
                    <a href="{{ route('patient.queue') }}" class="btn btn-outline" style="border-color:rgba(255,255,255,.6);color:#fff;">View Live Queue</a>
                @elseif(Auth::user()->isDoctor())
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-gold">Doctor Consultation Desk</a>
                    <a href="{{ route('doctor.queue') }}" class="btn btn-outline" style="border-color:rgba(255,255,255,.6);color:#fff;">Patient Queue</a>
                @elseif(Auth::user()->isPharmacist())
                    <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-gold">Pharmacy Desk</a>
                    <a href="{{ route('pharmacy.inventory') }}" class="btn btn-outline" style="border-color:rgba(255,255,255,.6);color:#fff;">Inventory Control</a>
                @elseif(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-gold">Admin Analytics Panel</a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline" style="border-color:rgba(255,255,255,.6);color:#fff;">User Management</a>
                @endif
            @else
                <a href="{{ route('patients.register') }}" class="btn btn-gold">Register as a Patient</a>
                <a href="{{ route('login') }}" class="btn btn-outline" style="border-color:rgba(255,255,255,.6);color:#fff;">Portal Sign In</a>
            @endauth
        </div>
        <p class="hero-note">* Subsidized primary medical care with valid University Student or Employee ID card.</p>
    </div>
    <div class="hero-right">
        <strong>Emergency: 0188888888</strong>
        Mon &ndash; Fri &nbsp;8:00am &ndash; 8:00pm<br>
        Saturday &nbsp;9:00am &ndash; 2:00pm<br>
        Sunday &nbsp;Emergency Counter Only<br><br>
        Medical Centre Building,<br>
        Campus Road, Dhaka 1205
    </div>
</div>

{{-- Real-time System Statistics --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-val">{{ $stats['patient_count'] ?? 0 }}</div>
        <div class="stat-label">Registered Patients</div>
        <div class="stat-sub">Verified university members</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-val">{{ $stats['staff_count'] ?? 0 }}</div>
        <div class="stat-label">Medical Staff</div>
        <div class="stat-sub">Doctors &amp; Pharmacists on roster</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-val">{{ $stats['appointments_month'] ?? 0 }}</div>
        <div class="stat-label">Appointments This Month</div>
        <div class="stat-sub">Scheduled &amp; attended visits</div>
    </div>
    <div class="stat-card">
        <div class="stat-val">{{ $stats['medicines_count'] ?? 0 }}</div>
        <div class="stat-label">Medicines In Stock</div>
        <div class="stat-sub">Tracked in Central Pharmacy</div>
    </div>
</div>

{{-- 4 Core Modules Gateways --}}
<div class="module-gateways">
    <div class="module-card">
        <div>
            <h3>3.1 Patient Module</h3>
            <p>Profile management, identity verification, appointment booking, live queue tracking, and dedicated portal to view and download printable PDF prescriptions.</p>
        </div>
        <a href="{{ route('patient.dashboard') }}" class="btn btn-green btn-sm">Access Patient Portal &rarr;</a>
    </div>

    <div class="module-card blue">
        <div>
            <h3>3.2 Doctor Module</h3>
            <p>Patient consultation queue management, medical history access for authorized consultations, and digital prescription generation with stock selection and dosage specifications.</p>
        </div>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline btn-sm">Doctor Consultation Desk &rarr;</a>
    </div>

    <div class="module-card gold">
        <div>
            <h3>3.3 Pharmacy Module</h3>
            <p>Medicine inventory control, stock monitoring, near-expiry alerts (&lt;60 days), and prescription fulfillment with automated inventory deductions.</p>
        </div>
        <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-gold btn-sm">Pharmacy Desk &rarr;</a>
    </div>

    <div class="module-card red">
        <div>
            <h3>3.4 Admin &amp; Reports</h3>
            <p>User management, role authorization (Admin, Doctor, Pharmacist, Patient), and operational analytics across departments and medical services.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">System Administration &rarr;</a>
    </div>
</div>

<div class="home-grid">
    {{-- Left: Available Services + Duty Roster --}}
    <div>
        <div class="section-head">Core Medical Centre Services</div>
        <div class="services-list">
            <div class="svc-row">
                <div class="svc-info">
                    <h4>General Medicine &amp; Consultation</h4>
                    <p>Walk-in and appointment consultations for common illnesses, clinical assessments, and referral letters.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Dental Clinic</h4>
                    <p>Dental check-ups, scaling, extractions, fillings, and emergency oral care.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Counselling &amp; Mental Health</h4>
                    <p>Confidential mental health guidance and professional psychological counselling.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Laboratory &amp; Diagnostics</h4>
                    <p>Routine diagnostic tests, blood work, urinalysis, and glucose screening with rapid turnaround.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Central Pharmacy &amp; Dispensary</h4>
                    <p>Subsidized dispensing of prescribed medications directly from the medical centre inventory.</p>
                </div>
            </div>
        </div>

        <div class="section-head">Doctors on Duty Today</div>
        <div class="card" style="margin-bottom: 1.4rem;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doc)
                        <tr>
                            <td><strong>{{ $doc->name }}</strong></td>
                            <td>{{ $doc->department ?? 'General Medicine' }}</td>
                            <td style="font-size:11px;color:var(--muted);">{{ $doc->designation ?? 'Medical Officer' }}</td>
                            <td><span class="badge badge-green">On Duty</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--muted);padding:1rem;">Doctor schedule is being refreshed.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right: Notice Board & Operating Hours --}}
    <div>
        <div class="section-head">Notice Board</div>
        <div class="card" style="margin-bottom: 1.4rem;">
            <div class="card-body" style="font-size:12px;line-height:1.5;">
                <div style="border-bottom:1px solid #ecf2ee;padding-bottom:.5rem;margin-bottom:.5rem;">
                    <span class="badge badge-red" style="font-size:10px;">URGENT</span>
                    <strong>Annual Health Screening</strong>
                    <div style="color:var(--muted);font-size:11px;margin-top:2px;">Free routine checkups for all 1st year students at Room 102.</div>
                </div>
                <div style="border-bottom:1px solid #ecf2ee;padding-bottom:.5rem;margin-bottom:.5rem;">
                    <span class="badge badge-blue" style="font-size:10px;">VACCINATION</span>
                    <strong>Seasonal Flu Vaccine Drive</strong>
                    <div style="color:var(--muted);font-size:11px;margin-top:2px;">Available at Pharmacy counter. Walk-ins accepted with student ID.</div>
                </div>
                <div>
                    <span class="badge badge-gray" style="font-size:10px;">GENERAL</span>
                    <strong>Emergency Hotline 24/7</strong>
                    <div style="color:var(--muted);font-size:11px;margin-top:2px;">Call <strong>0188888888</strong> for on-campus medical transport.</div>
                </div>
            </div>
        </div>

        <div class="section-head">Operating Hours</div>
        <div class="card">
            <div class="card-body">
                <table style="width:100%;font-size:12px;border-collapse:collapse;">
                    <tr style="border-bottom:1px solid #ecf2ee;"><td style="padding:.35rem 0;font-weight:700;">Monday &ndash; Friday</td><td style="text-align:right;">8:00am &ndash; 8:00pm</td></tr>
                    <tr style="border-bottom:1px solid #ecf2ee;"><td style="padding:.35rem 0;font-weight:700;">Saturday</td><td style="text-align:right;">9:00am &ndash; 2:00pm</td></tr>
                    <tr><td style="padding:.35rem 0;font-weight:700;color:#c0392b;">Sunday</td><td style="text-align:right;color:#c0392b;">Emergency Only</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
