<?php
// Modules/Fleet/app/Http/Controllers/CarController.php

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
        private CarService $carService
    ) {}

    public function index(): View
{
    // Filters
    $query = Car::query()
        ->with(['model.brand', 'branch', 'currentDriver'])
        ->when(request('search'), function ($q) {
            $q->where(function ($q) {
                $q->where('plate_number', 'like', '%' . request('search') . '%')
                  ->orWhere('code', 'like', '%' . request('search') . '%')
                  ->orWhereHas('model.brand', fn($q) =>
                        $q->where('name', 'like', '%' . request('search') . '%')
                  );
            });
        })
        ->when(request('status'),    fn($q) => $q->where('status', request('status')))
        ->when(request('branch_id'), fn($q) => $q->where('branch_id', request('branch_id')))
        ->when(request('brand_id'),  fn($q) => $q->whereHas('model', fn($q) =>
            $q->where('car_brand_id', request('brand_id'))
        ))
        ->latest();

    $cars = $query->paginate(15);

    // Stats
    $allCars = Car::selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

    $stats = [
        'total'       => Car::count(),
        'ready'       => $allCars['ready']       ?? 0,
        'in_use'      => $allCars['in_use']      ?? 0,
        'not_ready'   => $allCars['not_ready']   ?? 0,
        'maintenance' => $allCars['maintenance'] ?? 0,
        'retired'     => $allCars['retired']     ?? 0,
    ];

    // Filter options
    $branches = Branch::where('is_active', true)->get();
    $brands   = CarBrand::where('is_active', true)->get();

    return view('fleet::cars.index', compact('cars', 'stats', 'branches', 'brands'));
}


    public function create(): View
    {
        $brands   = CarBrand::where('is_active', true)->with('carModels')->get();
        $branches = Branch::where('is_active', true)->get();

        return view('fleet::cars.create', compact('brands', 'branches'));
    }

    public function store(StoreCarRequest $request): RedirectResponse
    {
        $this->carService->create($request->validated());

        return redirect()
            ->route('fleet.cars.index')
            ->with('success', 'تم إضافة السيارة بنجاح');
    }

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

    public function edit(Car $car): View
    {
        $brands   = CarBrand::where('is_active', true)->with('carModels')->get();
        $branches = Branch::where('is_active', true)->get();

        return view('fleet::cars.edit', compact('car', 'brands', 'branches'));
    }

    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $car->update($request->validated());

        return redirect()
            ->route('fleet.cars.show', $car)
            ->with('success', 'تم تحديث بيانات السيارة');
    }

    public function destroy(Car $car): RedirectResponse
    {
        // مش بنحذف، بنعمل retired
        $this->carService->changeStatus(
            car: $car,
            newStatus: 'retired',
            reason: 'تم إيقاف السيارة من النظام',
        );

        return redirect()
            ->route('fleet.cars.index')
            ->with('success', 'تم إيقاف السيارة');
    }

    // تغيير الحالة
    public function changeStatus(Car $car): RedirectResponse
    {
        request()->validate([
            'status' => 'required|in:ready,not_ready,maintenance,retired',
            'reason' => 'required|string|max:255',
        ]);

        $isOverride = request('status') === 'ready'
            && $car->status === 'not_ready';

        $this->carService->changeStatus(
            car: $car,
            newStatus: request('status'),
            reason: request('reason'),
            isOverride: $isOverride,
        );

        return back()->with('success', 'تم تغيير حالة السيارة');
    }
}
