@extends('layouts.app')

@section('title', 'Edit — ' . $car->plate_number)

@push('styles')
<style>
    /* ── Two-column layout ── */
    .create-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .create-layout { grid-template-columns: 1fr; }
    }

    /* ── Section cards ── */
    .form-section {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 16px;
        overflow: hidden;
    }
    .form-section-header {
        padding: 16px 20px 14px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .section-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; flex-shrink: 0;
        background: var(--body-bg);
        color: var(--accent-dark);
    }
    .section-title    { font-size: 14px; font-weight: 600; color: var(--text-primary); margin: 0; }
    .section-subtitle { font-size: 12px; color: var(--text-muted); margin: 2px 0 0; }
    .form-section-body { padding: 20px; }

    /* ── Form grid ── */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }
    .form-row.full { grid-template-columns: 1fr; }
    .form-row:last-child { margin-bottom: 0; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }

    label.field-label { font-size: 12.5px; font-weight: 500; color: var(--text-secondary); }
    label.field-label .req { color: #dc3545; margin-right: 2px; }

    .form-input,
    .form-select,
    .form-textarea {
        height: 38px;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 0 12px;
        font-size: 13px;
        color: var(--text-primary);
        background: var(--card-bg);
        outline: none;
        transition: border .2s, box-shadow .2s;
        width: 100%;
    }
    .form-input::placeholder { color: var(--text-muted); font-family: monospace; font-size: 12px; }
    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: var(--accent-dark);
        box-shadow: 0 0 0 3px rgba(30,100,200,.08);
    }
    .form-input.is-invalid,
    .form-select.is-invalid { border-color: #dc3545; }
    .field-hint  { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    .field-error { font-size: 11px; color: #dc3545; margin-top: 2px; }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%236b7a8d' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: left 10px center;
        padding-left: 32px;
    }
    .form-textarea { height: auto; padding: 10px 12px; resize: vertical; }

    .input-with-icon { position: relative; }
    .input-with-icon .form-input { padding-right: 34px; }
    .input-with-icon i {
        position: absolute; right: 11px; top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted); font-size: 14px; pointer-events: none;
    }

    /* ── Color picker ── */
    .color-row { display: flex; align-items: center; gap: 10px; }
    .color-swatch {
        width: 38px; height: 38px; border-radius: var(--radius-sm);
        border: 1px solid var(--border); cursor: pointer;
        flex-shrink: 0; padding: 0; overflow: hidden; position: relative;
    }
    .color-swatch input[type=color] {
        width: 100%; height: 100%; border: none; padding: 0; cursor: pointer; opacity: 0; position: absolute;
    }
    .color-preview { width: 100%; height: 100%; border-radius: 5px; pointer-events: none; }

    /* ── Feature toggles ── */
    .features-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .feature-toggle {
        border: 1px solid var(--border); border-radius: var(--radius-sm);
        padding: 12px 14px; display: flex; align-items: center; gap: 10px;
        cursor: pointer; transition: border-color .15s, background .15s; user-select: none;
    }
    .feature-toggle:hover { background: var(--body-bg); }
    .feature-toggle.is-on { border-color: var(--accent-dark); background: #eef3fc; }
    .feature-icon {
        width: 32px; height: 32px; border-radius: 8px;
        background: var(--body-bg); display: flex; align-items: center;
        justify-content: center; font-size: 15px; color: var(--text-muted);
        flex-shrink: 0; transition: background .15s, color .15s;
    }
    .feature-toggle.is-on .feature-icon { background: var(--accent-dark); color: #fff; }
    .feature-text { flex: 1; }
    .feature-name { font-size: 13px; font-weight: 500; color: var(--text-primary); }
    .feature-desc { font-size: 11px; color: var(--text-muted); margin-top: 1px; }
    .toggle-switch { position: relative; width: 34px; height: 18px; flex-shrink: 0; }
    .toggle-switch input { display: none; }
    .toggle-track {
        display: block; width: 34px; height: 18px; border-radius: 9px;
        background: #d1d5db; transition: background .2s; cursor: pointer;
    }
    .toggle-thumb {
        position: absolute; top: 2px; left: 2px; width: 14px; height: 14px;
        border-radius: 50%; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2);
        transition: transform .2s; pointer-events: none;
    }
    .toggle-switch input:checked ~ .toggle-track { background: var(--accent-dark); }
    .toggle-switch input:checked ~ .toggle-thumb { transform: translateX(16px); }

    /* ── Documents ── */
    /* Existing docs list */
    .existing-docs { margin-bottom: 14px; }
    .existing-doc-row {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px;
        border: 1px solid var(--border); border-radius: var(--radius-sm);
        background: var(--body-bg);
        margin-bottom: 6px;
        font-size: 13px;
    }
    .existing-doc-row i.doc-type-icon { font-size: 16px; color: var(--accent-dark); flex-shrink: 0; }
    .doc-type-tag {
        display: inline-block; padding: 2px 8px; border-radius: 4px;
        font-size: 11px; font-weight: 600; text-transform: uppercase;
        background: var(--card-bg); border: 1px solid var(--border);
        color: var(--text-secondary); flex-shrink: 0;
    }
    .doc-filename { flex: 1; color: var(--text-secondary); font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .doc-expiry   { font-size: 12px; color: var(--text-muted); flex-shrink: 0; }
    .doc-expiry.expiring { color: #f59e0b; font-weight: 500; }
    .doc-expiry.expired  { color: #dc3545; font-weight: 500; }
    .doc-actions  { display: flex; gap: 6px; flex-shrink: 0; }
    .doc-view-btn {
        width: 28px; height: 28px; border: 1px solid var(--border); border-radius: 6px;
        background: var(--card-bg); color: var(--text-muted); display: flex; align-items: center;
        justify-content: center; font-size: 13px; cursor: pointer; text-decoration: none;
        transition: background .15s;
    }
    .doc-view-btn:hover { background: var(--body-bg); color: var(--accent-dark); }
    .doc-delete-check { display: none; }
    .doc-delete-label {
        width: 28px; height: 28px; border: 1px solid var(--border); border-radius: 6px;
        background: var(--card-bg); color: #dc3545; display: flex; align-items: center;
        justify-content: center; font-size: 13px; cursor: pointer;
        transition: background .15s, border-color .15s;
    }
    .doc-delete-check:checked + .doc-delete-label {
        background: #fce8ea; border-color: #dc3545;
    }
    .doc-delete-check:checked ~ .existing-doc-inner { opacity: .4; }

    /* New doc rows */
    .doc-row {
        display: grid;
        grid-template-columns: 160px 1fr 130px 26px;
        gap: 8px;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
    }
    .doc-row:last-child { border-bottom: none; }
    .doc-row-header {
        font-size: 11px; font-weight: 600; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .4px;
        padding-bottom: 6px; border-bottom: 1px solid var(--border);
    }
    .doc-remove-btn {
        width: 26px; height: 26px; border: 1px solid var(--border); border-radius: 6px;
        background: transparent; color: #dc3545; display: flex; align-items: center;
        justify-content: center; cursor: pointer; font-size: 13px; transition: background .15s;
    }
    .doc-remove-btn:hover { background: #fce8ea; }
    .add-doc-btn {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12.5px; color: var(--accent-dark); background: transparent;
        border: none; cursor: pointer; padding: 4px 0; margin-top: 8px; font-weight: 500;
    }
    .add-doc-btn:hover { text-decoration: underline; }

    /* ── Sidebar ── */
    .sidebar-card {
        background: var(--card-bg); border: 1px solid var(--border);
        border-radius: var(--radius); box-shadow: var(--shadow-sm); margin-bottom: 14px; overflow: hidden;
    }
    .sidebar-card-header {
        padding: 14px 16px 12px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 10px;
    }
    .sidebar-card-body { padding: 16px; }

    /* Status history timeline */
    .status-timeline { margin: 0; padding: 0; list-style: none; }
    .status-timeline-item {
        display: flex; gap: 10px; padding: 8px 0;
        border-bottom: 1px solid var(--border); font-size: 12px;
    }
    .status-timeline-item:last-child { border-bottom: none; padding-bottom: 0; }
    .timeline-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
        margin-top: 4px; background: var(--text-muted);
    }
    .timeline-dot.ready       { background: var(--status-ready-c); }
    .timeline-dot.in_use      { background: var(--status-in-use-c); }
    .timeline-dot.not_ready   { background: var(--status-not-ready-c); }
    .timeline-dot.maintenance { background: var(--status-maintenance-c); }
    .timeline-dot.retired     { background: var(--status-retired-c); }
    .timeline-meta { color: var(--text-muted); margin-top: 2px; }

    /* Sidebar actions */
    .sidebar-actions {
        background: var(--card-bg); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 16px; box-shadow: var(--shadow-sm);
    }
    .sidebar-actions .btn-primary-fleet { width: 100%; justify-content: center; font-size: 14px; padding: 10px; }
    .sidebar-actions .btn-outline-fleet { width: 100%; justify-content: center; margin-top: 8px; font-size: 13px; }

    /* Danger zone */
    .danger-zone {
        border: 1px solid #fce8ea; border-radius: var(--radius);
        padding: 14px 16px; margin-top: 14px;
        background: #fffafa;
    }
    .danger-zone-title { font-size: 12px; font-weight: 600; color: #dc3545; margin-bottom: 6px; }
    .danger-zone p { font-size: 12px; color: var(--text-muted); margin-bottom: 10px; }
    .btn-danger-sm {
        background: transparent; color: #dc3545;
        border: 1px solid #dc3545; border-radius: var(--radius-sm);
        padding: 6px 12px; font-size: 12px; font-weight: 500;
        display: inline-flex; align-items: center; gap: 6px;
        cursor: pointer; transition: background .15s;
    }
    .btn-danger-sm:hover { background: #fce8ea; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Edit — {{ $car->plate_number }}</h1>
        <p class="page-subtitle">
            {{ $car->model->brand->name }} {{ $car->model->name }} · {{ $car->year }}
            <span class="status-badge {{ $car->status }}" style="margin-right:8px">
                {{ ucfirst(str_replace('_', ' ', $car->status)) }}
            </span>
        </p>
    </div>
    <div class="page-actions">
        <a href="{{ route('fleet.cars.show', $car) }}" class="btn-outline-fleet">
            <i class="bi bi-arrow-right"></i> Back
        </a>
        <button type="submit" form="editCarForm" class="btn-primary-fleet">
            <i class="bi bi-floppy"></i> Save changes
        </button>
    </div>
</div>

<form id="editCarForm"
      method="POST"
      action="{{ route('fleet.cars.update', $car) }}"
      enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="create-layout">

    {{-- ══════════════════════════════
         LEFT COLUMN
    ══════════════════════════════ --}}
    <div>

        {{-- ── Identity ── --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="section-icon"><i class="bi bi-hash"></i></div>
                <div>
                    <div class="section-title">Identity</div>
                    <div class="section-subtitle">Unique identifiers (BR-010).</div>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="field-label" for="code">Internal code</label>
                        <div class="input-with-icon">
                            <input type="text" id="code" name="code"
                                   class="form-input @error('code') is-invalid @enderror"
                                   value="{{ old('code', $car->code) }}">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        @error('code')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="plate_number">Plate number <span class="req">*</span></label>
                        <input type="text" id="plate_number" name="plate_number"
                               class="form-input @error('plate_number') is-invalid @enderror"
                               value="{{ old('plate_number', $car->plate_number) }}"
                               required>
                        @error('plate_number')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="field-label" for="chassis_number">VIN / Chassis</label>
                        <input type="text" id="chassis_number" name="chassis_number"
                               class="form-input @error('chassis_number') is-invalid @enderror"
                               value="{{ old('chassis_number', $car->chassis_number) }}"
                               maxlength="17">
                        @error('chassis_number')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="engine_number">Engine number</label>
                        <input type="text" id="engine_number" name="engine_number"
                               class="form-input @error('engine_number') is-invalid @enderror"
                               value="{{ old('engine_number', $car->engine_number) }}">
                        @error('engine_number')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Vehicle specs ── --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="section-icon"><i class="bi bi-car-front"></i></div>
                <div>
                    <div class="section-title">Vehicle specs</div>
                    <div class="section-subtitle">Manufacturer data used by reports and inspections.</div>
                </div>
            </div>
            <div class="form-section-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="field-label" for="brand_id">Brand <span class="req">*</span></label>
                        <select id="brand_id" name="brand_id"
                                class="form-select @error('brand_id') is-invalid @enderror"
                                required>
                            <option value="">Select brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $car->model->car_brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="car_model_id">Model <span class="req">*</span></label>
                        <select id="car_model_id" name="car_model_id"
                                class="form-select @error('car_model_id') is-invalid @enderror"
                                required>
                            <option value="">Select model</option>
                            @foreach($brands as $brand)
                                @foreach($brand->carModels as $model)
                                    <option value="{{ $model->id }}"
                                            data-brand="{{ $brand->id }}"
                                            {{ old('car_model_id', $car->car_model_id) == $model->id ? 'selected' : '' }}>
                                        {{ $model->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('car_model_id')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="field-label" for="year">
                            <i class="bi bi-calendar3" style="font-size:11px;margin-left:3px"></i>
                            Year <span class="req">*</span>
                        </label>
                        <input type="number" id="year" name="year"
                               class="form-input @error('year') is-invalid @enderror"
                               value="{{ old('year', $car->year) }}"
                               min="1990" max="{{ date('Y') + 1 }}"
                               required>
                        @error('year')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="field-label">
                            <i class="bi bi-palette" style="font-size:11px;margin-left:3px"></i>
                            Color
                        </label>
                        <div class="color-row">
                            <div class="color-swatch" id="colorSwatch" title="Pick color">
                                <div class="color-preview" id="colorPreview"
                                     style="background: {{ old('color_hex', $car->color_hex ?? '#ffffff') }}"></div>
                                <input type="color" id="colorPicker"
                                       value="{{ old('color_hex', $car->color_hex ?? '#ffffff') }}">
                            </div>
                            <input type="text" name="color"
                                   class="form-input @error('color') is-invalid @enderror"
                                   value="{{ old('color', $car->color) }}"
                                   placeholder="e.g. White, Silver">
                            <input type="hidden" name="color_hex" id="color_hex"
                                   value="{{ old('color_hex', $car->color_hex ?? '#ffffff') }}">
                        </div>
                        @error('color')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="field-label" for="fuel_type">Fuel type <span class="req">*</span></label>
                        <select id="fuel_type" name="fuel_type"
                                class="form-select @error('fuel_type') is-invalid @enderror"
                                required>
                            <option value="">Select fuel</option>
                            @foreach(['petrol' => 'Petrol', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid', 'gas' => 'Gas (CNG/LPG)'] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('fuel_type', $car->fuel_type) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('fuel_type')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="transmission">Transmission</label>
                        <select id="transmission" name="transmission"
                                class="form-select @error('transmission') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="automatic" {{ old('transmission', $car->transmission ?? '') === 'automatic' ? 'selected' : '' }}>Automatic</option>
                            <option value="manual"    {{ old('transmission', $car->transmission ?? '') === 'manual'    ? 'selected' : '' }}>Manual</option>
                        </select>
                        @error('transmission')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="field-label" for="current_km">
                            <i class="bi bi-speedometer2" style="font-size:11px;margin-left:3px"></i>
                            Odometer (km)
                        </label>
                        <input type="number" id="current_km" name="current_km"
                               class="form-input @error('current_km') is-invalid @enderror"
                               value="{{ old('current_km', $car->current_km) }}"
                               min="0">
                        @error('current_km')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="field-label" for="seats">Seats</label>
                        <input type="number" id="seats" name="seats"
                               class="form-input @error('seats') is-invalid @enderror"
                               value="{{ old('seats', $car->seats ?? 5) }}"
                               min="1" max="60">
                        @error('seats')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Features & equipment ── --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="section-icon"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="section-title">Features & equipment</div>
                    <div class="section-subtitle">Toggles power condition checks during inspections.</div>
                </div>
            </div>
            <div class="form-section-body">
                <div class="features-grid">
                    @php
                        $features    = $car->features ?? [];
                        $hasCamera   = old('has_camera',       $car->has_camera   ? '1' : '0') == '1';
                        $hasSensors  = old('has_sensors',      $car->has_sensors  ? '1' : '0') == '1';
                        $hasAbs      = old('features.abs',     ($features['abs']  ?? false) ? '1' : '0') == '1';
                        $hasGps      = old('features.gps',     ($features['gps']  ?? false) ? '1' : '0') == '1';
                    @endphp

                    <label class="feature-toggle {{ $hasCamera ? 'is-on' : '' }}">
                        <div class="feature-icon"><i class="bi bi-camera-video"></i></div>
                        <div class="feature-text">
                            <div class="feature-name">Front / rear camera</div>
                            <div class="feature-desc">Enables camera check in kroky.</div>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" name="has_camera" value="1" {{ $hasCamera ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-thumb"></span>
                        </div>
                    </label>

                    <label class="feature-toggle {{ $hasSensors ? 'is-on' : '' }}">
                        <div class="feature-icon"><i class="bi bi-radar"></i></div>
                        <div class="feature-text">
                            <div class="feature-name">Parking sensors</div>
                            <div class="feature-desc">Enables sensor check in kroky.</div>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" name="has_sensors" value="1" {{ $hasSensors ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-thumb"></span>
                        </div>
                    </label>

                    <label class="feature-toggle {{ $hasAbs ? 'is-on' : '' }}">
                        <div class="feature-icon"><i class="bi bi-shield-fill-check"></i></div>
                        <div class="feature-text">
                            <div class="feature-name">ABS / airbags</div>
                            <div class="feature-desc">Safety systems installed.</div>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" name="features[abs]" value="1" {{ $hasAbs ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-thumb"></span>
                        </div>
                    </label>

                    <label class="feature-toggle {{ $hasGps ? 'is-on' : '' }}">
                        <div class="feature-icon"><i class="bi bi-geo-alt"></i></div>
                        <div class="feature-text">
                            <div class="feature-name">GPS tracker</div>
                            <div class="feature-desc">Live location & geofencing.</div>
                        </div>
                        <div class="toggle-switch">
                            <input type="checkbox" name="features[gps]" value="1" {{ $hasGps ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                            <span class="toggle-thumb"></span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ── Documents ── --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="section-icon"><i class="bi bi-file-earmark-text"></i></div>
                <div>
                    <div class="section-title">Documents</div>
                    <div class="section-subtitle">License, insurance and inspection papers (BR-040).</div>
                </div>
            </div>
            <div class="form-section-body">

                {{-- Existing documents --}}
                @if($car->documents->count())
                <div class="existing-docs">
                    <div style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px">
                        Existing documents
                    </div>
                    @foreach($car->documents as $doc)
                    @php
                        $expired      = $doc->expiry_date && $doc->expiry_date->isPast();
                        $expiringSoon = $doc->expiry_date && !$expired && $doc->expiry_date->isBefore(now()->addDays(30));
                        $inputId      = 'del_doc_' . $doc->id;
                    @endphp
                    <div style="position:relative" class="existing-doc-wrapper">
                        <input type="checkbox" name="delete_documents[]"
                               value="{{ $doc->id }}" id="{{ $inputId }}" class="doc-delete-check"
                               style="position:absolute;opacity:0;width:0">
                        <div class="existing-doc-row">
                            <i class="bi bi-file-earmark-pdf doc-type-icon"></i>
                            <span class="doc-type-tag">{{ ucfirst($doc->type) }}</span>
                            <span class="doc-filename">{{ basename($doc->file_path) }}</span>
                            @if($doc->expiry_date)
                                <span class="doc-expiry {{ $expired ? 'expired' : ($expiringSoon ? 'expiring' : '') }}">
                                    @if($expired) Expired @elseif($expiringSoon) Exp soon @else Exp @endif
                                    {{ $doc->expiry_date->format('d M Y') }}
                                </span>
                            @else
                                <span class="doc-expiry">—</span>
                            @endif
                            <div class="doc-actions">
                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="doc-view-btn" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <label for="{{ $inputId }}" class="doc-delete-label" title="Mark for deletion">
                                    <i class="bi bi-trash3"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div style="font-size:11px;color:var(--text-muted);margin-top:4px">
                        <i class="bi bi-info-circle"></i>
                        Click the trash icon to mark a document for deletion. It will be removed when you save.
                    </div>
                </div>

                <div style="height:1px;background:var(--border);margin:16px 0"></div>
                @endif

                {{-- New uploads --}}
                <div style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px">
                    Upload new documents
                </div>
                <div class="doc-row doc-row-header">
                    <span>Type</span><span>File</span><span>Expiry date</span><span></span>
                </div>
                <div id="docRows">
                    <div class="doc-row">
                        <select name="documents[0][type]" class="form-select" style="height:34px;font-size:12.5px">
                            <option value="license">License</option>
                            <option value="insurance">Insurance</option>
                            <option value="inspection">Inspection</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="file" name="documents[0][file]" class="form-input" style="height:34px;padding:5px 10px;font-size:12px">
                        <input type="date" name="documents[0][expiry_date]" class="form-input" style="height:34px;font-size:12.5px">
                        <button type="button" class="doc-remove-btn" onclick="removeDoc(this)" style="display:none">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="add-doc-btn" id="addDocBtn">
                    <i class="bi bi-plus-circle"></i> Add document
                </button>
            </div>
        </div>

    </div>{{-- /left col --}}

    {{-- ══════════════════════════════
         RIGHT COLUMN — sidebar
    ══════════════════════════════ --}}
    <div>

        {{-- ── Branch & ownership ── --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="section-icon" style="width:30px;height:30px;font-size:14px"><i class="bi bi-building"></i></div>
                <div>
                    <div class="section-title">Branch & ownership</div>
                    <div class="section-subtitle" style="font-size:11px">Where the car is physically stationed.</div>
                </div>
            </div>
            <div class="sidebar-card-body">
                <div class="form-group" style="margin-bottom:12px">
                    <label class="field-label" for="branch_id">Branch <span class="req">*</span></label>
                    <select id="branch_id" name="branch_id"
                            class="form-select @error('branch_id') is-invalid @enderror"
                            required>
                        <option value="">Select branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ old('branch_id', $car->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group" style="margin-bottom:12px">
                    <label class="field-label" for="ownership">Ownership</label>
                    <select id="ownership" name="ownership" class="form-select">
                        @foreach(['owned' => 'Owned', 'leased' => 'Leased', 'rented' => 'Rented', 'company' => 'Company-provided'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('ownership', $car->ownership ?? 'owned') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:12px">
                    <label class="field-label" for="purchase_date">Purchase date</label>
                    <input type="date" id="purchase_date" name="purchase_date"
                           class="form-input @error('purchase_date') is-invalid @enderror"
                           value="{{ old('purchase_date', optional($car->purchase_date)->format('Y-m-d')) }}">
                    @error('purchase_date')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="field-label" for="purchase_price">Purchase price</label>
                    <div class="input-with-icon">
                        <input type="number" id="purchase_price" name="purchase_price" step="0.01"
                               class="form-input @error('purchase_price') is-invalid @enderror"
                               value="{{ old('purchase_price', $car->purchase_price ?? '0.00') }}">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    @error('purchase_price')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- ── Current status (read-only here, change via modal) ── --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="section-icon" style="width:30px;height:30px;font-size:14px"><i class="bi bi-arrow-repeat"></i></div>
                <div>
                    <div class="section-title">Status</div>
                    <div class="section-subtitle" style="font-size:11px">Use "Change status" to transition with audit trail.</div>
                </div>
            </div>
            <div class="sidebar-card-body">
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <span class="status-badge {{ $car->status }}" style="font-size:13px;padding:5px 12px">
                        {{ ucfirst(str_replace('_', ' ', $car->status)) }}
                    </span>
                    @can('cars.change_status')
                    <button type="button" class="btn-outline-fleet" style="font-size:12px;padding:5px 10px"
                            onclick="openStatusModal('{{ $car->id }}', '{{ $car->status }}')">
                        <i class="bi bi-arrow-repeat"></i> Change
                    </button>
                    @endcan
                </div>

                {{-- Recent history --}}
                @if($car->statusHistory->count())
                <div style="margin-top:14px">
                    <div style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px">
                        Recent history
                    </div>
                    <ul class="status-timeline">
                        @foreach($car->statusHistory->sortByDesc('created_at')->take(4) as $entry)
                        <li class="status-timeline-item">
                            <span class="timeline-dot {{ $entry->new_status }}"></span>
                            <div>
                                <div>
                                    @if($entry->old_status)
                                        <span style="color:var(--text-muted)">{{ ucfirst(str_replace('_',' ',$entry->old_status)) }}</span>
                                        <i class="bi bi-arrow-right" style="font-size:10px;color:var(--text-muted)"></i>
                                    @endif
                                    <strong>{{ ucfirst(str_replace('_',' ',$entry->new_status)) }}</strong>
                                    @if($entry->is_override)
                                        <span style="font-size:10px;color:#f59e0b;margin-right:4px">(override)</span>
                                    @endif
                                </div>
                                <div class="timeline-meta">
                                    {{ $entry->changedBy?->name ?? 'System' }}
                                    · {{ $entry->created_at->diffForHumans() }}
                                </div>
                                @if($entry->reason)
                                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;font-style:italic">
                                    "{{ Str::limit($entry->reason, 60) }}"
                                </div>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Notes ── --}}
        <div class="sidebar-card">
            <div class="sidebar-card-header">
                <div class="section-icon" style="width:30px;height:30px;font-size:14px"><i class="bi bi-sticky"></i></div>
                <div>
                    <div class="section-title">Notes</div>
                    <div class="section-subtitle" style="font-size:11px">Optional internal notes visible to admins.</div>
                </div>
            </div>
            <div class="sidebar-card-body">
                <textarea name="notes" rows="4" class="form-textarea"
                          placeholder="Anything worth noting about this vehicle...">{{ old('notes', $car->notes) }}</textarea>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="sidebar-actions">
            <button type="submit" form="editCarForm" class="btn-primary-fleet">
                <i class="bi bi-floppy"></i> Save changes
            </button>
            <a href="{{ route('fleet.cars.show', $car) }}" class="btn-outline-fleet">
                <i class="bi bi-x"></i> Cancel
            </a>
        </div>

        {{-- ── Danger zone ── --}}
        @can('cars.delete')
        <div class="danger-zone">
            <div class="danger-zone-title"><i class="bi bi-exclamation-triangle"></i> Danger zone</div>
            <p>Retiring this car will remove it from all operations and cannot be easily undone.</p>
            <button type="button" class="btn-danger-sm"
                    onclick="confirmRetire('{{ $car->id }}', '{{ $car->plate_number }}')">
                <i class="bi bi-power"></i> Retire car
            </button>
        </div>
        @endcan

    </div>{{-- /right col --}}

</div>
</form>

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
                        <select name="status" class="form-select" style="height:40px">
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
// ── Brand → Model cascade ──
const allOptions = Array.from(document.querySelectorAll('#car_model_id option[data-brand]'));

document.getElementById('brand_id').addEventListener('change', function () {
    const brandId  = this.value;
    const modelSel = document.getElementById('car_model_id');
    const current  = modelSel.value;

    modelSel.innerHTML = '<option value="">Select model</option>';
    allOptions
        .filter(o => !brandId || o.dataset.brand === brandId)
        .forEach(o => {
            const clone = o.cloneNode(true);
            if (clone.value === current) clone.selected = true;
            modelSel.appendChild(clone);
        });
});

// ── Color picker ──
const colorPicker   = document.getElementById('colorPicker');
const colorPreview  = document.getElementById('colorPreview');
const colorHexInput = document.getElementById('color_hex');

document.getElementById('colorSwatch').addEventListener('click', () => colorPicker.click());

colorPicker.addEventListener('input', function () {
    colorPreview.style.background = this.value;
    colorHexInput.value = this.value;
});

// ── Feature toggles ──
document.querySelectorAll('.feature-toggle input[type=checkbox]').forEach(function (cb) {
    const wrapper = cb.closest('.feature-toggle');
    cb.addEventListener('change', () => {
        wrapper.classList.toggle('is-on', cb.checked);
    });
});

// ── Documents (new rows) ──
let docIndex = 1;

document.getElementById('addDocBtn').addEventListener('click', function () {
    const i   = docIndex++;
    const row = document.createElement('div');
    row.className = 'doc-row';
    row.innerHTML = `
        <select name="documents[${i}][type]" class="form-select" style="height:34px;font-size:12.5px">
            <option value="license">License</option>
            <option value="insurance">Insurance</option>
            <option value="inspection">Inspection</option>
            <option value="other">Other</option>
        </select>
        <input type="file" name="documents[${i}][file]" class="form-input" style="height:34px;padding:5px 10px;font-size:12px">
        <input type="date" name="documents[${i}][expiry_date]" class="form-input" style="height:34px;font-size:12.5px">
        <button type="button" class="doc-remove-btn" onclick="removeDoc(this)">
            <i class="bi bi-x"></i>
        </button>
    `;
    document.getElementById('docRows').appendChild(row);
});

function removeDoc(btn) {
    btn.closest('.doc-row').remove();
}

// ── Status modal ──
function openStatusModal(carId, currentStatus) {
    const form = document.getElementById('statusForm');
    form.action = `/fleet/cars/${carId}/status`;
    form.querySelector('select[name=status]').value = currentStatus;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}

// ── Retire modal ──
function confirmRetire(carId, plate) {
    document.getElementById('retirePlate').textContent = plate;
    document.getElementById('retireForm').action = `/fleet/cars/${carId}`;
    new bootstrap.Modal(document.getElementById('retireModal')).show();
}

// ── Doc delete visual feedback ──
document.querySelectorAll('.doc-delete-check').forEach(function (cb) {
    cb.addEventListener('change', function () {
        const row = this.closest('.existing-doc-wrapper').querySelector('.existing-doc-row');
        row.style.opacity  = this.checked ? '0.45' : '1';
        row.style.background = this.checked ? '#fce8ea' : '';
    });
});
</script>
@endpush
