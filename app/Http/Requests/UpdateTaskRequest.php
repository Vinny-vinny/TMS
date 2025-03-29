<?php

namespace App\Http\Requests;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'title is required',
            'description.required' => 'description is required',
            'due_date.required' => 'due date is required',
            'due_date.date' => 'due date must be a valid date',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Log::error('Validation errors: ', $validator->errors()->toArray());
        $controller = new BaseController();
        throw new \Illuminate\Validation\ValidationException($validator, $controller->sendError('Validation error', $validator->errors(), 422));
    }
}
