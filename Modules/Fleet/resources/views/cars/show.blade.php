@extends('layouts.app')

@section('title', $car->model->brand->name . ' ' . $car->model->name)

@push('styles')
<style>
/* ── Hero card ── */
.car-hero {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: stretch;
    gap: 0;
    margin-bottom: 20px;
    overflow: hidden;
}
.car-hero-visual {
    width: 220px;
    flex-shrink: 0;
    background: linear-gradient(145deg, #0f2130, #1a3d56);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 24px 16px;
    gap: 10px;
}
.car-hero-icon {
    font-size: 64px;
    color: rgba(255,255,255,.25);
    line-height: 1;
}
.car-hero-plate {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 2px;
    font-family: monospace;
}
.car-hero-code {
    font-size: 11px;
    color: rgba(255,255,255,.45);
    letter-spacing: 1px;
}

/* Color dot on hero */
.hero-color-dot {
    width: 12px; height: 12px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,.3);
    display: inline-block; vertical-align: middle;
    margin-left: 4px;
}

.car-hero-body {
    flex: 1;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.hero-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    align-items: center;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 500;
    background: var(--body-bg); border: 1px solid var(--border);
    color: var(--text-secondary);
}
.hero-badge i { font-size: 12px; }

.hero-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.hero-stat {
    background: var(--body-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 10px 12px;
}
.hero-stat-label { font-size: 11px; color: var(--text-muted); margin-bottom: 3px; display: flex; align-items: center; gap: 4px; }
.hero-stat-value { font-size: 14px; font-weight: 600; color: var(--text-primary); }

.hero-identifiers {
    display: flex; flex-wrap: wrap; gap: 16px;
    font-size: 12px; color: var(--text-muted);
    align-items: center;
}
.hero-identifiers span { display: inline-flex; align-items: center; gap: 4px; }
.hero-identifiers strong { color: var(--text-secondary); font-family: monospace; font-size: 11.5px; }

.feature-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 9px; border-radius: 12px; font-size: 11.5px;
    font-weight: 500;
    background: rgba(30,100,200,.08);
    color: var(--accent-dark);
    border: 1px solid rgba(30,100,200,.15);
}

/* ── Tabs ── */
.show-tabs {
    display: flex;
    gap: 2px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 6px 8px;
    margin-bottom: 20px;
    overflow-x: auto;
    scrollbar-width: none;
    box-shadow: var(--shadow-sm);
}
.show-tabs::-webkit-scrollbar { display: none; }
.show-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 13px; border-radius: 7px;
    font-size: 13px; font-weight: 400;
    color: var(--text-secondary);
    cursor: pointer; border: none; background: transparent;
    white-space: nowrap; transition: background .12s, color .12s;
    text-decoration: none;
}
.show-tab:hover { background: var(--body-bg); color: var(--text-primary); }
.show-tab.active { background: var(--body-bg); color: var(--text-primary); font-weight: 500; }
.show-tab i { font-size: 14px; }

/* ── Section card ── */
.section-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    margin-bottom: 16px;
    overflow: hidden;
}
.section-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.section-card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600;
}
.section-card-icon {
    width: 30px; height: 30px; border-radius: 7px;
    background: var(--body-bg); color: var(--accent-dark);
    display: flex; align-items: center; justify-content: center; font-size: 14px;
}
.section-card-body { padding: 18px; }

/* ── Detail table (overview specs) ── */
.detail-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.detail-table tr { border-bottom: 1px solid var(--border); }
.detail-table tr:last-child { border-bottom: none; }
.detail-table td { padding: 10px 4px; }
.detail-table td:first-child { color: var(--text-muted); width: 40%; font-size: 12.5px; }
.detail-table td:last-child { color: var(--text-primary); font-weight: 500; text-align: left; }

