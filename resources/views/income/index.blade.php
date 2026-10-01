@extends('layouts.app')

@section('title', 'Income - Financial Sources')

@section('content')
<div class="space-y-8">

    <!-- Header & Action Button (Matching Screenshot 3) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900">Income</h1>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Where your money comes from</p>
        </div>

        <button onclick="document.getElementById('modal-add-income').classList.remove('hidden')"
            class="px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Income</span>
        </button>
    </div>

    <!-- Top Stat Cards (3 Cards Matching Screenshot 3) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Card 1: Total Income This Month (Dark Emerald Green Card) -->
        <div class="bg-emerald-600 p-6 rounded-3xl text-white shadow-xl shadow-emerald-600/15 flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-100">Total Income This Month</span>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold font-outfit tracking-tight">
                Rp {{ number_format($totalIncomeThisMonth, 0, ',', '.') }}
            </div>
        </div>

        <!-- Card 2: Previous Month -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Previous Month</span>
            <div class="mt-2 text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">
                Rp {{ number_format($totalIncomePrevMonth, 0, ',', '.') }}
            </div>
        </div>

        <!-- Card 3: Month-over-Month -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs flex flex-col justify-between min-h-[140px]">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Month-over-Month</span>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold font-outfit text-emerald-600 tracking-tight">
                    +{{ number_format(abs($momChangePercent), 1) }}%
                </div>
                <div class="text-xs text-slate-400 font-medium mt-1">
                    ≈ Rp {{ number_format($dailyAverageThisMonth, 0, ',', '.') }}/day this month
                </div>
            </div>
        </div>

    </div>

    <!-- Middle Section: Income Trend Line Chart & Income Sources -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Income Trend (Last 6 Months Area Chart) -->
        <div class="lg:col-span-2 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
            <div>
                <h3 class="text-lg font-bold font-outfit text-slate-900">Income Trend</h3>
                <p class="text-xs font-medium text-slate-400">Last 6 months</p>
            </div>
            <div class="h-64 relative">
                <canvas id="incomeTrendChart"></canvas>
            </div>
        </div>

        <!-- Income Sources (This Month Breakdown) -->
        <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-2xs space-y-5">
            <div>
                <h3 class="text-lg font-bold font-outfit text-slate-900">Income Sources</h3>
                <p class="text-xs font-medium text-slate-400">This month</p>
            </div>

            <div class="space-y-4">
                @forelse($sources as $src)
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-briefcase text-emerald-500"></i>
                                {{ $src['category'] }}
                            </span>
                            <span class="text-slate-600">
                                Rp {{ number_format($src['amount'], 0, ',', '.') }} - {{ $src['percent'] }}%
                            </span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $src['percent'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4">Belum ada pemasukan bulan ini.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Income Entries Table List (Matching Screenshot 3) -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
        <h3 class="text-lg font-bold font-outfit text-slate-900">Income Entries</h3>

        <div class="space-y-3">
            @forelse($incomes as $inc)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/70 border border-slate-100 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-sparkles"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>{{ $inc->description }}</span>
                                @if($inc->receipt_path)
                                    <a href="{{ asset('storage/' . $inc->receipt_path) }}" target="_blank" class="text-blue-500 hover:underline text-xs">
                                        <i class="fa-solid fa-paperclip"></i> Bukti
                                    </a>
                                @endif
                            </div>
                            <div class="text-xs text-slate-400 font-medium">
                                {{ $inc->category }} • {{ \Carbon\Carbon::parse($inc->date)->format('d M Y') }} • {{ $inc->payment_method }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <span class="text-base font-extrabold font-outfit text-emerald-600">
                            +Rp {{ number_format($inc->amount, 0, ',', '.') }}
                        </span>

                        <div class="flex items-center gap-1">
                            <button onclick="openEditIncomeModal({{ json_encode($inc) }})" class="w-8 h-8 rounded-full hover:bg-slate-200/70 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>

                            <form action="{{ route('income.destroy', $inc->id) }}" method="POST" onsubmit="return confirm('Hapus pemasukan ini?')">
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
                <p class="text-sm text-slate-400 py-6 text-center">Belum ada catatan pemasukan.</p>
            @endforelse
        </div>

        @if($incomes->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $incomes->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Add Income -->
<div id="modal-add-income" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Tambah Pemasukan</h3>
            <button onclick="document.getElementById('modal-add-income').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('income.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Deskripsi</label>
                <input type="text" name="description" required placeholder="misal: Quarterly performance bonus"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Amount (Rp)</label>
                    <input type="number" step="0.01" name="amount" required placeholder="500000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori</label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white">
                        <option value="Salary">Salary (Gaji)</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Bonus">Bonus</option>
                        <option value="Investment">Investment</option>
                        <option value="Business">Business</option>
                        <option value="Gift">Gift</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Date (Pilih Tanggal)</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Payment Method</label>
                    <select name="payment_method" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white">
                        <option value="BCA">BCA</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BNI">BNI</option>
                        <option value="GoPay">GoPay</option>
                        <option value="OVO">OVO</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Bukti (Opsional)</label>
                <input type="file" name="receipt" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add-income').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-500/20">Simpan Pemasukan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Income -->
<div id="modal-edit-income" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Edit Pemasukan</h3>
            <button onclick="document.getElementById('modal-edit-income').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-income" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Deskripsi</label>
                <input type="text" id="edit-inc-description" name="description" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Amount (Rp)</label>
                    <input type="number" step="0.01" id="edit-inc-amount" name="amount" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori</label>
                    <select id="edit-inc-category" name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white">
                        <option value="Salary">Salary (Gaji)</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Bonus">Bonus</option>
                        <option value="Investment">Investment</option>
                        <option value="Business">Business</option>
                        <option value="Gift">Gift</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Date (Pilih Tanggal)</label>
                    <input type="date" id="edit-inc-date" name="date" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Payment Method</label>
                    <input type="text" id="edit-inc-payment_method" name="payment_method" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Bukti Baru (Opsional)</label>
                <input type="file" name="receipt" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-income').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-500/20">Perbarui Pemasukan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditIncomeModal(inc) {
        document.getElementById('form-edit-income').action = `/income/${inc.id}`;
        document.getElementById('edit-inc-description').value = inc.description;
        document.getElementById('edit-inc-amount').value = inc.amount;
        document.getElementById('edit-inc-category').value = inc.category;

        const d = new Date(inc.date);
        const dateStr = d.toISOString().split('T')[0];
        document.getElementById('edit-inc-date').value = dateStr;
        document.getElementById('edit-inc-payment_method').value = inc.payment_method;

        document.getElementById('modal-edit-income').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('incomeTrendChart').getContext('2d');
        const labels = @json($trendLabels);
        const values = @json($trendValues);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Income Trend',
                    data: values,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#10b981',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Income: Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + ' jt';
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
