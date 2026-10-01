<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');
        return auth()->check() && $transaction && $transaction->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:income,expense'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:100'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Tipe transaksi wajib dipilih.',
            'description.required' => 'Deskripsi transaksi wajib diisi.',
            'amount.required' => 'Jumlah nominal wajib diisi.',
            'amount.numeric' => 'Jumlah nominal harus berupa angka.',
            'amount.min' => 'Jumlah nominal minimal 0.01.',
            'category.required' => 'Kategori wajib dipilih atau diisi.',
            'date.required' => 'Tanggal transaksi wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'receipt.mimes' => 'Bukti transaksi harus berformat JPG, PNG, atau PDF.',
            'receipt.max' => 'Ukuran file bukti transaksi maksimal 4MB.',
        ];
    }
}
