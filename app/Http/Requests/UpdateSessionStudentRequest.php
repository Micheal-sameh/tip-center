<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionStudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'professor_price' => 'numeric|gte:0',
            'center_price' => 'numeric|gte:0',
            'printables' => 'numeric|gte:0',
            'materials' => 'numeric|gte:0',
        ];
    }
}
