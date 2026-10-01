<?php

namespace App\Http\Requests\Savings;

use Illuminate\Foundation\Http\FormRequest;

class DepositSavingGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $savingGoal = $this->route('savingGoal');
        return auth()->check() && $savingGoal && $savingGoal->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'deposit_amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'deposit_amount.required' => 'Nominal setoran tabungan wajib diisi.',
            'deposit_amount.numeric' => 'Nominal setoran harus berupa angka.',
            'deposit_amount.min' => 'Nominal setoran minimal 0.01.',
        ];
    }
}
