<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Finrai Cashflow System)
|--------------------------------------------------------------------------
|
| Sesuai arsitektur MVC Laravel:
| Routes menangani URL endpoint dan menghubungkan URL ke Controller.
|
*/

// =========================================================================
// 1. PUBLIC & GUEST ROUTES
// =========================================================================

// Landing Page (Akses publik)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('home');
})->name('home');

// Autentikasi Pengguna (Hanya untuk Guest)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register ("Buat Akun")
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// =========================================================================
// 2. AUTHENTICATED ROUTES (Memerlukan Login)
// =========================================================================

Route::middleware('auth')->group(function () {
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Transaksi (CRUD)
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::put('/{transaction}', [TransactionController::class, 'update'])->name('update');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Pemasukan / Income (CRUD & Trend Analytics)
    Route::prefix('income')->name('income.')->group(function () {
        Route::get('/', [IncomeController::class, 'index'])->name('index');
        Route::post('/', [IncomeController::class, 'store'])->name('store');
        Route::put('/{income}', [IncomeController::class, 'update'])->name('update');
        Route::delete('/{income}', [IncomeController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Pengeluaran / Expenses (CRUD & Trend Analytics)
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy');
    });

    // Anggaran Keuangan / Budgeting (CRUD)
    Route::prefix('budget')->name('budget.')->group(function () {
        Route::get('/', [BudgetController::class, 'index'])->name('index');
        Route::post('/', [BudgetController::class, 'store'])->name('store');
        Route::put('/{budget}', [BudgetController::class, 'update'])->name('update');
        Route::delete('/{budget}', [BudgetController::class, 'destroy'])->name('destroy');
    });

    // Target Tabungan / Saving Goals (CRUD & Setoran)
    Route::prefix('savings')->name('savings.')->group(function () {
        Route::get('/', [SavingGoalController::class, 'index'])->name('index');
        Route::post('/', [SavingGoalController::class, 'store'])->name('store');
        Route::put('/{savingGoal}', [SavingGoalController::class, 'update'])->name('update');
        Route::post('/{savingGoal}/deposit', [SavingGoalController::class, 'deposit'])->name('deposit');
        Route::delete('/{savingGoal}', [SavingGoalController::class, 'destroy'])->name('destroy');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
