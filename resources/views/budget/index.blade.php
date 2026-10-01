@extends('layouts.app')

@section('title', 'Budget - Monthly Spending Limits')

@section('content')
<div class="space-y-8">

    <!-- Header Title & Action Button (Matching Screenshot 5) -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900">Budget</h1>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Monthly spending limits per category</p>
        </div>

        <button onclick="document.getElementById('modal-add-budget').classList.remove('hidden')"
            class="px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Budget</span>
        </button>
    </div>

    <!-- Top Total Spent Summary Banner Card (Matching Screenshot 5) -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Spent This Month</span>
                <div class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">
                    Rp {{ number_format($totalSpentCombined, 0, ',', '.') }} <span class="text-slate-400 text-xl sm:text-2xl font-normal">/ Rp {{ number_format($totalBudgetLimit, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="flex items-center gap-2">
                @if($nearLimitCount > 0)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>{{ $nearLimitCount }} near limit</span>
                    </span>
                @endif
                @if($overBudgetCount > 0)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $overBudgetCount }} over budget</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="space-y-2">
            <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $combinedPercent > 100 ? 'bg-rose-500' : ($combinedPercent >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                    style="width: {{ min($combinedPercent, 100) }}%"></div>
            </div>
            <div class="text-xs font-bold text-slate-500">
                {{ $combinedPercent }}% of the combined budget used
            </div>
        </div>
    </div>

    <!-- Category Budget Cards Grid (Matching Screenshot 5) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($budgetDetails as $b)
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-4 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="space-y-4">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-base font-bold">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold font-outfit text-slate-900">{{ $b['category'] }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold mt-0.5
                                    {{ $b['status'] === 'On Track' ? 'bg-emerald-100 text-emerald-700' : ($b['status'] === 'Near Limit' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                    {{ $b['status'] }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-1">
                            <button onclick="openEditBudgetModal({{ json_encode($b) }})" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <form action="{{ route('budget.destroy', $b['id']) }}" method="POST" onsubmit="return confirm('Hapus anggaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Spent / Limit Text -->
                    <div>
                        <div class="text-xl font-extrabold font-outfit text-slate-900">
                            Rp {{ number_format($b['spent'], 0, ',', '.') }} <span class="text-slate-400 text-base font-normal">/ Rp {{ number_format($b['amount'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1.5">
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $b['percent'] > 100 ? 'bg-rose-500' : ($b['percent'] >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                style="width: {{ min($b['percent'], 100) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500">
                            <span>{{ $b['percent'] }}% used</span>
                            @if($b['remaining'] >= 0)
                                <span class="text-slate-600">Rp {{ number_format($b['remaining'], 0, ',', '.') }} left</span>
                            @else
                                <span class="text-rose-600 font-extrabold">Over by Rp {{ number_format(abs($b['remaining']), 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Warning Banner if Near Limit / Over Budget -->
                @if($b['percent'] >= 80 && $b['remaining'] >= 0)
                    <div class="mt-2 p-3 rounded-2xl bg-amber-50 border border-amber-200/60 text-amber-800 text-xs font-medium flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                        <span>You're close to the 80% threshold — Rp {{ number_format($b['remaining'], 0, ',', '.') }} left.</span>
                    </div>
                @elseif($b['percent'] > 100)
                    <div class="mt-2 p-3 rounded-2xl bg-rose-50 border border-rose-200/60 text-rose-800 text-xs font-medium flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                        <span>Melebihi anggaran sebesar Rp {{ number_format(abs($b['remaining']), 0, ',', '.') }}!</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-3 bg-white p-12 rounded-3xl border border-slate-200/80 text-center text-slate-400">
                <i class="fa-solid fa-sliders text-3xl mb-2 text-slate-300"></i>
                <p class="text-sm font-medium">Belum ada kategori anggaran. Klik tombol <b>+ Add Budget</b> untuk menentukan batas pengeluaran.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Add Budget -->
<div id="modal-add-budget" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Tambah / Set Anggaran</h3>
            <button onclick="document.getElementById('modal-add-budget').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('budget.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori Pengeluaran</label>
                <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
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

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Total Estimasi Budget / Bulan (Rp)</label>
                <input type="number" step="0.01" name="amount" required placeholder="800000"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add-budget').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">Simpan Anggaran</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Budget -->
<div id="modal-edit-budget" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Edit Anggaran Kategori</h3>
            <button onclick="document.getElementById('modal-edit-budget').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-budget" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori Pengeluaran</label>
                <input type="text" id="edit-b-category" name="category" required readonly
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm bg-slate-100 text-slate-600 font-bold">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Total Estimasi Budget / Bulan (Rp)</label>
                <input type="number" step="0.01" id="edit-b-amount" name="amount" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-budget').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">Perbarui Anggaran</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditBudgetModal(b) {
        document.getElementById('form-edit-budget').action = `/budget/${b.id}`;
        document.getElementById('edit-b-category').value = b.category;
        document.getElementById('edit-b-amount').value = b.amount;
        document.getElementById('modal-edit-budget').classList.remove('hidden');
    }
</script>
@endpush
