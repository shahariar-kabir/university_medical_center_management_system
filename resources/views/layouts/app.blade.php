<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BUMC') | University Medical Centre</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:     #1a6b3a;
            --green-dk:  #134d2a;
            --green-lt:  #2d9156;
            --green-pale:#e8f5ee;
            --gold:      #c8922a;
            --bg:        #f4f8f5;
            --card:      #ffffff;
            --text:      #1c2b22;
            --muted:     #56735f;
            --border:    #c3dbc9;
            --radius:    6px;
            --shadow:    0 1px 4px rgba(0,0,0,.10);
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 400;
            font-size: 14px;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
        }

        /* ── TOP TICKER ── */
        .ticker {
            background: var(--gold);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .ticker-label {
            background: rgba(0,0,0,.2);
            padding: 1px 8px;
            border-radius: 3px;
            white-space: nowrap;
            letter-spacing: .04em;
            font-size: 11px;
        }
        .ticker-text { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }

        /* ── HEADER ── */
        .site-header {
            background: var(--green);
            padding: .9rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            border-bottom: 3px solid var(--gold);
        }
        .site-header .logo-mark {
            width: 52px; height: 52px;
            background: #fff;
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            line-height: 0;
        }
        .site-header .logo-text { color: #fff; }
        .site-header .logo-text strong {
            display: block;
            font-size: 1.1rem;
            letter-spacing: .01em;
            font-weight: 700;
        }
        .site-header .logo-text span {
            font-size: 11px;
            opacity: .78;
            font-weight: 400;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .header-right {
            margin-left: auto;
            color: rgba(255,255,255,.7);
            font-size: 12px;
            text-align: right;
            line-height: 1.7;
        }
        .header-right strong { color: #fff; font-size: 13px; display: block; }

        /* ── NAV BAR ── */
        nav {
            background: var(--green-dk);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 6px rgba(0,0,0,.25);
        }
        nav a {
            color: rgba(255,255,255,.82);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            padding: .75rem 1rem;
            display: block;
            border-bottom: 3px solid transparent;
            transition: color .15s, border-color .15s, background .15s;
            letter-spacing: .02em;
        }
        nav a:hover { color: #fff; background: rgba(255,255,255,.07); }
        nav a.active {
            color: #fff;
            border-bottom-color: var(--gold);
            background: rgba(255,255,255,.06);
        }

        /* ── BREADCRUMB ── */
        .breadcrumb {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: .45rem 2rem;
            font-size: 12px;
            color: var(--muted);
        }
        .breadcrumb a { color: var(--green); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .breadcrumb span { margin: 0 .35rem; }

        /* ── MAIN ── */
        main {
            flex: 1;
            padding: 1.8rem 2rem;
            max-width: 1180px;
            margin: 0 auto;
            width: 100%;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--green-dk);
            color: rgba(255,255,255,.55);
            font-size: 12px;
            padding: 1.4rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: auto;
        }
        footer .footer-links { display: flex; gap: 1.2rem; }
        footer .footer-links a { color: rgba(255,255,255,.55); text-decoration: none; }
        footer .footer-links a:hover { color: #fff; text-decoration: underline; }

        /* ── SHARED COMPONENTS ── */
        .page-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--green);
            margin-bottom: .25rem;
        }
        .page-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 1.4rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: .9rem;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .btn {
            display: inline-block;
            padding: .45rem 1.1rem;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 700;
            font-family: Arial, Helvetica, sans-serif;
            cursor: pointer; border: none;
            text-decoration: none;
            transition: background .15s;
            letter-spacing: .02em;
        }
        .btn-green   { background: var(--green);    color: #fff; }
        .btn-green:hover { background: var(--green-dk); }
        .btn-outline {
            background: transparent;
            color: var(--green);
            border: 1.5px solid var(--green);
        }
        .btn-outline:hover { background: var(--green); color: #fff; }
        .btn-gold { background: var(--gold); color: #fff; }
        .btn-gold:hover { background: #a8771f; }

        .badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .badge-green  { background: #d4edda; color: #1a5c2e; }
        .badge-yellow { background: #fff3cd; color: #856404; }
        .badge-red    { background: #f8d7da; color: #721c24; }
        .badge-gray   { background: #e2e3e5; color: #383d41; }

        .alert {
            padding: .75rem 1rem;
            border-radius: var(--radius);
            font-size: 13px;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }
        .alert-success { background: #d4edda; color: #155724; border-color: #28a745; }
        .alert-error   { background: #f8d7da; color: #721c24; border-color: #dc3545; }

        /* form basics shared */
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .form-label .req { color: #c0392b; margin-left: 2px; }
        .form-control {
            width: 100%;
            border: 1px solid #b0c4b8;
            border-radius: 3px;
            padding: .42rem .6rem;
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--green);
            box-shadow: 0 0 0 2px rgba(26,107,58,.15);
        }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 75px; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Announcement ticker --}}
<div class="ticker">
    <span class="ticker-label">NOTICE</span>
    <span class="ticker-text">Free annual medical check-up for Semester 1 students &nbsp;|&nbsp; Flu vaccination drive: Oct 10–14 &nbsp;|&nbsp; Dental clinic closed Saturday Oct 12 for maintenance</span>
</div>

{{-- Site header --}}
<div class="site-header">
    <a href="{{ url('/') }}" style="display:flex;align-items:center;gap:1.1rem;text-decoration:none;">
        <div style="line-height:1;">
            <span style="font-size:1.6rem;font-weight:700;color:#fff;letter-spacing:-.01em;">UniMed<span style="color:#c8d9c0;">Center</span></span>
        </div>
        <div style="width:1px;height:36px;background:rgba(255,255,255,.25);"></div>
        <div class="logo-text">
            <strong>University Medical Centre</strong>
            <span>Health &amp; Wellness Division &mdash; Student Affairs</span>
        </div>
    </a>
    <div class="header-right">
        <strong>Hotline: 0188888888</strong>
        Mon–Fri 8:00am – 8:00pm &nbsp;|&nbsp; Sat 9:00am – 2:00pm
    </div>
</div>

{{-- Nav --}}
<nav>
    <a href="{{ url('/') }}"                  class="{{ request()->is('/')                  ? 'active' : '' }}">Home</a>
    <a href="{{ url('/patients/register') }}"  class="{{ request()->is('patients/register')  ? 'active' : '' }}">Patient Registration</a>
    <a href="{{ url('/appointments') }}"       class="{{ request()->is('appointments')       ? 'active' : '' }}">Appointments</a>
</nav>

{{-- Breadcrumb --}}
<div class="breadcrumb">
    <a href="{{ url('/') }}">Home</a>
    @if(!request()->is('/'))
        <span>/</span>
        @if(request()->is('patients/register'))
            Patient Registration
        @elseif(request()->is('appointments'))
            Appointments
        @endif
    @endif
</div>

<main>
    @yield('content')
</main>

<footer>
    <div>
        &copy; {{ date('Y') }} University Medical Centre &mdash; All rights reserved.<br>
        Medical Centre Building, Campus Road, Dhaka 1205
    </div>
    <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Contact Us</a>
        <a href="#">Site Map</a>
    </div>
</footer>

@stack('scripts')
</body>
</html>
