<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();

        // 1. Stat Cards
        $totalExpenseThisMonth = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $currentDayCount = max($now->day, 1);
        $dailyAverage = $totalExpenseThisMonth / $currentDayCount;

        $highestSpendItem = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->first();

        $highestCategory = $highestSpendItem ? $highestSpendItem->category : '-';
        $highestCategoryAmount = $highestSpendItem ? $highestSpendItem->total : 0;

        // 2. Spending by Category (Donut chart & ranked list)
        $expenseCategories = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $topCategories = $expenseCategories->map(function ($item, $index) use ($totalExpenseThisMonth) {
            $percent = $totalExpenseThisMonth > 0 ? round(($item->total / $totalExpenseThisMonth) * 100) : 0;
            return [
                'rank' => $index + 1,
                'category' => $item->category,
                'amount' => $item->total,
                'percent' => $percent,
            ];
        });

        // 3. Daily Spending Chart (Days of current month up to today or month end)
        $daysInMonth = min($now->day, 14); // or up to current day
        $dailyLabels = [];
        $dailyValues = [];
        for ($d = 1; $d <= $now->day; $d++) {
            $dateStr = Carbon::create($now->year, $now->month, $d)->toDateString();
            $dailyLabels[] = sprintf('%02d', $d);
            $val = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereDate('date', $dateStr)
                ->sum('amount');
            $dailyValues[] = (float) $val;
        }

        // 4. Expense entries
        $expenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('expenses.index', compact(
            'totalExpenseThisMonth',
            'dailyAverage',
            'highestCategory',
            'highestCategoryAmount',
            'topCategories',
            'dailyLabels',
            'dailyValues',
            'expenses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|in:Food,Transportation,Shopping,Bills,Entertainment,Health,Education,Other',
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
            'type' => 'expense',
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'date' => $validated['date'],
            'payment_method' => $validated['payment_method'],
            'receipt_path' => $receiptPath,
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function update(Request $request, Transaction $expense)
    {
        if ($expense->user_id !== Auth::id() || $expense->type !== 'expense') {
            abort(403);
        }

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string|in:Food,Transportation,Shopping,Bills,Entertainment,Health,Education,Other',
            'date' => 'required|date',
            'payment_method' => 'required|string|max:100',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $expense->receipt_path = $request->file('receipt')->store('receipts', 'public');
        }

        $expense->update([
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'date' => $validated['date'],
            'payment_method' => $validated['payment_method'],
            'receipt_path' => $expense->receipt_path,
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    public function destroy(Transaction $expense)
    {
        if ($expense->user_id !== Auth::id() || $expense->type !== 'expense') {
            abort(403);
        }

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
