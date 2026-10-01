@extends('layouts.app')

@section('title', 'Expenses - Spending Analysis')

@section('content')
<div class="space-y-8">

    <!-- Header Title & Action Button (Matching Screenshot 4) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900">Expenses</h1>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Where your money goes</p>
        </div>

        <button onclick="document.getElementById('modal-add-expense').classList.remove('hidden')"
            class="px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Expense</span>
        </button>
    </div>

    <!-- Stat Cards Grid (3 Cards Matching Screenshot 4) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Card 1: Total Expenses This Month (Crimson Red Card) -->
        <div class="bg-rose-600 p-6 rounded-3xl text-white shadow-xl shadow-rose-600/15 flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-100">Total Expenses This Month</span>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold font-outfit tracking-tight">
                Rp {{ number_format($totalExpenseThisMonth, 0, ',', '.') }}
            </div>
        </div>

        <!-- Card 2: Daily Average -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Daily Average</span>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">
                Rp {{ number_format($dailyAverage, 0, ',', '.') }}
            </div>
        </div>

        <!-- Card 3: Highest Spend Category -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Highest Spend Category</span>
            <div class="mt-2">
                <div class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">
                    {{ $highestCategory }}
                </div>
                <div class="text-xs text-slate-400 font-semibold mt-1">
                    Rp {{ number_format($highestCategoryAmount, 0, ',', '.') }}
                </div>
            </div>
        </div>

    </div>

    <!-- Middle Section: Donut Chart, Top Spending Ranked, Daily Spending Chart -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- 1. Spending by Category Donut Chart -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
            <div>
                <h3 class="text-base font-bold font-outfit text-slate-900">Spending by Category</h3>
                <p class="text-xs font-medium text-slate-400">This month</p>
            </div>
            <div class="h-56 relative flex items-center justify-center">
                <canvas id="categoryDonutChart"></canvas>
            </div>
        </div>

        <!-- 2. Top Spending Categories Ranked List -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
            <div>
                <h3 class="text-base font-bold font-outfit text-slate-900">Top Spending Categories</h3>
                <p class="text-xs font-medium text-slate-400">Ranked by share of this month's spending</p>
            </div>

            <div class="space-y-3.5">
                @forelse($topCategories as $top)
                    <div class="flex items-center gap-3 text-xs">
                        <span class="w-5 font-bold text-slate-400 text-center">{{ $top['rank'] }}</span>
                        <div class="flex-1">
                            <div class="flex items-center justify-between font-bold text-slate-800 mb-1">
                                <span>{{ $top['category'] }}</span>
                                <span class="text-slate-500 font-normal">
                                    <span class="font-bold text-slate-700">{{ $top['percent'] }}%</span> Rp {{ number_format($top['amount'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-orange-500" style="width: {{ $top['percent'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada pengeluaran.</p>
                @endforelse
            </div>
        </div>

        <!-- 3. Daily Spending Bar Chart -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
            <div>
                <h3 class="text-base font-bold font-outfit text-slate-900">Daily Spending</h3>
                <p class="text-xs font-medium text-slate-400">This month, day by day</p>
            </div>
            <div class="h-56 relative">
                <canvas id="dailySpendingChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Expense Entries Table List (Matching Screenshot 4) -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
        <h3 class="text-lg font-bold font-outfit text-slate-900">Expense Entries</h3>

        <div class="space-y-3">
            @forelse($expenses as $exp)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/70 border border-slate-100 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>{{ $exp->description }}</span>
                                @if($exp->receipt_path)
                                    <a href="{{ asset('storage/' . $exp->receipt_path) }}" target="_blank" class="text-blue-500 hover:underline text-xs">
                                        <i class="fa-solid fa-paperclip"></i> Bukti
                                    </a>
                                @endif
                            </div>
                            <div class="text-xs text-slate-400 font-medium">
                                {{ $exp->category }} • {{ \Carbon\Carbon::parse($exp->date)->format('d M Y') }} • {{ $exp->payment_method }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-base font-extrabold font-outfit text-slate-900">
                            -Rp {{ number_format($exp->amount, 0, ',', '.') }}
                        </span>

                        <div class="flex items-center gap-1">
                            <button onclick="openEditExpenseModal({{ json_encode($exp) }})" class="w-8 h-8 rounded-full hover:bg-slate-200/70 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>

                            <form action="{{ route('expenses.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full hover:bg-rose-100 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-400 py-6 text-center">Belum ada catatan pengeluaran.</p>
            @endforelse
        </div>

        @if($expenses->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Add Expense -->
<div id="modal-add-expense" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Tambah Pengeluaran</h3>
            <button onclick="document.getElementById('modal-add-expense').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Deskripsi</label>
                <input type="text" name="description" required placeholder="misal: Concert ticket — Musik Nusantara"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Amount (Rp)</label>
                    <input type="number" step="0.01" name="amount" required placeholder="100000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 bg-white">
                        <option value="Food">Food (Makanan)</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Bills">Bills (Tagihan)</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Health">Health</option>
                        <option value="Education">Education</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Date (Pilih Tanggal)</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Payment Method</label>
                    <select name="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 bg-white">
                        <option value="GoPay">GoPay</option>
                        <option value="BCA">BCA</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="OVO">OVO</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Bukti (Opsional)</label>
                <input type="file" name="receipt" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add-expense').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Expense -->
<div id="modal-edit-expense" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Edit Pengeluaran</h3>
            <button onclick="document.getElementById('modal-edit-expense').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-expense" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Deskripsi</label>
                <input type="text" id="edit-exp-description" name="description" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Amount (Rp)</label>
                    <input type="number" step="0.01" id="edit-exp-amount" name="amount" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori</label>
                    <select id="edit-exp-category" name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 bg-white">
                        <option value="Food">Food (Makanan)</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Bills">Bills (Tagihan)</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Health">Health</option>
                        <option value="Education">Education</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Date (Pilih Tanggal)</label>
                    <input type="date" id="edit-exp-date" name="date" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Payment Method</label>
                    <input type="text" id="edit-exp-payment_method" name="payment_method" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Bukti Baru (Opsional)</label>
                <input type="file" name="receipt" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-expense').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20">Perbarui Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditExpenseModal(exp) {
        document.getElementById('form-edit-expense').action = `/expenses/${exp.id}`;
        document.getElementById('edit-exp-description').value = exp.description;
        document.getElementById('edit-exp-amount').value = exp.amount;
        document.getElementById('edit-exp-category').value = exp.category;

        const d = new Date(exp.date);
        const dateStr = d.toISOString().split('T')[0];
        document.getElementById('edit-exp-date').value = dateStr;
        document.getElementById('edit-exp-payment_method').value = exp.payment_method;

        document.getElementById('modal-edit-expense').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Donut Chart
        const donutCtx = document.getElementById('categoryDonutChart').getContext('2d');
        const topCategoriesData = @json($topCategories);
        
        const donutLabels = topCategoriesData.map(item => item.category);
        const donutValues = topCategoriesData.map(item => item.amount);

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{
                    data: donutValues,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Daily Bar Chart
        const dailyCtx = document.getElementById('dailySpendingChart').getContext('2d');
        const dailyLabels = @json($dailyLabels);
        const dailyValues = @json($dailyValues);

        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Daily Spending',
                    data: dailyValues,
                    backgroundColor: '#f97316',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { size: 9 },
                            callback: function(v) {
                                if (v >= 1000) return (v / 1000) + ' rb';
                                return v;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
