<?php

namespace Modules\Fleet\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Fleet\Http\Requests\StoreCarRequest;
use Modules\Fleet\Http\Requests\UpdateCarRequest;
use Modules\Fleet\Models\Car;
use Modules\Fleet\Models\CarBrand;
use Modules\Fleet\Services\CarService;
use Modules\Core\Models\Branch;

class CarController extends Controller
{
    public function __construct(
        private CarService $carService,
    ) {}

    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(): View
    {
        $query = Car::query()
            ->with(['model.brand', 'branch', 'currentDriver'])
            ->when(request('search'), function ($q) {
                $q->where(function ($q) {
                    $q->where('plate_number', 'like', '%' . request('search') . '%')
                      ->orWhere('code',         'like', '%' . request('search') . '%')
                      ->orWhereHas('model.brand', fn($q) =>
                            $q->where('name', 'like', '%' . request('search') . '%')
                      );
                });
            })
            ->when(request('status'),    fn($q) => $q->where('status',    request('status')))
            ->when(request('branch_id'), fn($q) => $q->where('branch_id', request('branch_id')))
            ->when(request('brand_id'),  fn($q) => $q->whereHas('model',  fn($q) =>
                $q->where('car_brand_id', request('brand_id'))
            ))
            ->latest();

        $cars = $query->paginate(15);

        $allCars = Car::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $stats = [
            'total'       => Car::count(),
            'ready'       => $allCars['ready']       ?? 0,
            'in_use'      => $allCars['in_use']       ?? 0,
            'not_ready'   => $allCars['not_ready']    ?? 0,
            'maintenance' => $allCars['maintenance']  ?? 0,
            'retired'     => $allCars['retired']      ?? 0,
        ];

        $branches = Branch::where('is_active', true)->get();
        $brands   = CarBrand::where('is_active', true)->get();

        return view('fleet::cars.index', compact('cars', 'stats', 'branches', 'brands'));
    }

    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    public function create(): View
    {
        $brands   = CarBrand::where('is_active', true)->with('carModels')->get();
        $branches = Branch::where('is_active', true)->get();

        return view('fleet::cars.create', compact('brands', 'branches'));
    }

    // ─────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────
    public function store(StoreCarRequest $request): RedirectResponse
    {
        $car = $this->carService->create($request->validated());

        return redirect()
            ->route('fleet.cars', $car)
            ->with('success', 'Car added successfully.');
    }

    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(Car $car): View
    {
        $car->load([
            'model.brand',
            'branch',
            'currentDriver',
            'documents',
            'statusHistory.changedBy',
            'latestKroky.points',
        ]);

        return view('fleet::cars.show', compact('car'));
    }

    // ─────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────
    public function edit(Car $car): View
    {
        $car->load(['documents', 'model.brand', 'statusHistory.changedBy']);

        $brands   = CarBrand::where('is_active', true)->with('carModels')->get();
        $branches = Branch::where('is_active', true)->get();

        return view('fleet::cars.edit', compact('car', 'brands', 'branches'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────
    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $this->carService->update($car, $request->validated());

        return redirect()
            ->route('fleet.cars', $car)
            ->with('success', 'Car updated successfully.');
    }

    // ─────────────────────────────────────────────
    //  DESTROY  (retire — not a real delete)
    // ─────────────────────────────────────────────
    public function destroy(Car $car): RedirectResponse
    {
        $this->carService->changeStatus(
            car: $car,
            newStatus: 'retired',
            reason: 'Car retired from system.',
        );

        return redirect()
            ->route('fleet.cars.index')
            ->with('success', 'Car has been retired.');
    }

    // ─────────────────────────────────────────────
    //  CHANGE STATUS
    // ─────────────────────────────────────────────
    public function changeStatus(Car $car): RedirectResponse
    {
        request()->validate([
            'status' => 'required|in:ready,not_ready,maintenance,retired',
            'reason' => 'required|string|max:255',
        ]);

        // BR-020: forcing not_ready → ready without an inspection is an override
        $isOverride = request('status') === 'ready'
            && $car->status === 'not_ready';

        $this->carService->changeStatus(
            car: $car,
            newStatus: request('status'),
            reason: request('reason'),
            isOverride: $isOverride,
        );

        return back()->with('success', 'Car status updated.');
    }
}
