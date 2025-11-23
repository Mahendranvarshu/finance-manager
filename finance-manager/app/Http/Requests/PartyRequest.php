<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartyRequest extends FormRequest
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
        $partyId = $this->route('party') ? $this->route('party')->id : null;

        return [
            'dl_no' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'store_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'loan_amount' => 'required|numeric|min:0',
            'interest_amount' => 'nullable|numeric|min:0',
            'daily_amount' => 'nullable|numeric|min:0',
            'total_days' => 'required|integer|min:1',
            'starting_date' => 'required|date',
            'ending_date' => 'nullable|date|after_or_equal:starting_date',
            'collector_id' => 'nullable|exists:collectors,id',
            'status' => 'required|in:active,completed,default',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Party name is required.',
            'loan_amount.required' => 'Loan amount is required.',
            'loan_amount.numeric' => 'Loan amount must be a valid number.',
            'loan_amount.min' => 'Loan amount must be greater than or equal to 0.',
            'total_days.required' => 'Total days is required.',
            'total_days.integer' => 'Total days must be a valid integer.',
            'total_days.min' => 'Total days must be at least 1.',
            'starting_date.required' => 'Starting date is required.',
            'starting_date.date' => 'Starting date must be a valid date.',
            'ending_date.date' => 'Ending date must be a valid date.',
            'ending_date.after_or_equal' => 'Ending date must be after or equal to starting date.',
            'collector_id.exists' => 'Selected collector does not exist.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: active, completed, default.',
        ];
    }
}

