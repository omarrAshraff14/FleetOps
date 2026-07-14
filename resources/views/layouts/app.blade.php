<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FleetOps — @yield('title', 'Dashboard')</title>

    @if(app()->getLocale() === 'ar')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --sidebar-w: 240px;
            --header-h: 56px;
            --accent: oklch(0.9 0.015 220);
            --accent-dark: oklch(0.45 0.12 220);
            --accent-mid: oklch(0.6 0.10 220);

            --sidebar-bg: #0f2130;
            --sidebar-hover: rgba(255,255,255,.07);
            --sidebar-active: rgba(255,255,255,.12);
            --sidebar-text: #8faec4;
            --sidebar-text-active: #fff;
            --sidebar-section: #4a6a82;

            --header-bg: #fff;
            --header-border: #e8eaed;
            --body-bg: #f4f6f8;
            --card-bg: #fff;
            --border: #e8eaed;

            --status-ready-c: #1d9e75;        --status-ready-bg: #e1f5ee;
            --status-in-use-c: var(--accent-dark); --status-in-use-bg: #e8f0fe;
            --status-not-ready-c: #dc3545;    --status-not-ready-bg: #fce8ea;
            --status-maintenance-c: #f59e0b;  --status-maintenance-bg: #fef3c7;
            --status-retired-c: #6c757d;      --status-retired-bg: #f1f3f5;

            --text-primary: #1a2332;
            --text-secondary: #6b7a8d;
            --text-muted: #9aa3ae;
            --radius: 10px;
            --radius-sm: 6px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
            --shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; font-family: 'Segoe UI', system-ui, sans-serif; background: var(--body-bg); color: var(--text-primary); font-size: 14px; }

        /* ── LAYOUT ── */
        .app-layout { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
            transition: width .25s ease, transform .25s ease;
        }
        .sidebar::-webkit-scrollbar { display: none; }

        /* RTL/LTR sidebar position */
        [dir="ltr"] .sidebar { left: 0; }
        [dir="rtl"] .sidebar { right: 0; }

        /* Collapsed */
        .sidebar.collapsed { width: 0; overflow: hidden; }

        /* ── LOGO ── */
        .sidebar-logo {
            padding: 16px 18px 13px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            text-decoration: none; flex-shrink: 0;
        }
        .sidebar-logo-icon {
            width: 34px; height: 34px;
            background: var(--accent-dark);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 17px; flex-shrink: 0;
        }
        .sidebar-logo-name { font-size: 14px; font-weight: 700; color: #fff; display: block; }
        .sidebar-logo-sub  { font-size: 11px; color: var(--sidebar-text); display: block; }

        /* ── NAV ── */
        .sidebar-nav { padding: 10px 0; flex: 1; }
        .sidebar-section-label {
            font-size: 10px; font-weight: 600; letter-spacing: .7px;
            text-transform: uppercase; color: var(--sidebar-section);
            padding: 12px 18px 5px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 18px;
            color: var(--sidebar-text);
            text-decoration: none; font-size: 13px; font-weight: 400;
            transition: background .15s, color .15s;
            position: relative; white-space: nowrap;
        }
        .sidebar-link:hover  { background: var(--sidebar-hover); color: #fff; }
        .sidebar-link.active { background: var(--sidebar-active); color: var(--sidebar-text-active); font-weight: 500; }
        .sidebar-link.active::after {
            content: '';
            position: absolute;
            top: 5px; bottom: 5px; width: 3px;
            background: var(--accent-dark);
            border-radius: 3px;
        }
        [dir="ltr"] .sidebar-link.active::after { right: 0; border-radius: 3px 0 0 3px; }
        [dir="rtl"] .sidebar-link.active::after { left: 0;  border-radius: 0 3px 3px 0; }
        .sidebar-link i { font-size: 15px; width: 17px; text-align: center; flex-shrink: 0; }

        /* ── USER footer ── */
        .sidebar-user {
            padding: 12px 14px;
            border-top: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: 10px; flex-shrink: 0;
        }
        .sidebar-user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--accent-dark);
            color: #fff; font-size: 12px; font-weight: 600;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .sidebar-user-name { font-size: 12.5px; font-weight: 500; color: #fff; }
        .sidebar-user-role { font-size: 10.5px; color: var(--sidebar-text); }

        /* ── MAIN ── */
        .main-wrap {
            flex: 1;
            display: flex; flex-direction: column; min-height: 100vh;
            transition: margin .25s ease;
        }
        [dir="ltr"] .main-wrap { margin-left: var(--sidebar-w); }
        [dir="rtl"] .main-wrap { margin-right: var(--sidebar-w); }
        [dir="ltr"] .main-wrap.sidebar-collapsed { margin-left: 0; }
        [dir="rtl"] .main-wrap.sidebar-collapsed { margin-right: 0; }

        /* ── HEADER ── */
        .app-header {
            height: var(--header-h);
            background: var(--header-bg);
            border-bottom: 1px solid var(--header-border);
            display: flex; align-items: center;
            padding: 0 20px; gap: 12px;
            position: sticky; top: 0; z-index: 90;
        }

        /* Sidebar toggle btn */
        .sidebar-toggle {
            width: 34px; height: 34px; border-radius: 8px;
            border: 1px solid var(--border); background: transparent;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); font-size: 16px;
            cursor: pointer; flex-shrink: 0;
            transition: background .15s;
        }
        .sidebar-toggle:hover { background: var(--body-bg); }

        /* Search */
        .header-search { flex: 1; max-width: 380px; position: relative; }
        .header-search input {
            width: 100%; height: 34px;
            border: 1px solid var(--border); border-radius: 18px;
            padding: 0 34px 0 12px;
            font-size: 13px; background: var(--body-bg);
            outline: none; transition: border .2s, box-shadow .2s;
        }
        .header-search input:focus { border-color: var(--accent-dark); box-shadow: 0 0 0 3px rgba(30,100,200,.1); background: #fff; }
        .header-search i { position: absolute; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px; }
        [dir="ltr"] .header-search i { right: 11px; }
        [dir="rtl"] .header-search i { left: 11px; }

        .header-spacer { flex: 1; }

        /* Language switcher */
        .lang-switch {
            display: flex; gap: 2px;
            background: var(--body-bg); border: 1px solid var(--border);
            border-radius: 8px; padding: 3px;
        }
        .lang-btn {
            padding: 3px 10px; border-radius: 6px;
            font-size: 12px; font-weight: 500;
            border: none; background: transparent;
            color: var(--text-muted); cursor: pointer;
            transition: all .15s;
        }
        .lang-btn.active {
            background: var(--accent-dark);
            color: #fff;
        }

        /* Icon btn */
        .header-icon-btn {
            width: 34px; height: 34px; border-radius: 50%;
            border: 1px solid var(--border); background: transparent;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); font-size: 16px;
            cursor: pointer; position: relative; transition: background .15s;
        }
        .header-icon-btn:hover { background: var(--body-bg); }
        .notif-dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: #dc3545; border-radius: 50%; border: 2px solid #fff;
        }

        /* Header avatar */
        .header-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--accent-dark); color: #fff;
            font-size: 12px; font-weight: 600;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
        }

        /* ── PAGE ── */
        .page-content { padding: 24px; flex: 1; }
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 22px; }
        .page-title   { font-size: 21px; font-weight: 700; margin: 0; }
        .page-subtitle { font-size: 12.5px; color: var(--text-muted); margin: 3px 0 0; }
        .page-actions { display: flex; gap: 8px; align-items: center; }

        /* ── BUTTONS ── */
        .btn-primary-fleet {
            background: var(--accent-dark); color: #fff;
            border: none; border-radius: var(--radius-sm);
            padding: 8px 15px; font-size: 13px; font-weight: 500;
            display: inline-flex; align-items: center; gap: 6px;
            cursor: pointer; text-decoration: none; transition: opacity .15s;
        }
        .btn-primary-fleet:hover { opacity: .88; color: #fff; }

        .btn-outline-fleet {
            background: transparent; color: var(--text-secondary);
            border: 1px solid var(--border); border-radius: var(--radius-sm);
            padding: 8px 15px; font-size: 13px; font-weight: 500;
            display: inline-flex; align-items: center; gap: 6px;
            cursor: pointer; text-decoration: none; transition: background .15s;
        }
        .btn-outline-fleet:hover { background: var(--body-bg); color: var(--text-primary); }

        /* ── STATUS BADGES ── */
        .status-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 11.5px; font-weight: 500;
        }
        .status-badge.ready       { background: var(--status-ready-bg);       color: var(--status-ready-c); }
        .status-badge.in_use      { background: var(--status-in-use-bg);      color: var(--status-in-use-c); }
        .status-badge.not_ready   { background: var(--status-not-ready-bg);   color: var(--status-not-ready-c); }
        .status-badge.maintenance { background: var(--status-maintenance-bg); color: var(--status-maintenance-c); }
        .status-badge.retired     { background: var(--status-retired-bg);     color: var(--status-retired-c); }

        /* ── FLASH ── */
        .flash-success { background: var(--status-ready-bg); border: 1px solid var(--status-ready-c); color: var(--status-ready-c); border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: 18px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .flash-error   { background: var(--status-not-ready-bg); border: 1px solid var(--status-not-ready-c); color: var(--status-not-ready-c); border-radius: var(--radius-sm); padding: 10px 14px; margin-bottom: 18px; font-size: 13px; }

        @stack('styles')
    </style>
    @stack('styles')
</head>
<body>
<div class="app-layout">

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon"><i class="bi bi-truck"></i></div>
        <div>
            <span class="sidebar-logo-name">FleetOps</span>
            <span class="sidebar-logo-sub">{{ currentTenant()->name }}</span>
        </div>
    </a>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Overview</div>
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <div class="sidebar-section-label">Fleet</div>
        <a href="{{ route('fleet.cars.index') }}" class="sidebar-link {{ request()->routeIs('fleet.cars.*') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> Cars
        </a>
        <a href="#" class="sidebar-link {{ request()->routeIs('fleet.car-logs.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Car Logs
        </a>
        <a href="#" class="sidebar-link {{ request()->routeIs('fleet.kroky.*') ? 'active' : '' }}">
            <i class="bi bi-layers"></i> Kroky
        </a>

        <div class="sidebar-section-label">Operations</div>
        <a href="#" class="sidebar-link {{ request()->routeIs('operations.requests.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i> Requests
        </a>
        <a href="#" class="sidebar-link {{ request()->routeIs('operations.customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Customers
        </a>

        <div class="sidebar-section-label">Maintenance</div>
        <a href="#" class="sidebar-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
            <i class="bi bi-wrench-adjustable"></i> Repair Orders
        </a>

        <div class="sidebar-section-label">Workforce</div>
        <a href="#" class="sidebar-link {{ request()->routeIs('hr.timesheets.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Timesheets
        </a>

        @role('super_admin')
        <div class="sidebar-section-label">Admin</div>
        <a href="#" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Users
        </a>
        <a href="#" class="sidebar-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Branches
        </a>
        <a href="#" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        @endrole
    </nav>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div style="flex:1;min-width:0">
            <div class="sidebar-user-name text-truncate">{{ auth()->user()->name }}</div>
            <div class="sidebar-user-role">
                {{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}
                · {{ auth()->user()->branch?->name }}
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="border:none;background:transparent;color:var(--sidebar-text);cursor:pointer;font-size:16px;padding:4px" title="Logout">
                <i class="bi bi-box-arrow-left"></i>
            </button>
        </form>
    </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main-wrap" id="mainWrap">

    {{-- Header --}}
    <header class="app-header">
        {{-- Toggle btn --}}
        <button class="sidebar-toggle" id="sidebarToggle" title="Toggle sidebar">
            <i class="bi bi-layout-sidebar" id="toggleIcon"></i>
        </button>

        {{-- Search --}}
        <div class="header-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search cars, requests, customers...">
        </div>

        <div class="header-spacer"></div>

        {{-- Language switcher --}}
        <div class="lang-switch">
            <form method="POST" action="{{ route('locale.switch', 'en') }}" style="margin:0">
                @csrf
                <button type="submit" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</button>
            </form>
            <form method="POST" action="{{ route('locale.switch', 'ar') }}" style="margin:0">
                @csrf
                <button type="submit" class="lang-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}">AR</button>
            </form>
        </div>

        {{-- Notifications --}}
        <button class="header-icon-btn">
            <i class="bi bi-bell"></i>
            <span class="notif-dot"></span>
        </button>

        {{-- Avatar --}}
        <div class="header-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
    </header>

    {{-- Content --}}
    <main class="page-content">
        @if(session('success'))
        <div class="flash-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash-error"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
// ── Sidebar Toggle ──
const sidebar    = document.getElementById('sidebar');
const mainWrap   = document.getElementById('mainWrap');
const toggleIcon = document.getElementById('toggleIcon');
let collapsed    = localStorage.getItem('sidebarCollapsed') === 'true';

function applySidebar() {
    if (collapsed) {
        sidebar.classList.add('collapsed');
        mainWrap.classList.add('sidebar-collapsed');
        toggleIcon.className = 'bi bi-layout-sidebar-reverse';
    } else {
        sidebar.classList.remove('collapsed');
        mainWrap.classList.remove('sidebar-collapsed');
        toggleIcon.className = 'bi bi-layout-sidebar';
    }
}

applySidebar();

document.getElementById('sidebarToggle').addEventListener('click', () => {
    collapsed = !collapsed;
    localStorage.setItem('sidebarCollapsed', collapsed);
    applySidebar();
});
</script>
@stack('scripts')
</body>
</html>

