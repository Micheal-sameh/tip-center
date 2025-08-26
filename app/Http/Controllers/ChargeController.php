<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargeIndexRequest;
use App\Http\Requests\ChargeStoreRequest;
use App\Repositories\ChargeRepository;

class ChargeController extends Controller
{
    public function __construct(protected ChargeRepository $chargeRepository)
    {
        $this->middleware('permission:charges_create')->only(['create', 'store']);
        $this->middleware('permission:charges_index')->only(['index']);
        $this->middleware('permission:charges_delete')->only(['delete']);
        $this->middleware('permission:monthly_income')->only(['monthlyIncome']);
    }

    public function index(ChargeIndexRequest $request)
    {
        $charges = $this->chargeRepository->index($request->validated());

        return view('charges.index', compact('charges'));
    }

    public function create()
    {
        return view('charges.create');
    }

    public function store(ChargeStoreRequest $request)
    {
        $this->chargeRepository->store($request->validated());

        return to_route('charges.index');
    }

    public function delete($id)
    {
        $this->chargeRepository->delete($id);

        return to_route('charges.index');
    }
}
