<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Kategori anggaran wajib diisi.',
            'amount.required' => 'Nominal batas anggaran wajib diisi.',
            'amount.numeric' => 'Nominal anggaran harus berupa angka.',
            'amount.min' => 'Nominal anggaran minimal 0.01.',
        ];
    }
}
