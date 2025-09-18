<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class CreateOnlinePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'materials' => 'nullable|string|max:255',
            'professor' => 'required|numeric|gte:0',
            'center' => 'required|numeric|gte:0',
            'session_id' => 'required|exists:sessions,id',
            'stage' => 'required|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
        ];
    }
}