/* ── Generic data table ── */
.data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.data-table thead tr { border-bottom: 2px solid var(--border); }
.data-table thead th {
    padding: 10px 12px; font-size: 12px; font-weight: 600;
    color: var(--text-muted); text-align: left; white-space: nowrap;
}
.data-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background .1s;
}
.data-table tbody tr:last-child { border-bottom: none; }
.data-table tbody tr:hover { background: #f8f9fb; }
.data-table td { padding: 11px 12px; color: var(--text-primary); vertical-align: middle; }
.data-table td.muted { color: var(--text-muted); font-size: 12.5px; }

/* ── Badges ── */
.badge-valid        { background:#e1f5ee; color:#1d9e75; border:1px solid #a3dfc8; }
.badge-expiring     { background:#fef3c7; color:#d97706; border:1px solid #fcd34d; }
.badge-expired      { background:#fce8ea; color:#dc3545; border:1px solid #f5c2c7; }
.badge-pass         { background:#e1f5ee; color:#1d9e75; border:1px solid #a3dfc8; }
.badge-fail         { background:#fce8ea; color:#dc3545; border:1px solid #f5c2c7; }
.badge-active       { background: var(--accent-dark); color:#fff; border:1px solid transparent; }
.badge-completed    { background:var(--body-bg); color:var(--text-secondary); border:1px solid var(--border); }
.badge-repaired     { background:#e1f5ee; color:#1d9e75; border:1px solid #a3dfc8; }
.badge-logged       { background:#eef3fc; color:var(--accent-dark); border:1px solid rgba(30,100,200,.2); }
.badge-minor        { background:var(--body-bg); color:var(--text-secondary); border:1px solid var(--border); }
.badge-moderate     { background:#fef3c7; color:#d97706; border:1px solid #fcd34d; }
.badge-severe       { background:#fce8ea; color:#dc3545; border:1px solid #f5c2c7; }

.inline-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 11.5px; font-weight: 500;
}

/* ── Overview 3-col grid ── */
.overview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 16px;
}
@media (max-width: 900px) { .overview-grid { grid-template-columns: 1fr; } }

/* ── Financials mini cards ── */
.fin-cards {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px;
}
.fin-card {
    padding: 12px 14px; border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}
.fin-card-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .4px; margin-bottom: 4px; }
.fin-card-value { font-size: 20px; font-weight: 700; }
.fin-card.repairs  { background: #fce8ea; border-color: #f5c2c7; }
.fin-card.repairs .fin-card-value  { color: #dc3545; }
.fin-card.revenue  { background: #e1f5ee; border-color: #a3dfc8; }
.fin-card.revenue .fin-card-value  { color: #1d9e75; }
.fin-card.util     { background: #eef3fc; border-color: rgba(30,100,200,.2); }
.fin-card.util .fin-card-value     { color: var(--accent-dark); }

/* ── Activity log ── */
.activity-list { display: flex; flex-direction: column; gap: 0; }
.activity-item {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 12.5px;
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--accent-dark); flex-shrink: 0; margin-top: 5px;
}
.activity-action { font-weight: 600; font-family: monospace; font-size: 12px; color: var(--text-primary); }
.activity-meta   { color: var(--text-muted); font-size: 11.5px; margin-top: 2px; }
.activity-time   { margin-right: auto; color: var(--text-muted); font-size: 11.5px; white-space: nowrap; }

/* ── Status timeline ── */
.status-timeline { display: flex; flex-direction: column; gap: 0; }
.status-item {
    display: flex; gap: 14px; padding: 14px 0;
    border-bottom: 1px solid var(--border);
}
.status-item:last-child { border-bottom: none; }
.status-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; margin-top: 5px;
}
.status-dot.ready        { background: var(--status-ready-c); }
.status-dot.in_use       { background: var(--status-in-use-c); }
.status-dot.not_ready    { background: var(--status-not-ready-c); }
.status-dot.maintenance  { background: var(--status-maintenance-c); }
.status-dot.retired      { background: var(--status-retired-c); }
.status-item-body { flex: 1; }
.status-transition { display: flex; align-items: center; gap: 6px; font-size: 13px; margin-bottom: 4px; }
.status-arrow { color: var(--text-muted); font-size: 12px; }
.status-by    { font-size: 12px; color: var(--text-muted); }
.status-reason{ font-size: 12px; color: var(--text-secondary); font-style: italic; margin-top: 3px; }
.override-tag {
    display: inline-block; padding: 1px 7px; border-radius: 4px;
    font-size: 10px; font-weight: 600; background: #fef3c7;
    color: #d97706; border: 1px solid #fcd34d; margin-right: 4px;
}

/* ── Audit trail ── */
.audit-action { font-family: monospace; font-size: 12px; color: var(--accent-dark); }
.audit-detail { font-size: 12px; color: var(--text-muted); }

/* ── Compliance ── */
.compliance-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 13px;
}
.compliance-row:last-child { border-bottom: none; }
.compliance-label { color: var(--text-muted); font-size: 12.5px; }
.compliance-value { font-weight: 500; color: var(--text-primary); }
.compliance-value.warning { color: #d97706; }
.compliance-value.danger  { color: #dc3545; }

/* ── Placeholder (unbuilt modules) ── */
.tab-placeholder {
    text-align: center; padding: 48px 20px;
    color: var(--text-muted);
}
.tab-placeholder-icon {
    font-size: 40px; opacity: .25; display: block; margin-bottom: 12px;
}
.tab-placeholder h6 { font-size: 14px; color: var(--text-secondary); margin-bottom: 6px; }
.tab-placeholder p  { font-size: 12.5px; max-width: 300px; margin: 0 auto; }

/* ── Danger zone ── */
.danger-zone {
    background: #fffafa;
    border: 1px solid #fce8ea;
    border-radius: var(--radius);
    padding: 16px 20px;
    margin-top: 8px;
}
.danger-zone-title { font-size: 13px; font-weight: 600; color: #dc3545; margin-bottom: 4px; }
.danger-zone p { font-size: 12.5px; color: var(--text-muted); margin: 0 0 12px; }
.btn-retire {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 500;
    background: transparent; color: #dc3545;
    border: 1px solid #dc3545; cursor: pointer;
    transition: background .15s;
}
.btn-retire:hover { background: #fce8ea; }
</style>
@endpush

@section('content')

{{-- ── Page header ── --}}
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $car->model->brand->name }} {{ $car->model->name }}</h1>
        <p class="page-subtitle">
            {{ $car->code }} · {{ $car->plate_number }} · {{ $car->year }}
            @if($car->branch) · {{ $car->branch->name }} @endif
        </p>
    </div>
    <div class="page-actions">
        <a href="{{ route('fleet.cars.index') }}" class="btn-outline-fleet">
            <i class="bi bi-arrow-right"></i> Back
        </a>
        @can('cars.change_status')
        <button type="button" class="btn-outline-fleet"
                onclick="openStatusModal('{{ $car->id }}', '{{ $car->status }}')">
            <i class="bi bi-arrow-repeat"></i> Change status
        </button>
        @endcan
        @can('cars.edit')
        <a href="{{ route('fleet.cars.edit', $car) }}" class="btn-primary-fleet">
            <i class="bi bi-pencil"></i> Edit
        </a>
        @endcan
    </div>
</div>

{{-- ── Hero card ── --}}
<div class="car-hero">
    <div class="car-hero-visual">
        <i class="bi bi-car-front car-hero-icon"></i>
        <div class="car-hero-plate">{{ $car->plate_number }}</div>
        <div class="car-hero-code">{{ $car->code }}</div>
    </div>
    <div class="car-hero-body">
        {{-- Badges row --}}
        <div class="hero-badges">
            <span class="status-badge {{ $car->status }}">
                {{ ucfirst(str_replace('_', ' ', $car->status)) }}
            </span>
            @if($car->branch)
            <span class="hero-badge">
                <i class="bi bi-building"></i> {{ $car->branch->name }}
            </span>
            @endif
            @if($car->currentDriver)
            <span class="hero-badge">
                <i class="bi bi-person"></i> {{ $car->currentDriver->name }}
            </span>
            @endif
            @if($car->fuel_type)
            <span class="hero-badge">
                <i class="bi bi-fuel-pump"></i> {{ ucfirst($car->fuel_type) }}
            </span>
            @endif
        </div>

        {{-- Stats row --}}
        <div class="hero-stats-row">
            <div class="hero-stat">
                <div class="hero-stat-label"><i class="bi bi-speedometer2"></i> Odometer</div>
                <div class="hero-stat-value">{{ number_format($car->current_km) }} km</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-label"><i class="bi bi-calendar3"></i> Year</div>
                <div class="hero-stat-value">{{ $car->year }}</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-label"><i class="bi bi-palette"></i> Color</div>
                <div class="hero-stat-value">
                    @if($car->color_hex)
                        <span class="hero-color-dot" style="background:{{ $car->color_hex }}"></span>
                    @endif
                    {{ $car->color ?? '—' }}
                </div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-label"><i class="bi bi-shield-check"></i> Ownership</div>
                <div class="hero-stat-value">{{ ucfirst($car->ownership ?? 'Owned') }}</div>
            </div>
        </div>

        {{-- Identifiers + features --}}
        <div class="hero-identifiers">
            @if($car->chassis_number)
            <span><i class="bi bi-hash"></i> VIN <strong>{{ $car->chassis_number }}</strong></span>
            @endif
            @if($car->engine_number)
            <span><i class="bi bi-hash"></i> Engine <strong>{{ $car->engine_number }}</strong></span>
            @endif

            @if($car->has_camera)
                <span class="feature-pill"><i class="bi bi-camera-video"></i> Camera</span>
            @endif
            @if($car->has_sensors)
                <span class="feature-pill"><i class="bi bi-radar"></i> Sensors</span>
            @endif
            @if($car->features['gps'] ?? false)
                <span class="feature-pill"><i class="bi bi-geo-alt"></i> GPS</span>
            @endif
            @if($car->features['abs'] ?? false)
                <span class="feature-pill"><i class="bi bi-shield-fill-check"></i> ABS</span>
            @endif
        </div>
    </div>
</div>

{{-- ── Tabs ── --}}
@php $tab = request('tab', 'overview'); @endphp

<div class="show-tabs">
    @foreach([
        'overview'    => ['icon' => 'bi-activity',           'label' => 'Overview'],
        'documents'   => ['icon' => 'bi-file-earmark-text',  'label' => 'Documents'],
        'kroky'       => ['icon' => 'bi-geo-alt',             'label' => 'Kroky'],
        'inspections' => ['icon' => 'bi-clipboard-check',    'label' => 'Inspections'],
        'assignments' => ['icon' => 'bi-box-arrow-in-right', 'label' => 'Assignments'],
        'repairs'     => ['icon' => 'bi-wrench-adjustable',  'label' => 'Repairs'],
        'damages'     => ['icon' => 'bi-exclamation-triangle','label' => 'Damages'],
        'status'      => ['icon' => 'bi-arrow-repeat',       'label' => 'Status'],
        'audit'       => ['icon' => 'bi-clock-history',      'label' => 'Audit'],
    ] as $key => $t)
    <a href="{{ route('fleet.cars.show', $car) }}?tab={{ $key }}"
       class="show-tab {{ $tab === $key ? 'active' : '' }}">
        <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
    </a>
    @endforeach
</div>

{{-- ════════════════════════════════════════════
     TAB CONTENT
════════════════════════════════════════════ --}}

{{-- ── OVERVIEW ── --}}
@if($tab === 'overview')
<div class="overview-grid">

    {{-- Specifications --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-card-title">
                <div class="section-card-icon"><i class="bi bi-car-front"></i></div>
                Specifications
            </div>
        </div>
        <div class="section-card-body" style="padding:0 18px">
            <table class="detail-table">
                <tr><td>Brand</td><td>{{ $car->model->brand->name }}</td></tr>
                <tr><td>Model</td><td>{{ $car->model->name }}</td></tr>
                <tr><td>Year</td><td>{{ $car->year }}</td></tr>
                <tr><td>Transmission</td><td>{{ ucfirst($car->transmission ?? '—') }}</td></tr>
                <tr><td>Fuel</td><td>{{ ucfirst($car->fuel_type) }}</td></tr>
                <tr><td>Seats</td><td>{{ $car->seats ?? '—' }}</td></tr>
            </table>
        </div>
    </div>

    {{-- Assignment --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-card-title">
                <div class="section-card-icon"><i class="bi bi-person-badge"></i></div>
                Assignment
            </div>
        </div>
        <div class="section-card-body" style="padding:0 18px">
            <table class="detail-table">
                <tr>
                    <td>Branch</td>
                    <td>{{ $car->branch?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Current driver</td>
                    <td>{{ $car->currentDriver?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Contact</td>
                    <td>{{ $car->currentDriver?->phone ?? '—' }}</td>
                </tr>
                {{-- Active request — loaded from Operations when module exists --}}
                @if(method_exists($car, 'requests') && $car->relationLoaded('requests'))
                @php $activeReq = $car->requests->where('status', 'active')->first(); @endphp
                <tr>
                    <td>Active request</td>
                    <td>{{ $activeReq?->code ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Due back</td>
                    <td>{{ $activeReq?->due_date?->format('Y-m-d') ?? '—' }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Compliance --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-card-title">
                <div class="section-card-icon"><i class="bi bi-shield-check"></i></div>
                Compliance
            </div>
            @if($car->documents->count())
            <a href="{{ route('fleet.cars.show', $car) }}?tab=documents" class="btn-outline-fleet" style="font-size:12px;padding:5px 10px">
                <i class="bi bi-file-earmark-text"></i> Manage documents
            </a>
            @endif
        </div>
        <div class="section-card-body" style="padding:0 18px">
            @php
                $docTypes = ['license' => 'License', 'insurance' => 'Insurance', 'inspection' => 'Inspection'];
            @endphp
            @foreach($docTypes as $type => $label)
            @php
                $doc = $car->documents->where('type', $type)->sortByDesc('expiry_date')->first();
                $expired      = $doc && $doc->expiry_date && $doc->expiry_date->isPast();
                $expiringSoon = $doc && $doc->expiry_date && !$expired && $doc->expiry_date->isBefore(now()->addDays(30));
            @endphp
            <div class="compliance-row">
                <span class="compliance-label">{{ $label }}</span>
                @if(!$doc)
                    <span style="font-size:12.5px;color:var(--text-muted)">Not uploaded</span>
                @elseif(!$doc->expiry_date)
                    <span class="compliance-value">Uploaded</span>
                @elseif($expired)
                    <span class="compliance-value danger">Expired {{ $doc->expiry_date->diffForHumans() }}</span>
                @elseif($expiringSoon)
                    <span class="compliance-value warning">Expires in {{ $doc->expiry_date->diffInDays() }} days</span>
                @else
                    <span class="compliance-value">Valid · {{ $doc->expiry_date->format('Y-m-d') }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent activity + Financials --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

    {{-- Recent activity --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-card-title">
                <div class="section-card-icon"><i class="bi bi-activity"></i></div>
                Recent activity
            </div>
        </div>
        <div class="section-card-body">
            @if($car->statusHistory->count())
            <div class="activity-list">
                @foreach($car->statusHistory->sortByDesc('created_at')->take(5) as $entry)
                <div class="activity-item">
                    <span class="activity-dot status-dot {{ $entry->new_status }}" style="margin-top:5px"></span>
                    <div style="flex:1">
                        <div class="activity-action">car.status.changed</div>
                        <div class="activity-meta">
                            {{ $entry->old_status ? ucfirst(str_replace('_',' ',$entry->old_status)).' → ' : '' }}{{ ucfirst(str_replace('_',' ',$entry->new_status)) }}
                        </div>
                    </div>
                    <div style="text-align:left;flex-shrink:0">
                        <div style="font-size:11.5px;color:var(--text-secondary)">{{ $entry->changedBy?->name ?? 'System' }}</div>
                        <div class="activity-time">{{ $entry->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="tab-placeholder" style="padding:24px">
                <i class="bi bi-activity tab-placeholder-icon"></i>
                <p>No activity recorded yet.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Financials placeholder (populated when Finance/Operations modules exist) --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-card-title">
                <div class="section-card-icon"><i class="bi bi-graph-up-arrow"></i></div>
                Financials (last 90 days)
            </div>
        </div>
        <div class="section-card-body">
            @php
                // Will be populated when Operations / Finance modules are built
                $hasFinancials = false;
                try {
                    if (method_exists($car, 'repairOrders') && class_exists('\Modules\Maintenance\Models\RepairOrder')) {
                        $repairCost = $car->repairOrders()
                            ->where('created_at', '>=', now()->subDays(90))
                            ->sum('total_cost');
                        $hasFinancials = true;
                    }
                } catch (\Throwable $e) { $repairCost = 0; }
            @endphp

            @if($hasFinancials)
            <div class="fin-cards">
                <div class="fin-card repairs">
                    <div class="fin-card-label">Repairs</div>
                    <div class="fin-card-value">EGP {{ number_format($repairCost) }}</div>
                </div>
                <div class="fin-card revenue">
                    <div class="fin-card-label">Revenue</div>
                    <div class="fin-card-value">—</div>
                </div>
                <div class="fin-card util">
                    <div class="fin-card-label">Utilisation</div>
                    <div class="fin-card-value">—</div>
                </div>
            </div>
            @else
            <div class="fin-cards">
                <div class="fin-card repairs"><div class="fin-card-label">Repairs</div><div class="fin-card-value" style="font-size:15px;color:var(--text-muted)">—</div></div>
                <div class="fin-card revenue"><div class="fin-card-label">Revenue</div><div class="fin-card-value" style="font-size:15px;color:var(--text-muted)">—</div></div>
                <div class="fin-card util"><div class="fin-card-label">Utilisation</div><div class="fin-card-value" style="font-size:15px;color:var(--text-muted)">—</div></div>
            </div>
            <p style="font-size:12px;color:var(--text-muted);margin:0">Financial data will appear here once Operations and Maintenance modules are active.</p>
            @endif

            <table class="detail-table" style="margin-top:12px">
                <tr>
                    <td>Total km driven</td>
                    <td>{{ number_format($car->current_km) }} km</td>
                </tr>
                <tr>
                    <td>Trips completed</td>
                    <td>
                        @php
                            $tripCount = 0;
                            try {
                                if (method_exists($car, 'requests')) {
                                    $tripCount = $car->requests()->where('status','completed')->count();
                                }
                            } catch (\Throwable $e) {}
                        @endphp
                        {{ $tripCount ?: '—' }}
                    </td>
                </tr>
                <tr>
                    <td>Damage reports</td>
                    <td>
                        @php
                            $dmgCount = 0;
                            try {
                                if (method_exists($car, 'damages')) {
                                    $dmgCount = $car->damages()->count();
                                }
                            } catch (\Throwable $e) {}
                        @endphp
                        {{ $dmgCount ?: '—' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endif

{{-- ── DOCUMENTS ── --}}
@if($tab === 'documents')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-file-earmark-text"></i></div>
            Documents
        </div>
        @if($car->documents->count())
        <a href="#" class="btn-outline-fleet" style="font-size:12px;padding:5px 10px">
            <i class="bi bi-download"></i> Download all
        </a>
        @endif
    </div>
    @if($car->documents->count())
    <table class="data-table">
        <thead>
            <tr>
                <th>Name / File</th>
                <th>Type</th>
                <th>Expires</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($car->documents->sortBy('type') as $doc)
            @php
                $expired      = $doc->expiry_date && $doc->expiry_date->isPast();
                $expiringSoon = $doc->expiry_date && !$expired && $doc->expiry_date->isBefore(now()->addDays(30));
                $docLabel     = ucfirst(str_replace('_',' ', basename($doc->file_path)));
                $badgeClass   = $expired ? 'badge-expired' : ($expiringSoon ? 'badge-expiring' : 'badge-valid');
                $badgeText    = $expired ? 'Expired' : ($expiringSoon ? 'Expiring soon' : 'Valid');
                $badgeIcon    = $expired ? 'bi-exclamation-triangle' : ($expiringSoon ? 'bi-clock' : 'bi-check-circle');
            @endphp
            <tr>
                <td style="font-weight:600">{{ $docLabel }}</td>
                <td class="muted">{{ ucfirst($doc->type) }}</td>
                <td class="muted">{{ $doc->expiry_date?->format('Y-m-d') ?? '—' }}</td>
                <td>
                    @if($doc->expiry_date)
                    <span class="inline-badge {{ $badgeClass }}">
                        <i class="bi {{ $badgeIcon }}"></i> {{ $badgeText }}
                    </span>
                    @else
                    <span style="color:var(--text-muted);font-size:12.5px">—</span>
                    @endif
                </td>
                <td style="text-align:right">
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                       class="btn-outline-fleet" style="font-size:12px;padding:4px 9px">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="tab-placeholder">
        <i class="bi bi-file-earmark-text tab-placeholder-icon"></i>
        <h6>No documents uploaded</h6>
        <p>Upload the vehicle license, insurance, and inspection certificate from the Edit screen.</p>
    </div>
    @endif
</div>
@endif

{{-- ── KROKY ── --}}
@if($tab === 'kroky')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-geo-alt"></i></div>
            Kroky · damage diagram
        </div>
        {{-- Button will link to kroky module once built --}}
        @php $krokyExists = false; try { $krokyExists = class_exists('\Modules\Kroky\Models\KrokyVersion'); } catch(\Throwable $e) {} @endphp
        @if($krokyExists)
        <a href="#" class="btn-primary-fleet" style="font-size:12px;padding:6px 12px">
            <i class="bi bi-pencil"></i> New kroky
        </a>
        @endif
    </div>
    @php
        $latestKroky = null;
        try { $latestKroky = $car->latestKroky ?? null; } catch(\Throwable $e) {}
    @endphp
    @if($latestKroky)
        <div class="section-card-body">
            <div style="display:grid;grid-template-columns:1fr 220px;gap:16px;align-items:start">
                <div style="min-height:280px;border:1px dashed var(--border);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:13px">
                    Interactive car diagram (placeholder)
                </div>
                <div style="display:flex;flex-direction:column;gap:10px">
                    <div class="fin-card" style="background:#fce8ea;border-color:#f5c2c7">
                        <div class="fin-card-label">Open marks</div>
                        <div class="fin-card-value" style="color:#dc3545;font-size:22px">
                            {{ $latestKroky->points?->where('status','open')->count() ?? 0 }}
                        </div>
                    </div>
                    <div class="fin-card" style="background:#e1f5ee;border-color:#a3dfc8">
                        <div class="fin-card-label">Repaired</div>
                        <div class="fin-card-value" style="color:#1d9e75;font-size:22px">
                            {{ $latestKroky->points?->where('status','repaired')->count() ?? 0 }}
                        </div>
                    </div>
                    <div class="fin-card" style="background:#eef3fc;border-color:rgba(30,100,200,.2)">
                        <div class="fin-card-label">Last version</div>
                        <div class="fin-card-value" style="color:var(--accent-dark);font-size:15px">
                            v{{ $latestKroky->version_number }} · {{ $latestKroky->created_at?->format('Y-m-d') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="tab-placeholder">
            <i class="bi bi-geo-alt tab-placeholder-icon"></i>
            <h6>No kroky recorded yet</h6>
            <p>Kroky (damage diagrams) will appear here once the Kroky module is active and the first diagram is created for this car.</p>
        </div>
    @endif
</div>
@endif

{{-- ── INSPECTIONS ── --}}
@if($tab === 'inspections')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-clipboard-check"></i></div>
            Inspections
        </div>
    </div>
    @php
        $inspections = collect();
        try {
            if (method_exists($car, 'inspections') && class_exists('\Modules\Kroky\Models\Inspection')) {
                $inspections = $car->inspections()->with('inspector')->latest()->get();
            }
        } catch(\Throwable $e) {}
    @endphp
    @if($inspections->count())
    <table class="data-table">
        <thead>
            <tr><th>Ref</th><th>Date</th><th>Type</th><th>Result</th><th>Inspector</th></tr>
        </thead>
        <tbody>
            @foreach($inspections as $ins)
            <tr>
                <td style="font-weight:600;font-family:monospace">{{ $ins->code }}</td>
                <td class="muted">{{ $ins->inspected_at?->format('Y-m-d') }}</td>
                <td>{{ ucfirst($ins->type) }}</td>
                <td>
                    <span class="inline-badge {{ $ins->result === 'pass' ? 'badge-pass' : 'badge-fail' }}">
                        <i class="bi {{ $ins->result === 'pass' ? 'bi-check-circle' : 'bi-exclamation-triangle' }}"></i>
                        {{ ucfirst($ins->result) }}
                    </span>
                </td>
                <td>{{ $ins->inspector?->name ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="tab-placeholder">
        <i class="bi bi-clipboard-check tab-placeholder-icon"></i>
        <h6>No inspections yet</h6>
        <p>Inspection records will appear here once the Inspections module is active.</p>
    </div>
    @endif
</div>
@endif

{{-- ── ASSIGNMENTS ── --}}
@if($tab === 'assignments')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-box-arrow-in-right"></i></div>
            Assignments
        </div>
    </div>
    @php
        $assignments = collect();
        try {
            if (method_exists($car, 'requests') && class_exists('\Modules\Operations\Models\Request')) {
                $assignments = $car->requests()->with('customer')->latest()->get();
            }
        } catch(\Throwable $e) {}
    @endphp
    @if($assignments->count())
    <table class="data-table">
        <thead>
            <tr><th>Request</th><th>Customer</th><th>From</th><th>To</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($assignments as $req)
            <tr>
                <td style="font-weight:600;font-family:monospace">{{ $req->code }}</td>
                <td>{{ $req->customer?->name ?? '—' }}</td>
                <td class="muted">{{ $req->start_date?->format('Y-m-d') ?? '—' }}</td>
                <td class="muted">{{ $req->due_date?->format('Y-m-d') ?? '—' }}</td>
                <td>
                    <span class="inline-badge {{ $req->status === 'active' ? 'badge-active' : 'badge-completed' }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="tab-placeholder">
        <i class="bi bi-box-arrow-in-right tab-placeholder-icon"></i>
        <h6>No assignments yet</h6>
        <p>Assignment history will appear here once the Operations module is active and requests are linked to this car.</p>
    </div>
    @endif
</div>
@endif

{{-- ── REPAIRS ── --}}
@if($tab === 'repairs')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-wrench-adjustable"></i></div>
            Repair orders
        </div>
    </div>
    @php
        $repairs = collect();
        try {
            if (method_exists($car, 'repairOrders') && class_exists('\Modules\Maintenance\Models\RepairOrder')) {
                $repairs = $car->repairOrders()->latest()->get();
            }
        } catch(\Throwable $e) {}
    @endphp
    @if($repairs->count())
    <table class="data-table">
        <thead>
            <tr><th>Ref</th><th>Opened</th><th>Closed</th><th>Summary</th><th style="text-align:left">Cost</th></tr>
        </thead>
        <tbody>
            @foreach($repairs as $ro)
            <tr>
                <td style="font-weight:600;font-family:monospace">{{ $ro->code }}</td>
                <td class="muted">{{ $ro->opened_at?->format('Y-m-d') }}</td>
                <td class="muted">{{ $ro->closed_at?->format('Y-m-d') ?? '—' }}</td>
                <td>{{ $ro->summary ?? '—' }}</td>
                <td style="text-align:left;font-variant-numeric:tabular-nums">
                    @if($ro->total_cost) EGP {{ number_format($ro->total_cost) }} @else — @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="tab-placeholder">
        <i class="bi bi-wrench-adjustable tab-placeholder-icon"></i>
        <h6>No repair orders yet</h6>
        <p>Repair history will appear here once the Maintenance module is active.</p>
    </div>
    @endif
</div>
@endif

{{-- ── DAMAGES ── --}}
@if($tab === 'damages')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-exclamation-triangle"></i></div>
            Damage reports
        </div>
    </div>
    @php
        $damages = collect();
        try {
            if (method_exists($car, 'damages') && class_exists('\Modules\Kroky\Models\DamageReport')) {
                $damages = $car->damages()->latest()->get();
            }
        } catch(\Throwable $e) {}
    @endphp
    @if($damages->count())
    <table class="data-table">
        <thead>
            <tr><th>Ref</th><th>Date</th><th>Area</th><th>Severity</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($damages as $dmg)
            @php
                $sevClass = match($dmg->severity ?? '') { 'moderate' => 'badge-moderate', 'severe' => 'badge-severe', default => 'badge-minor' };
                $stClass  = match($dmg->status ?? '')  { 'repaired' => 'badge-repaired', default => 'badge-logged' };
            @endphp
            <tr>
                <td style="font-weight:600;font-family:monospace">{{ $dmg->code }}</td>
                <td class="muted">{{ $dmg->reported_at?->format('Y-m-d') }}</td>
                <td>{{ $dmg->area ?? '—' }}</td>
                <td><span class="inline-badge {{ $sevClass }}">{{ ucfirst($dmg->severity ?? '—') }}</span></td>
                <td><span class="inline-badge {{ $stClass }}">{{ ucfirst($dmg->status ?? '—') }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="tab-placeholder">
        <i class="bi bi-exclamation-triangle tab-placeholder-icon"></i>
        <h6>No damage reports</h6>
        <p>Damage reports will appear here once the Kroky / Damage module is active.</p>
    </div>
    @endif
</div>
@endif

{{-- ── STATUS ── --}}
@if($tab === 'status')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-arrow-repeat"></i></div>
            Status history
        </div>
        <span style="font-size:12px;color:var(--text-muted)">All transitions per BR-020 with override reasons.</span>
    </div>
    <div class="section-card-body">
        @if($car->statusHistory->count())
        <div class="status-timeline">
            @foreach($car->statusHistory->sortByDesc('created_at') as $entry)
            <div class="status-item">
                <span class="status-dot {{ $entry->new_status }}"></span>
                <div class="status-item-body">
                    <div class="status-transition">
                        @if($entry->old_status)
                            <span class="status-badge {{ $entry->old_status }}" style="font-size:11px;padding:2px 8px">
                                {{ ucfirst(str_replace('_',' ',$entry->old_status)) }}
                            </span>
                            <i class="bi bi-arrow-right status-arrow"></i>
                        @endif
                        <span class="status-badge {{ $entry->new_status }}" style="font-size:11px;padding:2px 8px">
                            {{ ucfirst(str_replace('_',' ',$entry->new_status)) }}
                        </span>
                        @if($entry->is_override)
                            <span class="override-tag">override</span>
                        @endif
                        <span class="status-by">· by {{ $entry->changedBy?->name ?? 'System' }}</span>
                    </div>
                    @if($entry->reason)
                        <div class="status-reason">"{{ $entry->reason }}"</div>
                    @endif
                </div>
                <div style="font-size:12px;color:var(--text-muted);white-space:nowrap;flex-shrink:0">
                    {{ $entry->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="tab-placeholder" style="padding:32px">
            <i class="bi bi-arrow-repeat tab-placeholder-icon"></i>
            <p>No status transitions recorded yet.</p>
        </div>
        @endif
    </div>
</div>
@endif

{{-- ── AUDIT ── --}}
@if($tab === 'audit')
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-title">
            <div class="section-card-icon"><i class="bi bi-clock-history"></i></div>
            Audit trail
        </div>
    </div>
    @php
        $auditEntries = collect();
        // Build audit from status history (always available)
        // When LogService / dedicated audit model exists, merge here
        $auditEntries = $car->statusHistory->map(fn($h) => [
            'timestamp' => $h->created_at,
            'actor'     => $h->changedBy?->name ?? 'System',
            'action'    => 'car.status.changed',
            'detail'    => ($h->old_status ?? 'created') . ' → ' . $h->new_status,
        ])->sortByDesc('timestamp');

        // Try to merge with a generic LogService if it exists
        try {
            if (class_exists('\Modules\Core\Models\ActivityLog')) {
                $logs = \Modules\Core\Models\ActivityLog::where('subject_type', Car::class)
                    ->where('subject_id', $car->id)
                    ->latest()
                    ->get()
                    ->map(fn($l) => [
                        'timestamp' => $l->created_at,
                        'actor'     => $l->causer?->name ?? 'System',
                        'action'    => $l->event,
                        'detail'    => $l->description ?? '',
                    ]);
                $auditEntries = $auditEntries->merge($logs)->sortByDesc('timestamp');
            }
        } catch(\Throwable $e) {}
    @endphp
    @if($auditEntries->count())
    <table class="data-table">
        <thead>
            <tr><th>Timestamp</th><th>Actor</th><th>Action</th><th>Detail</th></tr>
        </thead>
        <tbody>
            @foreach($auditEntries as $entry)
            <tr>
                <td class="muted" style="white-space:nowrap">
                    {{ $entry['timestamp'] instanceof \Carbon\Carbon ? $entry['timestamp']->format('Y-m-d H:i') : $entry['timestamp'] }}
                </td>
                <td>{{ $entry['actor'] }}</td>
                <td><span class="audit-action">{{ $entry['action'] }}</span></td>
                <td class="audit-detail">{{ $entry['detail'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="tab-placeholder">
        <i class="bi bi-clock-history tab-placeholder-icon"></i>
        <h6>No audit entries yet</h6>
        <p>All system actions on this car will be logged here.</p>
    </div>
    @endif
</div>
@endif

{{-- ── Danger zone (always visible) ── --}}
@can('cars.delete')
<div class="danger-zone">
    <div class="danger-zone-title">Danger zone</div>
    <p>Retiring a car removes it from availability. <a href="{{ route('fleet.cars.show', $car) }}?tab=audit" style="color:var(--accent-dark)">This action is audited.</a></p>
    <button type="button" class="btn-retire" onclick="confirmRetire('{{ $car->id }}', '{{ $car->plate_number }}')">
        <i class="bi bi-power"></i> Retire car
    </button>
</div>
@endcan

{{-- ── Change Status Modal ── --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border)">
            <div class="modal-header" style="border-bottom:1px solid var(--border);padding:16px 20px">
                <h6 class="modal-title" style="font-weight:600">Change Car Status</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body" style="padding:20px">
                    <div style="margin-bottom:14px">
                        <label style="font-size:13px;font-weight:500;margin-bottom:6px;display:block">New Status</label>
                        <select name="status" style="width:100%;height:40px;border:1px solid var(--border);border-radius:var(--radius-sm);padding:0 12px;font-size:13px;outline:none">
                            <option value="ready">Ready</option>
                            <option value="not_ready">Not Ready</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:13px;font-weight:500;margin-bottom:6px;display:block">
                            Reason <span style="color:#dc3545">*</span>
                        </label>
                        <textarea name="reason" rows="3"
                            style="width:100%;border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px;font-size:13px;outline:none;resize:none"
                            placeholder="Explain why the status is changing..."
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 20px;gap:8px">
                    <button type="button" class="btn-outline-fleet" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-fleet">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Retire Confirm Modal ── --}}
<div class="modal fade" id="retireModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content" style="border-radius:var(--radius);border:1px solid var(--border)">
            <div class="modal-body" style="padding:28px 24px;text-align:center">
                <div style="width:52px;height:52px;border-radius:50%;background:#fce8ea;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:22px;color:#dc3545">
                    <i class="bi bi-power"></i>
                </div>
                <h6 style="font-weight:700;margin-bottom:8px">Retire this car?</h6>
                <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px">
                    Car <strong id="retirePlate"></strong> will be marked as retired and removed from all operations.
                </p>
                <div style="display:flex;gap:10px;justify-content:center">
                    <button class="btn-outline-fleet" data-bs-dismiss="modal">Cancel</button>
                    <form id="retireForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-primary-fleet" style="background:#dc3545">Retire</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openStatusModal(carId, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = `/fleet/cars/${carId}/status`;
    form.querySelector('select[name=status]').value = currentStatus;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

function confirmRetire(carId, plate) {
    document.getElementById('retirePlate').textContent = plate;
    document.getElementById('retireForm').action = `/fleet/cars/${carId}`;
    new bootstrap.Modal(document.getElementById('retireModal')).show();
}
</script>
@endpush
