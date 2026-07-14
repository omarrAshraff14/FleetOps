@extends('layouts.app')

@section('title', 'Cars')

@push('styles')
<style>
    /* ── Stats Grid ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }
    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: var(--shadow-sm);
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text-primary); line-height: 1.1; }

    /* ── Filters ── */
    .filters-bar {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 16px;
        margin-bottom: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        box-shadow: var(--shadow-sm);
    }
    .filter-search {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 320px;
    }
    .filter-search input {
        width: 100%;
        height: 36px;
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 0 36px 0 12px;
        font-size: 13px;
        outline: none;
        transition: border .2s, box-shadow .2s;
    }
    .filter-search input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(13,110,253,.1);
    }
    .filter-search i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 14px;
    }
    .filter-select {
        height: 36px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0 32px 0 12px;
        font-size: 13px;
        background: var(--card-bg);
        color: var(--text-primary);
        outline: none;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%236b7a8d' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: left 10px center;
        transition: border .2s;
    }
    .filter-select:focus { border-color: var(--primary); }

    .filter-btn-more {
        height: 36px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0 14px;
        font-size: 13px;
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background .15s;
    }
    .filter-btn-more:hover { background: var(--body-bg); }

    /* ── Table ── */
    .table-wrap {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: visible;
    }
    .fleet-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    .fleet-table thead tr {
        border-bottom: 1px solid var(--border);
    }
    .fleet-table thead th {
        padding: 11px 14px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-align: right;
        white-space: nowrap;
        background: var(--body-bg);
    }
    .fleet-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .12s;
    }
    .fleet-table tbody tr:last-child { border-bottom: none; }
    .fleet-table tbody tr:hover { background: #f8f9fb; }
    .fleet-table td {
        padding: 12px 14px;
        color: var(--text-primary);
        vertical-align: middle;
        text-align: right;
    }

    /* Code + Plate */
    .car-code { font-size: 11px; color: var(--text-muted); font-weight: 500; }
    .car-plate { font-size: 14px; font-weight: 700; color: var(--text-primary); }

    /* Brand/Model */
    .car-brand { font-weight: 600; }
    .car-model { font-size: 12px; color: var(--text-muted); }

    /* Color dot */
    .color-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        border: 1.5px solid rgba(0,0,0,.12);
        vertical-align: middle;
        margin-left: 6px;
    }

    /* Fuel badge */
    .fuel-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11.5px;
        font-weight: 500;
        background: var(--body-bg);
        border: 1px solid var(--border);
        color: var(--text-secondary);
    }

    /* Features icons */
    .features-icons { display: flex; gap: 5px; align-items: center; }
    .feat-icon { font-size: 15px; color: var(--text-muted); }
    .feat-icon.active { color: var(--primary); }

    /* Odometer */
    .odometer { font-variant-numeric: tabular-nums; }

    /* Driver avatar */
    .driver-avatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        font-size: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: 6px;
    }

    /* Actions dropdown */
    .actions-btn {
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-size: 18px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        line-height: 1;
        transition: background .15s, color .15s;
    }
    .actions-btn:hover { background: var(--body-bg); color: var(--text-primary); }

   .dropdown-menu-fleet{
    display:none;
    position:absolute;
    top:100%;
    right:0;
    left:auto;
    margin-top:4px;
    z-index:9999;
}
    .dropdown-item-fleet {
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-primary);
        cursor: pointer;
        text-decoration: none;
        transition: background .12s;
    }
    .dropdown-item-fleet:hover { background: var(--body-bg); }
    .dropdown-item-fleet.danger { color: #dc3545; }
    .dropdown-item-fleet i { font-size: 15px; width: 16px; }
    .dropdown-divider-fleet {
        height: 1px;
        background: var(--border);
        margin: 4px 0;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 48px; margin-bottom: 12px; display: block; opacity: .4; }
    .empty-state h6 { font-size: 15px; color: var(--text-secondary); margin-bottom: 6px; }

    /* Pagination */
    .pagination-wrap {
        padding: 14px 16px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Cars</h1>
        <p class="page-subtitle">All fleet vehicles across branches — statuses reflect BR-020 rules.</p>
    </div>
    <div class="page-actions">
        <a href="#" class="btn-outline-fleet">
            <i class="bi bi-download"></i> Export
        </a>
        @can('cars.create')
        <a href="{{ route('fleet.cars.create') }}" class="btn-primary-fleet">
            <i class="bi bi-plus"></i> Add car
        </a>
        @endcan
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eef2f7; color:#4a6a82">
            <i class="bi bi-car-front"></i>
        </div>
        <div>
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--status-ready-bg); color:var(--status-ready)">
            <i class="bi bi-check-circle"></i>
        </div>
        <div>
            <div class="stat-label">Ready</div>
            <div class="stat-value">{{ $stats['ready'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--status-in-use-bg); color:var(--status-in-use)">
            <i class="bi bi-truck"></i>
        </div>
        <div>
            <div class="stat-label">In Use</div>
            <div class="stat-value">{{ $stats['in_use'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--status-not-ready-bg); color:var(--status-not-ready)">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        <div>
            <div class="stat-label">Not Ready</div>
            <div class="stat-value">{{ $stats['not_ready'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--status-maintenance-bg); color:var(--status-maintenance)">
            <i class="bi bi-wrench"></i>
        </div>
        <div>
            <div class="stat-label">Maintenance</div>
            <div class="stat-value">{{ $stats['maintenance'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--status-retired-bg); color:var(--status-retired)">
            <i class="bi bi-power"></i>
        </div>
        <div>
            <div class="stat-label">Retired</div>
            <div class="stat-value">{{ $stats['retired'] }}</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('fleet.cars.index') }}" id="filterForm">
<div class="filters-bar">
    <div class="filter-search">
        <i class="bi bi-search"></i>
        <input type="text" name="search"
               value="{{ request('search') }}"
               placeholder="Search plate, code, brand..."
               id="searchInput">
    </div>

    <select name="status" class="filter-select" onchange="$('#filterForm').submit()">
        <option value="">All statuses</option>
        @foreach(['ready','in_use','not_ready','maintenance','retired'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $s)) }}
            </option>
        @endforeach
    </select>

    <select name="branch_id" class="filter-select" onchange="$('#filterForm').submit()">
        <option value="">All branches</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>

    <select name="brand_id" class="filter-select" onchange="$('#filterForm').submit()">
        <option value="">All brands</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>

    <button type="button" class="filter-btn-more">
        <i class="bi bi-funnel"></i> More
    </button>
</div>
</form>

{{-- Table --}}
<div class="table-wrap">
    @if($cars->count())
    <table class="fleet-table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Plate</th>
                <th>Brand / Model</th>
                <th>Year</th>
                <th>Color</th>
                <th>Fuel</th>
                <th>Odometer</th>
                <th>Features</th>
                <th>Branch</th>
                <th>Driver</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cars as $car)
            <tr>
                {{-- Code --}}
                <td><span class="car-code">{{ $car->code ?? '—' }}</span></td>

                {{-- Plate --}}
                <td><span class="car-plate">{{ $car->plate_number }}</span></td>

                {{-- Brand / Model --}}
                <td>
                    <div class="car-brand">{{ $car->model->brand->name }}</div>
                    <div class="car-model">{{ $car->model->name }}</div>
                </td>

                {{-- Year --}}
                <td>{{ $car->year }}</td>

                {{-- Color --}}
                <td>
                    <span class="color-dot" style="background: {{ $car->color_hex ?? '#ccc' }}"></span>
                    {{ $car->color }}
                </td>

                {{-- Fuel --}}
                <td>
                    <span class="fuel-badge">
                        {{ ucfirst($car->fuel_type) }}
                    </span>
                </td>

                {{-- Odometer --}}
                <td class="odometer">
                    {{ number_format($car->current_km) }} km
                </td>

                {{-- Features --}}
                <td>
                    <div class="features-icons">
                        <i class="bi bi-camera-video feat-icon {{ $car->has_camera ? 'active' : '' }}"
                           title="Camera"></i>
                        <i class="bi bi-sign-turn-right feat-icon {{ ($car->features['abs'] ?? false) ? 'active' : '' }}"
                           title="ABS"></i>
                        <i class="bi bi-pencil feat-icon {{ ($car->features['bluetooth'] ?? false) ? 'active' : '' }}"
                           title="Bluetooth"></i>
                        <i class="bi bi-chat-square feat-icon {{ $car->has_sensors ? 'active' : '' }}"
                           title="Sensors"></i>
                    </div>
                </td>

                {{-- Branch --}}
                <td>
                    <div>{{ $car->branch->name }}</div>
                    @if($car->branch->address)
                        <div style="font-size:11px;color:var(--text-muted)">{{ Str::limit($car->branch->address, 15) }}</div>
                    @endif
                </td>

                {{-- Driver --}}
                <td>
                    @if($car->currentDriver)
                        <div style="display:flex;align-items:center;gap:6px">
                            <div class="driver-avatar">
                                {{ strtoupper(substr($car->currentDriver->name, 0, 2)) }}
                            </div>
                            <span>{{ Str::limit($car->currentDriver->name, 14) }}</span>
                        </div>
                    @else
                        <span style="color:var(--text-muted)">—</span>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    <span class="status-badge {{ $car->status }}">
                        {{ ucfirst(str_replace('_', ' ', $car->status)) }}
                    </span>
                </td>

                {{-- Actions --}}
                <td>
                    <div class="dropdown">
                        <button class="actions-btn dropdown-toggle-custom" data-car="{{ $car->id }}">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-fleet" id="menu-{{ $car->id }}" style="display:none;position:absolute;z-index:200">
                            <li>
                                <a href="{{ route('fleet.cars.show', $car) }}" class="dropdown-item-fleet">
                                    <i class="bi bi-eye"></i> View details
                                </a>
                            </li>
                            @can('cars.edit')
                            <li>
                                <a href="{{ route('fleet.cars.edit', $car) }}" class="dropdown-item-fleet">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </li>
                            @endcan
                            @can('cars.change_status')
                            <li>
                                <button class="dropdown-item-fleet w-100 border-0 bg-transparent text-start"
                                        onclick="openStatusModal('{{ $car->id }}', '{{ $car->status }}')">
                                    <i class="bi bi-arrow-repeat"></i> Change status
                                </button>
                            </li>
                            @endcan
                            <li>
                                <a href="#" class="dropdown-item-fleet">
                                    <i class="bi bi-file-earmark-text"></i> Documents
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item-fleet">
                                    <i class="bi bi-layers"></i> Kroky
                                </a>
                            </li>
                            @can('cars.delete')
                            <li><div class="dropdown-divider-fleet"></div></li>
                            <li>
                                <button class="dropdown-item-fleet danger w-100 border-0 bg-transparent text-start"
                                        onclick="confirmRetire('{{ $car->id }}', '{{ $car->plate_number }}')">
                                    <i class="bi bi-power"></i> Retire car
                                </button>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="pagination-wrap">
        <span>Showing {{ $cars->firstItem() }}–{{ $cars->lastItem() }} of {{ $cars->total() }} cars</span>
      {{ $cars->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>

    @else
    <div class="empty-state">
        <i class="bi bi-car-front"></i>
        <h6>No cars found</h6>
        <p style="font-size:13px">Try adjusting filters or add a new car.</p>
        @can('cars.create')
        <a href="{{ route('fleet.cars.create') }}" class="btn-primary-fleet" style="margin-top:10px;display:inline-flex">
            <i class="bi bi-plus"></i> Add first car
        </a>
        @endcan
    </div>
    @endif
</div>


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
                        <select name="status" class="filter-select" style="width:100%;height:40px">
                            <option value="ready">Ready</option>
                            <option value="not_ready">Not Ready</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:13px;font-weight:500;margin-bottom:6px;display:block">Reason <span style="color:#dc3545">*</span></label>
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
// ── Dropdown toggle ──
$(document).on('click', '.dropdown-toggle-custom', function (e) {
    e.stopPropagation();

    $('.dropdown-menu-fleet').hide();

    $(this)
        .siblings('.dropdown-menu-fleet')
        .toggle();
});

$(document).on('click', function () {
    $('.dropdown-menu-fleet').hide();
});

$(document).on('click', function() {
    $('.dropdown-menu-fleet').hide();
});

// ── Search debounce ──
let searchTimer;
$('#searchInput').on('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => $('#filterForm').submit(), 400);
});

// ── Change Status Modal ──
function openStatusModal(carId, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = `/fleet/cars/${carId}/status`;
    form.querySelector('select[name=status]').value = currentStatus;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

// ── Retire Modal ──
function confirmRetire(carId, plate) {
    document.getElementById('retirePlate').textContent = plate;
    document.getElementById('retireForm').action = `/fleet/cars/${carId}`;
    new bootstrap.Modal(document.getElementById('retireModal')).show();
}
</script>
@endpush
