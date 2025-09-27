<?php

namespace App\Http\Controllers;

use App\Enums\ChargeType;
use App\Http\Requests\ChargeIndexRequest;
use App\Http\Requests\ChargeStoreRequest;
use App\Repositories\ChargeRepository;

class ChargeController extends Controller
{
    public function __construct(protected ChargeRepository $chargeRepository)
    {
        $this->middleware('permission:charges_create')->only(['create', 'store']);
        $this->middleware('permission:charges_index')->only(['index', 'gap']);
        $this->middleware('permission:charges_delete')->only(['delete']);
    }

    public function index(ChargeIndexRequest $request)
    {
        $charges = $this->chargeRepository->index($request->validated());
        $title = 'Charges';
        $route = 'index';

        return view('charges.index', compact('charges', 'title', 'route'));
    }

    public function gap(ChargeIndexRequest $request)
    {
        $charges = $this->chargeRepository->gap($request->validated());
        $title = 'Gaps';
        $route = 'gap';

        return view('charges.index', compact('charges', 'title', 'route'));
    }

    public function studentPrint(ChargeIndexRequest $request)
    {
        $charges = $this->chargeRepository->studentPrint($request->validated());
        $title = 'Student Print';
        $route = 'student-print';

        return view('charges.index', compact('charges', 'title', 'route'));
    }

    public function create()
    {
        return view('charges.create');
    }

    public function store(ChargeStoreRequest $request)
    {
        $charge = $this->chargeRepository->store($request->validated());

        return $charge->type == ChargeType::GAP
            ? redirect()->back()->with('success', "gap: $charge->amount EGP created successfuly")
            : to_route('charges.index');
    }

    public function delete($id)
    {
        $type = $this->chargeRepository->delete($id);

        return match ($type) {
            ChargeType::GAP => to_route('charges.gap'),
            ChargeType::STUDENT_PRINT => to_route('charges.student-print'),
            default => to_route('charges.index'),
        };
    }
}
