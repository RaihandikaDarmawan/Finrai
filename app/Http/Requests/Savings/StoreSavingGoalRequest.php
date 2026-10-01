<?php

namespace App\Http\Requests\Savings;

use Illuminate\Foundation\Http\FormRequest;

class StoreSavingGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'min:0.01'],
            'initial_deposit' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['nullable', 'date'],
            'color' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama target tabungan wajib diisi.',
            'target_amount.required' => 'Target nominal tabungan wajib diisi.',
            'target_amount.numeric' => 'Target nominal harus berupa angka.',
            'target_amount.min' => 'Target nominal minimal 0.01.',
            'initial_deposit.numeric' => 'Setoran awal harus berupa angka.',
            'target_date.date' => 'Format tanggal target tidak valid.',
        ];
    }
}
