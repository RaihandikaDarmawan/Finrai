@extends('layouts.app')

@section('title', 'Transactions - History & Management')

@section('content')
<div class="space-y-6">

    <!-- Header Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900">Transactions</h1>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Lacak dan kelola semua riwayat transaksi keuangan Anda</p>
        </div>

        <button onclick="document.getElementById('modal-add-tx').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Transaksi</span>
        </button>
    </div>

    <!-- Filter Bar (Matching Screenshot 2) -->
    <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-2xs">
        <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- Search description -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" placeholder="Search description..." value="{{ request('search') }}"
                    class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-full focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-full focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-700 font-medium">
                    <option value="all">Semua Kategori (all)</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <select name="type" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-full focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-700 font-medium">
                    <option value="all">Semua Tipe (all)</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income (Pemasukan)</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense (Pengeluaran)</option>
                </select>
            </div>

            <!-- Date Range Filter -->
            <div class="flex items-center gap-1.5 col-span-1 sm:col-span-2 lg:col-span-1">
                <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 text-[11px] bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-700">
                <span class="text-slate-400 text-xs">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()"
                    class="w-full px-2.5 py-2 text-[11px] bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-700">
            </div>

            <!-- Sorting & Reset -->
            <div class="flex items-center gap-2">
                <select name="sort" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-full focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-slate-700 font-medium">
                    <option value="date_desc" {{ request('sort', 'date_desc') == 'date_desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Terlama</option>
                    <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Nominal Terbesar</option>
                    <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Nominal Terkecil</option>
                </select>

                @if(request()->hasAny(['search', 'category', 'type', 'start_date', 'end_date', 'sort']))
                    <a href="{{ route('transactions.index') }}" class="p-2 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Transactions Table (Matching Screenshot 2) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Description</th>
                        <th class="py-3.5 px-6">Category</th>
                        <th class="py-3.5 px-6">Type</th>
                        <th class="py-3.5 px-6 text-right">Amount</th>
                        <th class="py-3.5 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 text-slate-500 text-xs whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <span>{{ $tx->description }}</span>
                                    @if($tx->receipt_path)
                                        <a href="{{ asset('storage/' . $tx->receipt_path) }}" target="_blank" class="text-blue-500 hover:text-blue-700" title="Lihat Bukti Upload">
                                            <i class="fa-solid fa-paperclip text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $categoryColors = [
                                        'Bonus' => 'bg-purple-100 text-purple-700',
                                        'Entertainment' => 'bg-pink-100 text-pink-700',
                                        'Food' => 'bg-amber-100 text-amber-700',
                                        'Business' => 'bg-amber-100 text-amber-800',
                                        'Shopping' => 'bg-indigo-100 text-indigo-700',
                                        'Salary' => 'bg-emerald-100 text-emerald-700',
                                        'Freelance' => 'bg-cyan-100 text-cyan-700',
                                        'Bills' => 'bg-sky-100 text-sky-700',
                                        'Other' => 'bg-slate-100 text-slate-700',
                                    ];
                                    $colorClass = $categoryColors[$tx->category] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $colorClass }}">
                                    <i class="fa-solid fa-sparkles text-[10px]"></i>
                                    {{ $tx->category }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($tx->type === 'income')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-700">
                                        Income
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-700">
                                        Expense
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right font-extrabold font-outfit text-base whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-slate-900' }}">
                                {{ $tx->type === 'income' ? 'Rp ' : 'Rp -' }}{{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <button onclick="openEditTxModal({{ json_encode($tx) }})"
                                        class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-receipt text-3xl text-slate-300"></i>
                                    <p class="text-sm font-medium">Tidak ada transaksi yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Add Transaction -->
<div id="modal-add-tx" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Tambah Transaksi</h3>
            <button onclick="document.getElementById('modal-add-tx').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
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
                        <div class="p-3 text-center rounded-xl border border-slate-200 text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white transition-all">
                            Pemasukan (Income)
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border border-slate-200 text-xs font-bold peer-checked:bg-rose-600 peer-checked:text-white transition-all">
                            Pengeluaran (Expense)
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Deskripsi</label>
                <input type="text" name="description" required placeholder="Deskripsi transaksi..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Amount (Rp)</label>
                    <input type="number" step="0.01" name="amount" required placeholder="100000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori</label>
                    <input type="text" name="category" required placeholder="Bonus / Food / Shopping"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Date (Pilih Tanggal)</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Payment Method</label>
                    <input type="text" name="payment_method" required value="BCA" placeholder="BCA / GoPay / Cash"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Upload Bukti (Opsional)</label>
                <input type="file" name="receipt" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add-tx').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Transaction -->
<div id="modal-edit-tx" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Edit Transaksi</h3>
            <button onclick="document.getElementById('modal-edit-tx').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-tx" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Tipe Transaksi</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" id="edit-type-income" name="type" value="income" class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border border-slate-200 text-xs font-bold peer-checked:bg-emerald-600 peer-checked:text-white transition-all">
                            Pemasukan (Income)
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" id="edit-type-expense" name="type" value="expense" class="peer sr-only">
                        <div class="p-3 text-center rounded-xl border border-slate-200 text-xs font-bold peer-checked:bg-rose-600 peer-checked:text-white transition-all">
                            Pengeluaran (Expense)
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Deskripsi</label>
                <input type="text" id="edit-description" name="description" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Amount (Rp)</label>
                    <input type="number" step="0.01" id="edit-amount" name="amount" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Kategori</label>
                    <input type="text" id="edit-category" name="category" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Date (Pilih Tanggal)</label>
                    <input type="date" id="edit-date" name="date" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Payment Method</label>
                    <input type="text" id="edit-payment_method" name="payment_method" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Ganti Bukti Upload (Opsional)</label>
                <input type="file" name="receipt" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-tx').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditTxModal(tx) {
        document.getElementById('form-edit-tx').action = `/transactions/${tx.id}`;
        if (tx.type === 'income') {
            document.getElementById('edit-type-income').checked = true;
        } else {
            document.getElementById('edit-type-expense').checked = true;
        }
        document.getElementById('edit-description').value = tx.description;
        document.getElementById('edit-amount').value = tx.amount;
        document.getElementById('edit-category').value = tx.category;
        
        // Format date to YYYY-MM-DD
        const d = new Date(tx.date);
        const dateStr = d.toISOString().split('T')[0];
        document.getElementById('edit-date').value = dateStr;
        document.getElementById('edit-payment_method').value = tx.payment_method;

        document.getElementById('modal-edit-tx').classList.remove('hidden');
    }
</script>
@endpush
