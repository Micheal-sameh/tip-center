<?php

namespace App\Http\Requests;

use App\Enums\ChargeType;
use Illuminate\Foundation\Http\FormRequest;

class ChargeStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'amount' => 'required|numeric'.($this->type == ChargeType::GAP ? '' : '|gte:0'),
            'type' => 'required|in:'.implode(',', array_column(ChargeType::all(), 'value')),
            'created_at' => auth()->user()->can('charges_salary') ? 'nullable|date|after_or_equal:'.now()->subMonth()->startOfMonth()->toDateString().'|before_or_equal:'.now()->toDateString() : 'prohibited',
        ];
    }
}
