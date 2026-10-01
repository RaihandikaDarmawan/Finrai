@extends('layouts.app')

@section('title', 'Dashboard - Finrai')

@section('content')
<div class="space-y-8">
    
    <!-- Top Greeting & Quick Action Header (Matching Screenshot 1) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900 flex items-center gap-2">
                <span>Selamat Datang, {{ Auth::user()->name ?? 'Raihan' }}!</span>
                <span class="inline-block animate-pulse">👋</span>
            </h1>
            <p class="text-sm font-medium text-slate-400 mt-1">
                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </p>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="document.getElementById('modal-add-transaction').classList.remove('hidden')"
                class="px-4 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Add Transaction</span>
            </button>

            <a href="{{ route('income.index') }}"
                class="px-4 py-2.5 rounded-full bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs sm:text-sm font-semibold transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-down-left text-emerald-500"></i>
                <span>Add Income</span>
            </a>

            <a href="{{ route('budget.index') }}"
                class="px-4 py-2.5 rounded-full bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs sm:text-sm font-semibold transition-colors flex items-center gap-2">
                <i class="fa-solid fa-sliders text-amber-500"></i>
                <span>Set Budget</span>
            </a>

            <a href="{{ route('savings.index') }}"
                class="px-4 py-2.5 rounded-full bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs sm:text-sm font-semibold transition-colors flex items-center gap-2">
                <i class="fa-solid fa-bullseye text-indigo-500"></i>
                <span>Add Goal</span>
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards Grid (4 Cards Matching Screenshot 1) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- 1. Total Balance Card (Dark Blue Gradient) -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-700 p-6 rounded-3xl text-white shadow-xl shadow-blue-500/15 relative overflow-hidden flex flex-col justify-between min-h-[160px]">
            <div class="flex items-center justify-between text-blue-100">
                <span class="text-xs font-bold uppercase tracking-wider">Total Balance</span>
                <div class="flex items-center gap-2">
                    <button onclick="toggleBalanceVisibility()" class="text-blue-200 hover:text-white transition-colors" title="Sembunyikan / Tampilkan">
                        <i id="eye-icon" class="fa-solid fa-eye text-sm"></i>
                    </button>
                    <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div id="balance-amount" class="text-2xl sm:text-3xl font-extrabold font-outfit tracking-tight">
                    Rp {{ number_format($totalBalance, 0, ',', '.') }}
                </div>
                <div id="balance-hidden" class="text-2xl sm:text-3xl font-extrabold font-outfit tracking-tight hidden">
                    Rp ••••••••••
                </div>
                <div class="mt-2 text-[11px] font-medium text-blue-100/90 flex items-center gap-1.5">
                    <span class="px-1.5 py-0.5 rounded-md bg-white/20 font-bold text-white">
                        <i class="fa-solid fa-arrow-trend-up"></i> +12.5%
                    </span>
                    <span>dari bulan sebelumnya</span>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
        </div>

        <!-- 2. Income Card (Light Green) -->
        <div class="bg-emerald-50/70 p-6 rounded-3xl border border-emerald-200/60 flex flex-col justify-between min-h-[160px]">
            <div class="flex items-center justify-between text-emerald-700">
                <span class="text-xs font-bold uppercase tracking-wider">Income</span>
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-arrow-up-right rotate-180"></i>
                </div>
            </div>

            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-extrabold font-outfit text-emerald-950 tracking-tight">
                    Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[11px] font-semibold text-emerald-700 flex items-center gap-1.5">
                    <span class="px-1.5 py-0.5 rounded-md bg-emerald-100 text-emerald-700 font-bold">
                        <i class="fa-solid fa-arrow-trend-up"></i> +{{ number_format(abs($incomeChangePercent), 1) }}%
                    </span>
                    <span>dari bulan sebelumnya</span>
                </div>
            </div>
        </div>

        <!-- 3. Expenses Card (Light Red / Pink) -->
        <div class="bg-rose-50/70 p-6 rounded-3xl border border-rose-200/60 flex flex-col justify-between min-h-[160px]">
            <div class="flex items-center justify-between text-rose-700">
                <span class="text-xs font-bold uppercase tracking-wider">Expenses</span>
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-arrow-up-right"></i>
                </div>
            </div>

            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-extrabold font-outfit text-rose-950 tracking-tight">
                    Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[11px] font-semibold text-rose-700 flex items-center gap-1.5">
                    <span class="px-1.5 py-0.5 rounded-md bg-rose-100 text-rose-700 font-bold">
                        <i class="fa-solid fa-arrow-trend-down"></i> {{ number_format($expenseChangePercent, 1) }}%
                    </span>
                    <span>dari bulan sebelumnya</span>
                </div>
            </div>
        </div>

        <!-- 4. Savings Card (Light Purple) -->
        <div class="bg-purple-50/70 p-6 rounded-3xl border border-purple-200/60 flex flex-col justify-between min-h-[160px]">
            <div class="flex items-center justify-between text-purple-700">
                <span class="text-xs font-bold uppercase tracking-wider">Savings</span>
                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-piggy-bank"></i>
                </div>
            </div>

            <div class="mt-4">
                <div class="text-2xl sm:text-3xl font-extrabold font-outfit text-purple-950 tracking-tight">
                    Rp {{ number_format($totalSavings, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs font-medium text-purple-600">
                    across your savings goals
                </div>
            </div>
        </div>

    </div>

    <!-- Middle Section: Cash Flow Chart + Right Widgets (Budget Pulse & Savings Goals) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Cash Flow Overview Chart -->
        <div class="lg:col-span-2 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-2xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold font-outfit text-slate-900">Cash Flow Overview</h2>
                    <p class="text-xs font-medium text-slate-400">Income vs Expenses</p>
                </div>

                <!-- Time Filter Tabs -->
                <div class="flex items-center bg-slate-100 p-1 rounded-full text-xs font-bold text-slate-600">
                    <a href="{{ route('dashboard', ['filter' => 'this_week']) }}" 
                        class="px-3 py-1.5 rounded-full transition-all {{ $chartFilter === 'this_week' ? 'bg-white text-blue-600 shadow-xs' : 'hover:text-slate-900' }}">
                        This Week
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'this_month']) }}" 
                        class="px-3 py-1.5 rounded-full transition-all {{ $chartFilter === 'this_month' ? 'bg-white text-blue-600 shadow-xs' : 'hover:text-slate-900' }}">
                        This Month
                    </a>
                    <a href="{{ route('dashboard', ['filter' => 'this_year']) }}" 
                        class="px-3 py-1.5 rounded-full transition-all {{ $chartFilter === 'this_year' ? 'bg-white text-blue-600 shadow-xs' : 'hover:text-slate-900' }}">
                        This Year
                    </a>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="h-64 sm:h-72 relative">
                <canvas id="cashflowChart"></canvas>
            </div>
        </div>

        <!-- Right 1 Col: Widgets -->
        <div class="space-y-6">
            
            <!-- Widget 1: Budget Pulse -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold font-outfit text-slate-900">Budget Pulse</h3>
                    <a href="{{ route('budget.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Details</a>
                </div>

                <div class="space-y-4">
                    @forelse($budgetPulse->take(3) as $pulse)
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-700">{{ $pulse['category'] }}</span>
                                <span class="{{ $pulse['percent'] > 100 ? 'text-rose-600 font-extrabold' : ($pulse['percent'] >= 80 ? 'text-amber-600 font-bold' : 'text-emerald-600') }}">
                                    {{ $pulse['percent'] }}%
                                </span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 {{ $pulse['percent'] > 100 ? 'bg-rose-500' : ($pulse['percent'] >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                    style="width: {{ min($pulse['percent'], 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-2">Belum ada anggaran yang diset.</p>
                    @endforelse
                </div>
            </div>

            <!-- Widget 2: Savings Goals -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold font-outfit text-slate-900">Savings Goals</h3>
                    <a href="{{ route('savings.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Details</a>
                </div>

                <div class="space-y-4">
                    @forelse($savingsGoals->take(3) as $goal)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-xs"
                                style="background-color: {{ $goal['color'] === 'emerald' ? '#10b981' : ($goal['color'] === 'purple' ? '#a855f7' : ($goal['color'] === 'amber' ? '#f59e0b' : '#3b82f6')) }}">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between text-xs font-bold mb-1">
                                    <span class="text-slate-800">{{ $goal['name'] }}</span>
                                    <span class="text-slate-500">{{ $goal['percent'] }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                        style="width: {{ $goal['percent'] }}%; background-color: {{ $goal['color'] === 'emerald' ? '#10b981' : ($goal['color'] === 'purple' ? '#a855f7' : ($goal['color'] === 'amber' ? '#f59e0b' : '#3b82f6')) }}"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-2">Belum ada target tabungan.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Bottom Section: Recent Transactions Table -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold font-outfit text-slate-900">Recent Transactions</h3>
                <p class="text-xs font-medium text-slate-400">Your latest income and expenses</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                View All <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-slate-500 text-xs whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-900 font-bold">
                                {{ $tx->description }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    <i class="fa-solid fa-tag text-[10px] text-slate-400"></i>
                                    {{ $tx->category }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($tx->type === 'income')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        Income
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                        Expense
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-extrabold font-outfit whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-slate-900' }}">
                                {{ $tx->type === 'income' ? '+Rp ' : 'Rp -' }}{{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-sm">
                                Belum ada transaksi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Quick Add Transaction -->
<div id="modal-add-transaction" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 relative border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Add Transaction</h3>
            <button onclick="document.getElementById('modal-add-transaction').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Tipe Transaksi</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" checked class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border border-slate-200 text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all">
                            Pemasukan (Income)
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border border-slate-200 text-xs font-bold peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 transition-all">
                            Pengeluaran (Expense)
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Deskripsi</label>
                <input type="text" name="description" required placeholder="misal: Gaji Bulanan / Belanja Supermarket"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Jumlah (Rp)</label>
                    <input type="number" step="0.01" name="amount" required placeholder="500000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori</label>
                    <input type="text" name="category" required placeholder="Food / Salary"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Tanggal</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Metode Pembayaran</label>
                    <input type="text" name="payment_method" required value="BCA" placeholder="BCA / GoPay"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Bukti (Opsional)</label>
                <input type="file" name="receipt" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add-transaction').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleBalanceVisibility() {
        const amount = document.getElementById('balance-amount');
        const hidden = document.getElementById('balance-hidden');
        const icon = document.getElementById('eye-icon');

        if (amount.classList.contains('hidden')) {
            amount.classList.remove('hidden');
            hidden.classList.add('hidden');
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            amount.classList.add('hidden');
            hidden.classList.remove('hidden');
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Income',
                        data: chartData.income,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Expenses',
                        data: chartData.expense,
                        backgroundColor: '#f97316',
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + ' jt';
                                if (value >= 1000) return (value / 1000) + ' rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
