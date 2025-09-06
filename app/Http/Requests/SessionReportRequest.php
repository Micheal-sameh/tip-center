<?php

namespace App\Http\Requests;

use App\Enums\ReportType;
use Illuminate\Foundation\Http\FormRequest;

class SessionReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'session_id' => 'required|integer|exists:sessions,id',
            'type' => 'integer|in:'.implode(',', array_column(ReportType::all(), 'value')),
            'with_phones' => 'in:1',
        ];
    }
}
