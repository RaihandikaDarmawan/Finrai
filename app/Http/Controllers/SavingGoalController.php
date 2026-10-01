<?php

namespace App\Http\Controllers;

use App\Http\Requests\Savings\DepositSavingGoalRequest;
use App\Http\Requests\Savings\StoreSavingGoalRequest;
use App\Models\SavingGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SavingGoalController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        $goals = SavingGoal::forUser($userId)->get();

        $totalSaved = $goals->sum('current_amount');
        $totalTarget = $goals->sum('target_amount');

        $formattedGoals = $goals->map(function ($goal) {
            return [
                'id' => $goal->id,
                'name' => $goal->name,
                'target_amount' => $goal->target_amount,
                'initial_deposit' => $goal->initial_deposit,
                'current_amount' => $goal->current_amount,
                'target_date' => $goal->target_date,
                'color' => $goal->color ?? 'blue',
                'percent' => $goal->progress_percent,
            ];
        });

        return view('savings.index', compact('totalSaved', 'totalTarget', 'formattedGoals'));
    }

    public function store(StoreSavingGoalRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $initialDeposit = $validated['initial_deposit'] ?? 0;

        SavingGoal::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'target_amount' => $validated['target_amount'],
            'initial_deposit' => $initialDeposit,
            'current_amount' => $initialDeposit,
            'target_date' => $validated['target_date'] ?? null,
            'color' => $validated['color'] ?? 'blue',
        ]);

        return redirect()->back()->with('success', 'Target Tabungan berhasil dibuat!');
    }

    public function update(StoreSavingGoalRequest $request, SavingGoal $savingGoal): RedirectResponse
    {
        if ($savingGoal->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();

        $savingGoal->update([
            'name' => $validated['name'],
            'target_amount' => $validated['target_amount'],
            'initial_deposit' => $validated['initial_deposit'] ?? 0,
            'current_amount' => $request->input('current_amount', $savingGoal->current_amount),
            'target_date' => $validated['target_date'] ?? null,
            'color' => $validated['color'] ?? 'blue',
        ]);

        return redirect()->back()->with('success', 'Target Tabungan berhasil diperbarui!');
    }

    public function deposit(DepositSavingGoalRequest $request, SavingGoal $savingGoal): RedirectResponse
    {
        $validated = $request->validated();

        $savingGoal->current_amount += $validated['deposit_amount'];
        $savingGoal->save();

        return redirect()->back()->with('success', 'Setoran tabungan berhasil ditambahkan!');
    }

    public function destroy(SavingGoal $savingGoal): RedirectResponse
    {
        if ($savingGoal->user_id !== Auth::id()) {
            abort(403);
        }

        $savingGoal->delete();

        return redirect()->back()->with('success', 'Target Tabungan berhasil dihapus!');
    }
}
