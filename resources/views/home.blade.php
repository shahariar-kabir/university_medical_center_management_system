@extends('layouts.app')
@section('title', 'Home')

@push('styles')
<style>
    /* ── banner ── */
    .hero-banner {
        background: var(--green);
        color: #fff;
        padding: 2rem 2.2rem;
        border-radius: var(--radius);
        margin-bottom: 1.6rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1.5rem;
        border-left: 6px solid var(--gold);
    }
    .hero-banner h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: .5rem;
        line-height: 1.3;
    }
    .hero-banner p {
        font-size: 13px;
        opacity: .88;
        max-width: 520px;
        margin-bottom: 1rem;
        font-weight: 400;
        line-height: 1.6;
    }
    .hero-banner .btn-gold { font-size: 13px; }
    .hero-note {
        font-size: 11px;
        opacity: .7;
        margin-top: .5rem;
        font-weight: 400;
    }
    .hero-right {
        text-align: right;
        flex-shrink: 0;
        font-size: 12px;
        opacity: .8;
        line-height: 1.9;
        font-weight: 400;
    }
    .hero-right strong { font-size: 14px; display: block; color: #fff; opacity: 1; }

    /* ── two-column layout ── */
    .home-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.4rem;
        align-items: start;
    }

    /* ── quick info bar ── */
    .info-strip {
        display: flex;
        gap: 0;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 1.4rem;
        background: #fff;
        box-shadow: var(--shadow);
    }
    .info-cell {
        flex: 1;
        padding: .85rem 1.1rem;
        border-right: 1px solid var(--border);
    }
    .info-cell:last-child { border-right: none; }
    .info-cell .val {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--green);
        display: block;
        line-height: 1.1;
    }
    .info-cell .lbl {
        font-size: 11px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-top: 3px;
        display: block;
    }

    /* ── services table-style ── */
    .section-head {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--green);
        border-bottom: 2px solid var(--green);
        padding-bottom: .3rem;
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

    /* ── staff on duty ── */
    .duty-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        background: #fff;
    }
    .duty-table th {
        background: var(--green);
        color: #fff;
        padding: .45rem .7rem;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .duty-table td {
        padding: .5rem .7rem;
        border-bottom: 1px solid #ecf2ee;
        vertical-align: top;
    }
    .duty-table tr:last-child td { border-bottom: none; }
    .duty-table tr:nth-child(even) td { background: #f9fbfa; }
    .available { color: #1a6b3a; font-weight: 700; }
    .busy      { color: #856404; }

    /* ── sidebar ── */
    .sidebar-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 1.1rem;
    }
    .sidebar-head {
        background: var(--green);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        padding: .45rem .75rem;
    }
    .sidebar-body { padding: .75rem; }
    .notice-row {
        padding: .45rem 0;
        border-bottom: 1px solid #ecf2ee;
        font-size: 12px;
        font-weight: 400;
        line-height: 1.45;
    }
    .notice-row:last-child { border-bottom: none; }
    .notice-row .n-date { font-size: 11px; color: var(--muted); margin-top: 2px; }
    .notice-row .n-type {
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
        padding: 1px 5px; border-radius: 2px; margin-right: 4px;
    }
    .n-urgent { background: #f8d7da; color: #721c24; }
    .n-info   { background: #d1ecf1; color: #0c5460; }
    .n-gen    { background: #e2e3e5; color: #383d41; }

    .hours-table { width: 100%; font-size: 12px; border-collapse: collapse; }
    .hours-table td { padding: .3rem .1rem; border-bottom: 1px solid #ecf2ee; }
    .hours-table tr:last-child td { border-bottom: none; }
    .hours-table .day { font-weight: 700; width: 40%; }
    .hours-table .closed { color: #c0392b; }

    @media (max-width: 860px) {
        .home-grid { grid-template-columns: 1fr; }
        .info-strip { flex-wrap: wrap; }
        .info-cell { min-width: 48%; }
    }
</style>
@endpush

@section('content')

{{-- Hero Banner --}}
<div class="hero-banner">
    <div>
        <h1>University Medical Centre</h1>
        <p>The Medical Centre provides primary healthcare, dental, counselling and emergency services to all currently enrolled students, faculty members and university staff. Services are subsidised — please bring your university ID card.</p>
        <div style="display:flex;gap:.7rem;flex-wrap:wrap;align-items:center;">
            <a href="{{ url('/patients/register') }}" class="btn btn-gold">Register as a Patient</a>
            <a href="{{ url('/appointments') }}"      class="btn btn-outline" style="border-color:rgba(255,255,255,.5);color:#fff;">Book an Appointment</a>
        </div>
        <p class="hero-note">* Walk-ins accepted for general consultation. Specialist visits require an appointment.</p>
    </div>
    <div class="hero-right">
        <strong>Emergency: 0188888888</strong>
        Mon – Fri &nbsp;8:00am – 8:00pm<br>
        Saturday &nbsp;9:00am – 2:00pm<br>
        Sunday &nbsp;CLOSED<br><br>
        Medical Centre Building,<br>
        Campus Road, Dhaka 1205
    </div>
</div>

{{-- Quick stats strip --}}
<div class="info-strip">
    <div class="info-cell">
        <span class="val">18</span>
        <span class="lbl">Medical Staff</span>
    </div>
    <div class="info-cell">
        <span class="val">3,847</span>
        <span class="lbl">Registered Patients</span>
    </div>
    <div class="info-cell">
        <span class="val">274</span>
        <span class="lbl">Appointments This Month</span>
    </div>
    <div class="info-cell">
        <span class="val">Est. 1998</span>
        <span class="lbl">Years in Service</span>
    </div>
</div>

<div class="home-grid">
    {{-- Left: Services + Duty Roster --}}
    <div>
        <div class="section-head">Available Services</div>
        <div class="services-list">
            <div class="svc-row">
                <div class="svc-info">
                    <h4>General Consultation</h4>
                    <p>Walk-in GP visits for common illnesses, referral letters, and sick-leave certificates. No appointment needed.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Laboratory &amp; Diagnostics</h4>
                    <p>Blood work, urinalysis, glucose screening, and other routine tests. Results typically within 24–48 hours.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Dental Clinic</h4>
                    <p>Check-ups, scaling, simple extractions and fillings. Appointment required. Run by Dr. Karim Mon/Wed/Thu.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Counselling &amp; Mental Health</h4>
                    <p>Confidential one-on-one sessions with a licensed counsellor. Strictly by appointment — call ahead or book online.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>Vaccinations</h4>
                    <p>Seasonal flu, hepatitis B, and typhoid vaccines available. Students may be required to present updated records at enrolment.</p>
                </div>
            </div>
            <div class="svc-row">
                <div class="svc-info">
                    <h4>First Aid &amp; Emergency</h4>
                    <p>Trained first-aid staff available during centre hours. For off-hours emergencies, call 0188888888 for ambulance referral.</p>
                </div>
            </div>
        </div>

        <div class="section-head" style="margin-top:1.6rem;">Doctors on Duty Today</div>
        <div class="card" style="overflow:hidden;">
            <table class="duty-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialisation</th>
                        <th>Hours</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Dr. Anisur Rahman</strong></td>
                        <td>General Medicine</td>
                        <td>8am – 2pm</td>
                        <td class="available">Available</td>
                    </tr>
                    <tr>
                        <td><strong>Dr. Nusrat Sultana</strong></td>
                        <td>Gynaecology</td>
                        <td>10am – 4pm</td>
                        <td class="available">Available</td>
                    </tr>
                    <tr>
                        <td><strong>Dr. Abdur Karim</strong></td>
                        <td>Dental</td>
                        <td>1pm – 6pm</td>
                        <td class="busy">In Session</td>
                    </tr>
                    <tr>
                        <td><strong>Dr. Farid Hossain</strong></td>
                        <td>Internal Medicine</td>
                        <td>2pm – 8pm</td>
                        <td class="available">Available</td>
                    </tr>
                    <tr>
                        <td><strong>Ms. Reshma Akter</strong></td>
                        <td>Counsellor</td>
                        <td>9am – 1pm</td>
                        <td class="busy">In Session</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right: Sidebar --}}
    <div>
        <div class="sidebar-card">
            <div class="sidebar-head">Notices &amp; Announcements</div>
            <div class="sidebar-body">
                <div class="notice-row">
                    <span class="n-type n-urgent">Urgent</span>Semester 1 mandatory health screening starts Oct 7. All freshers must register before Oct 20 or risk enrolment hold.
                    <div class="n-date">4 October 2026</div>
                </div>
                <div class="notice-row">
                    <span class="n-type n-info">Notice</span>Flu vaccination drive: Oct 10–14, Ground Floor Lobby, 10am–3pm daily. Free for all ID holders.
                    <div class="n-date">1 October 2026</div>
                </div>
                <div class="notice-row">
                    <span class="n-type n-gen">General</span>Dental clinic will be closed Saturday 12 October for equipment maintenance. Rescheduling in progress.
                    <div class="n-date">28 September 2026</div>
                </div>
                <div class="notice-row">
                    <span class="n-type n-info">Notice</span>World Mental Health Day (Oct 10): free drop-in counselling, Hall C, 11am–4pm. No appointment needed.
                    <div class="n-date">25 September 2026</div>
                </div>
                <div class="notice-row">
                    <span class="n-type n-gen">General</span>Lab result collection hours changed to 10am–1pm effective immediately. Please check your registered email.
                    <div class="n-date">20 September 2026</div>
                </div>
            </div>
        </div>

        <div class="sidebar-card">
            <div class="sidebar-head">Opening Hours</div>
            <div class="sidebar-body">
                <table class="hours-table">
                    <tr><td class="day">Monday</td>     <td>8:00am – 8:00pm</td></tr>
                    <tr><td class="day">Tuesday</td>    <td>8:00am – 8:00pm</td></tr>
                    <tr><td class="day">Wednesday</td>  <td>8:00am – 8:00pm</td></tr>
                    <tr><td class="day">Thursday</td>   <td>8:00am – 8:00pm</td></tr>
                    <tr><td class="day">Friday</td>     <td>8:00am – 4:00pm</td></tr>
                    <tr><td class="day">Saturday</td>   <td>9:00am – 2:00pm</td></tr>
                    <tr><td class="day">Sunday</td>     <td class="closed">Closed</td></tr>
                </table>
                <p style="font-size:11px;color:var(--muted);margin-top:.6rem;font-weight:400;">Emergency first-aid available 24/7 via hotline: 0188888888</p>
            </div>
        </div>

        <div class="sidebar-card">
            <div class="sidebar-head">Quick Links</div>
            <div class="sidebar-body" style="display:flex;flex-direction:column;gap:.45rem;">
                <a href="{{ url('/patients/register') }}" class="btn btn-green" style="text-align:center;">Register as a Patient</a>
                <a href="{{ url('/appointments') }}"      class="btn btn-outline" style="text-align:center;">Book an Appointment</a>
            </div>
        </div>
    </div>
</div>

@endsection
