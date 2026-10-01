<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\SavingGoal;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $now = Carbon::now();

        // 1. Stat Summary
        $totalIncomeAll = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalExpenseAll = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $totalBalance = $totalIncomeAll - $totalExpenseAll;

        // Current & Previous Month Income
        $incomeThisMonth = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $incomePrevMonth = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereYear('date', $now->copy()->subMonth()->year)
            ->whereMonth('date', $now->copy()->subMonth()->month)
            ->sum('amount');

        $incomeChangePercent = $incomePrevMonth > 0 
            ? (($incomeThisMonth - $incomePrevMonth) / $incomePrevMonth) * 100 
            : 0;

        // Current & Previous Month Expenses
        $expenseThisMonth = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $now->year)
            ->whereMonth('date', $now->month)
            ->sum('amount');

        $expensePrevMonth = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereYear('date', $now->copy()->subMonth()->year)
            ->whereMonth('date', $now->copy()->subMonth()->month)
            ->sum('amount');

        $expenseChangePercent = $expensePrevMonth > 0 
            ? (($expenseThisMonth - $expensePrevMonth) / $expensePrevMonth) * 100 
            : 0;

        // Total Savings across goals
        $totalSavings = SavingGoal::where('user_id', $userId)->sum('current_amount');

        // 2. Budget Pulse Widget
        $budgets = Budget::where('user_id', $userId)->get();
        $budgetPulse = $budgets->map(function ($budget) use ($userId, $now) {
            $spent = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->where('category', $budget->category)
                ->whereYear('date', $now->year)
                ->whereMonth('date', $now->month)
                ->sum('amount');

            $percent = $budget->amount > 0 ? min(round(($spent / $budget->amount) * 100), 150) : 0;
            return [
                'category' => $budget->category,
                'limit' => $budget->amount,
                'spent' => $spent,
                'percent' => $percent,
            ];
        })->sortByDesc('percent')->values();

        // 3. Savings Goals Widget
        $savingsGoals = SavingGoal::where('user_id', $userId)->get()->map(function ($goal) {
            $percent = $goal->target_amount > 0 ? min(round(($goal->current_amount / $goal->target_amount) * 100), 100) : 0;
            return [
                'id' => $goal->id,
                'name' => $goal->name,
                'target_amount' => $goal->target_amount,
                'current_amount' => $goal->current_amount,
                'percent' => $percent,
                'color' => $goal->color ?? 'blue',
            ];
        });

        // 4. Recent Transactions
        $recentTransactions = Transaction::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        // 5. Cash Flow Overview Chart Data (This Month by Days or 6 Months)
        $chartFilter = $request->get('filter', 'this_month');
        $chartData = $this->getChartData($userId, $chartFilter);

        return view('dashboard', compact(
            'totalBalance',
            'incomeThisMonth',
            'incomeChangePercent',
            'expenseThisMonth',
            'expenseChangePercent',
            'totalSavings',
            'budgetPulse',
            'savingsGoals',
            'recentTransactions',
            'chartData',
            'chartFilter'
        ));
    }

    private function getChartData($userId, $filter)
    {
        $now = Carbon::now();
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        if ($filter === 'this_week') {
            $startOfWeek = $now->copy()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $day = $startOfWeek->copy()->addDays($i);
                $labels[] = $day->translatedFormat('D, d M');
                $incomeData[] = (float) Transaction::where('user_id', $userId)->where('type', 'income')->whereDate('date', $day->toDateString())->sum('amount');
                $expenseData[] = (float) Transaction::where('user_id', $userId)->where('type', 'expense')->whereDate('date', $day->toDateString())->sum('amount');
            }
        } elseif ($filter === 'this_year') {
            for ($m = 1; $m <= 12; $m++) {
                $monthDate = Carbon::create($now->year, $m, 1);
                $labels[] = $monthDate->translatedFormat('M');
                $incomeData[] = (float) Transaction::where('user_id', $userId)->where('type', 'income')->whereYear('date', $now->year)->whereMonth('date', $m)->sum('amount');
                $expenseData[] = (float) Transaction::where('user_id', $userId)->where('type', 'expense')->whereYear('date', $now->year)->whereMonth('date', $m)->sum('amount');
            }
        } else {
            // Default: this_month (days 1 to days in month)
            $daysInMonth = $now->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dateStr = Carbon::create($now->year, $now->month, $d)->toDateString();
                $labels[] = (string) $d;
                $incomeData[] = (float) Transaction::where('user_id', $userId)->where('type', 'income')->whereDate('date', $dateStr)->sum('amount');
                $expenseData[] = (float) Transaction::where('user_id', $userId)->where('type', 'expense')->whereDate('date', $dateStr)->sum('amount');
            }
        }

        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
}
