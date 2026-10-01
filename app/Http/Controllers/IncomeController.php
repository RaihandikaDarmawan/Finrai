<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncomeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        // 1. Total Income Cards
        $totalIncomeThisMonth = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $totalIncomePrevMonth = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $now->copy()->subMonth()->year)
            ->whereMonth('date', $now->copy()->subMonth()->month)
            ->sum('amount');

        $momChangePercent = $totalIncomePrevMonth > 0 
            ? (($totalIncomeThisMonth - $totalIncomePrevMonth) / $totalIncomePrevMonth) * 100 
            : 0;

        $dailyAverageThisMonth = $now->day > 0 ? $totalIncomeThisMonth / $now->day : 0;

        // 2. Income Trend (Last 6 months)
        $trendLabels = [];
        $trendValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $now->copy()->subMonths($i);
            $trendLabels[] = $monthDate->translatedFormat('M');
            $val = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereYear('date', $monthDate->year)
                ->whereMonth('date', $monthDate->month)
                ->sum('amount');
            $trendValues[] = (float) $val;
        }

        // 3. Income Sources breakdown this month
        $incomeCategories = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $sources = $incomeCategories->map(function ($item) use ($totalIncomeThisMonth) {
            $percent = $totalIncomeThisMonth > 0 ? round(($item->total / $totalIncomeThisMonth) * 100) : 0;
            return [
                'category' => $item->category,
                'amount' => $item->total,
                'percent' => $percent,
            ];
        });

        // 4. Income entries list
        $incomes = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('income.index', compact(
            'totalIncomeThisMonth',
            'totalIncomePrevMonth',
            'momChangePercent',
            'dailyAverageThisMonth',
            'trendLabels',
            'trendValues',
            'sources',
            'incomes'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|in:Salary,Freelance,Bonus,Investment,Gift,Business,Other',
            'date' => 'required|date',
            'payment_method' => 'required|string|max:100',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'income',
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'date' => $validated['date'],
            'payment_method' => $validated['payment_method'],
            'receipt_path' => $receiptPath,
        ]);

        return redirect()->back()->with('success', 'Pemasukan berhasil ditambahkan!');
    }

    public function update(Request $request, Transaction $income)
    {
        if ($income->user_id !== Auth::id() || $income->type !== 'income') {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|in:Salary,Freelance,Bonus,Investment,Gift,Business,Other',
            'date' => 'required|date',
            'payment_method' => 'required|string|max:100',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($request->hasFile('receipt')) {
            if ($income->receipt_path) {
                Storage::disk('public')->delete($income->receipt_path);
            }
            $income->receipt_path = $request->file('receipt')->store('receipts', 'public');
        }

        $income->update([
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'date' => $validated['date'],
            'payment_method' => $validated['payment_method'],
            'receipt_path' => $income->receipt_path,
        ]);

        return redirect()->back()->with('success', 'Pemasukan berhasil diperbarui!');
    }

    public function destroy(Transaction $income)
    {
        if ($income->user_id !== Auth::id() || $income->type !== 'income') {
            abort(403);
        }

        if ($income->receipt_path) {
            Storage::disk('public')->delete($income->receipt_path);
        }

        $income->delete();

        return redirect()->back()->with('success', 'Pemasukan berhasil dihapus!');
    }
}
