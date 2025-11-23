<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $collectorId = $this->route('collector') ? $this->route('collector')->id : null;

        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'area' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:collectors,username,' . $collectorId,
            'password' => $collectorId ? 'nullable|string|min:6' : 'required|string|min:6',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Collector name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If updating and password is empty, remove it from validation
        if ($this->route('collector') && empty($this->password)) {
            $this->merge(['password' => $this->route('collector')->password]);
        }
    }
}

