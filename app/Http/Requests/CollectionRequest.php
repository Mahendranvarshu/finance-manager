<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
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
        return [
            'party_id' => 'required|exists:parties,id',
            'collector_id' => 'required|exists:collectors,id',
            'date' => 'required|date',
            'amount_collected' => 'required|numeric|min:0.01',
            'remaining_amount' => 'nullable|numeric|min:0',
            'day_number' => 'nullable|integer|min:1',
            'remarks' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'party_id.required' => 'Party selection is required.',
            'party_id.exists' => 'Selected party does not exist.',
            'collector_id.required' => 'Collector selection is required.',
            'collector_id.exists' => 'Selected collector does not exist.',
            'date.required' => 'Collection date is required.',
            'date.date' => 'Collection date must be a valid date.',
            'amount_collected.required' => 'Amount collected is required.',
            'amount_collected.numeric' => 'Amount collected must be a valid number.',
            'amount_collected.min' => 'Amount collected must be greater than 0.',
        ];
    }
}

