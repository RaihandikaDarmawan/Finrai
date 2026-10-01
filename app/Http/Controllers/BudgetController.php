<?php

namespace App\Http\Controllers;

use App\Http\Requests\Budget\StoreBudgetRequest;
use App\Models\Budget;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        $now = Carbon::now();

        $budgets = Budget::forUser($userId)->get();

        $totalBudgetLimit = $budgets->sum('amount');
        $totalSpentCombined = 0;
        $nearLimitCount = 0;
        $overBudgetCount = 0;

        $budgetDetails = $budgets->map(function ($budget) use ($userId, $now, &$totalSpentCombined, &$nearLimitCount, &$overBudgetCount) {
            $spent = Transaction::forUser($userId)
                ->expense()
                ->where('category', $budget->category)
                ->inMonth($now->year, $now->month)
                ->sum('amount');

            $totalSpentCombined += $spent;

            $percent = $budget->amount > 0 ? round(($spent / $budget->amount) * 100) : 0;

            if ($percent > 100) {
                $status = 'Over Budget';
                $statusClass = 'rose';
                $overBudgetCount++;
            } elseif ($percent >= 80) {
                $status = 'Near Limit';
                $statusClass = 'amber';
                $nearLimitCount++;
            } else {
                $status = 'On Track';
                $statusClass = 'emerald';
            }

            $remaining = $budget->amount - $spent;

            return [
                'id' => $budget->id,
                'category' => $budget->category,
                'amount' => $budget->amount,
                'spent' => $spent,
                'percent' => $percent,
                'status' => $status,
                'status_class' => $statusClass,
                'remaining' => $remaining,
            ];
        });

        $combinedPercent = $totalBudgetLimit > 0 ? min(round(($totalSpentCombined / $totalBudgetLimit) * 100), 100) : 0;

        return view('budget.index', compact(
            'totalBudgetLimit',
            'totalSpentCombined',
            'combinedPercent',
            'nearLimitCount',
            'overBudgetCount',
            'budgetDetails'
        ));
    }

    public function store(StoreBudgetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Budget::updateOrCreate(
            ['user_id' => Auth::id(), 'category' => $validated['category']],
            ['amount' => $validated['amount']]
        );

        return redirect()->back()->with('success', 'Anggaran berhasil disimpan!');
    }

    public function update(StoreBudgetRequest $request, Budget $budget): RedirectResponse
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $budget->update([
            'category' => $validated['category'],
            'amount' => $validated['amount'],
        ]);

        return redirect()->back()->with('success', 'Anggaran berhasil diperbarui!');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        if ($budget->user_id !== Auth::id()) {
            abort(403);
        }

        $budget->delete();

        return redirect()->back()->with('success', 'Anggaran berhasil dihapus!');
    }
}
