<?php

namespace App\Http\Requests;

use App\Enums\StagesEnum;
use Illuminate\Foundation\Http\FormRequest;

class StudentCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:students,name',
            'stage' => 'required|integer|in:'.implode(',', array_column(StagesEnum::all(), 'value')),
            'phone' => 'required_without:parent_phone|string|min:11|max:11|unique:students,phone,',
            'parent_phone' => 'required_without:phone|min:11|max:11|string',
            'parent_phone_2' => 'string|min:11|max:11',
            'note' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Student name is required.',
            'name.string' => 'Student name must be a valid string.',
            'name.unique' => 'This student name already exists.',

            'stage.required' => 'Stage is required.',
            'stage.integer' => 'Stage must be an integer value.',
            'stage.in' => 'Invalid stage selected.',

            'phone.required_without' => 'Phone number is required if parent phone is not provided.',
            'phone.string' => 'Phone number must be a valid string.',
            'phone.min' => 'Phone number must be exactly 11 digits.',
            'phone.max' => 'Phone number must be exactly 11 digits.',
            'phone.unique' => 'This phone number is already registered.',

            'parent_phone.required_without' => 'Parent phone is required if student phone is not provided.',
            'parent_phone.string' => 'Parent phone must be a valid string.',
            'parent_phone.min' => 'Parent Phone number must be exactly 11 digits.',
            'parent_phone.max' => 'Parent Phone number must be exactly 11 digits.',

            'parent_phone_2.string' => 'Parent second phone must be a valid string.',
            'parent_phone_2.min' => 'Parent second phone must be exactly 11 digits.',
            'parent_phone_2.max' => 'Parent second phone must be exactly 11 digits.',

            'note.string' => 'Note must be a valid string.',
        ];
    }
}
