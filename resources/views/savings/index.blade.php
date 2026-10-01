@extends('layouts.app')

@section('title', 'Saving Goals - Target Tabungan')

@section('content')
<div class="space-y-8">

    <!-- Header & Action Button -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-outfit text-slate-900">Saving Goals</h1>
            <p class="text-xs font-medium text-slate-400 mt-0.5">Rencanakan dan capai impian finansial Anda</p>
        </div>

        <button onclick="document.getElementById('modal-add-goal').classList.remove('hidden')"
            class="px-5 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-500/20 transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add Goal</span>
        </button>
    </div>

    <!-- Total Saved Across Goals Summary Banner -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 p-6 sm:p-8 rounded-3xl text-white shadow-xl shadow-indigo-600/15 relative overflow-hidden flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="relative z-10 space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-100">Total Saved Across Goals</span>
            <div class="text-3xl sm:text-4xl font-extrabold font-outfit tracking-tight">
                Rp {{ number_format($totalSaved, 0, ',', '.') }}
            </div>
            <p class="text-xs text-indigo-100/80 font-medium pt-1">
                Total Target: Rp {{ number_format($totalTarget, 0, ',', '.') }}
            </p>
        </div>

        <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-2xl text-white shadow-inner">
            <i class="fa-solid fa-piggy-bank"></i>
        </div>
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Cards Grid for Each Goal (Matching User Specs with Color Choices) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($formattedGoals as $g)
            @php
                $colorThemes = [
                    'emerald' => 'from-emerald-500 to-teal-600 border-emerald-200 text-emerald-600 bg-emerald-50',
                    'blue' => 'from-blue-500 to-indigo-600 border-blue-200 text-blue-600 bg-blue-50',
                    'purple' => 'from-purple-500 to-pink-600 border-purple-200 text-purple-600 bg-purple-50',
                    'amber' => 'from-amber-500 to-orange-600 border-amber-200 text-amber-600 bg-amber-50',
                    'rose' => 'from-rose-500 to-pink-600 border-rose-200 text-rose-600 bg-rose-50',
                    'cyan' => 'from-cyan-500 to-blue-600 border-cyan-200 text-cyan-600 bg-cyan-50',
                    'indigo' => 'from-indigo-500 to-purple-600 border-indigo-200 text-indigo-600 bg-indigo-50',
                    'teal' => 'from-teal-500 to-emerald-600 border-teal-200 text-teal-600 bg-teal-50',
                ];
                $barColors = [
                    'emerald' => 'bg-emerald-500',
                    'blue' => 'bg-blue-500',
                    'purple' => 'bg-purple-500',
                    'amber' => 'bg-amber-500',
                    'rose' => 'bg-rose-500',
                    'cyan' => 'bg-cyan-500',
                    'indigo' => 'bg-indigo-500',
                    'teal' => 'bg-teal-500',
                ];
                $themeClass = $colorThemes[$g['color']] ?? $colorThemes['blue'];
                $barClass = $barColors[$g['color']] ?? $barColors['blue'];
            @endphp

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs space-y-5 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden">
                
                <!-- Color Bar Accent Header -->
                <div class="h-2 w-full absolute top-0 left-0 bg-gradient-to-r {{ $themeClass }}"></div>

                <div class="space-y-4 pt-1">
                    <!-- Title & Actions -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-lg font-bold">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold font-outfit text-slate-900">{{ $g['name'] }}</h3>
                                <span class="text-xs font-medium text-slate-400">
                                    Target: {{ \Carbon\Carbon::parse($g['target_date'])->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-1">
                            <button onclick="openEditGoalModal({{ json_encode($g) }})" class="w-8 h-8 rounded-full hover:bg-slate-100 text-slate-400 hover:text-blue-600 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <form action="{{ route('savings.destroy', $g['id']) }}" method="POST" onsubmit="return confirm('Hapus target tabungan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Saved vs Target -->
                    <div>
                        <div class="text-2xl font-extrabold font-outfit text-slate-900">
                            Rp {{ number_format($g['current_amount'], 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-slate-400 font-semibold mt-0.5">
                            dari target Rp {{ number_format($g['target_amount'], 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1.5">
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $barClass }}"
                                style="width: {{ $g['percent'] }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-600">{{ $g['percent'] }}% Tercapai</span>
                            <span class="text-slate-400 font-normal">Sisa Rp {{ number_format(max($g['target_amount'] - $g['current_amount'], 0), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Setor Tabungan Action Button -->
                <div class="pt-2 border-t border-slate-100">
                    <button onclick="openDepositModal({{ $g['id'] }}, '{{ addslashes($g['name']) }}')"
                        class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-700 text-xs font-bold transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-plus-minus text-slate-500"></i>
                        <span>+ Setor / Tambah Tabungan</span>
                    </button>
                </div>

            </div>
        @empty
            <div class="col-span-3 bg-white p-12 rounded-3xl border border-slate-200/80 text-center text-slate-400">
                <i class="fa-solid fa-piggy-bank text-3xl mb-2 text-slate-300"></i>
                <p class="text-sm font-medium">Belum ada target tabungan. Klik <b>+ Add Goal</b> untuk membuat impian baru!</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Add Saving Goal -->
<div id="modal-add-goal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Tambah Target Tabungan</h3>
            <button onclick="document.getElementById('modal-add-goal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('savings.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Goal Name (Nama Impian)</label>
                <input type="text" name="name" required placeholder="misal: Emergency Fund / Vacation to Bali"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Target Amount (Rp)</label>
                    <input type="number" step="0.01" name="target_amount" required placeholder="10000000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Initial Deposit (Rp)</label>
                    <input type="number" step="0.01" name="initial_deposit" value="0" placeholder="1000000"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Target Date (Pilih Tanggal)</label>
                <input type="date" name="target_date" required value="{{ date('Y-m-d', strtotime('+6 months')) }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Pilihan Warna Tema Kartu</label>
                <div class="grid grid-cols-4 gap-2">
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="emerald" checked class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-emerald-100 text-emerald-700 text-xs font-bold border border-transparent peer-checked:border-emerald-600 peer-checked:ring-2 peer-checked:ring-emerald-500/20">
                            Emerald
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="blue" class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-blue-100 text-blue-700 text-xs font-bold border border-transparent peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-500/20">
                            Ocean Blue
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="purple" class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-purple-100 text-purple-700 text-xs font-bold border border-transparent peer-checked:border-purple-600 peer-checked:ring-2 peer-checked:ring-purple-500/20">
                            Purple
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="amber" class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-amber-100 text-amber-700 text-xs font-bold border border-transparent peer-checked:border-amber-600 peer-checked:ring-2 peer-checked:ring-amber-500/20">
                            Amber
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="rose" class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-rose-100 text-rose-700 text-xs font-bold border border-transparent peer-checked:border-rose-600 peer-checked:ring-2 peer-checked:ring-rose-500/20">
                            Rose
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="cyan" class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-cyan-100 text-cyan-700 text-xs font-bold border border-transparent peer-checked:border-cyan-600 peer-checked:ring-2 peer-checked:ring-cyan-500/20">
                            Cyan
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="indigo" class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-indigo-100 text-indigo-700 text-xs font-bold border border-transparent peer-checked:border-indigo-600 peer-checked:ring-2 peer-checked:ring-indigo-500/20">
                            Indigo
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="teal" class="peer sr-only">
                        <div class="p-2 text-center rounded-xl bg-teal-100 text-teal-700 text-xs font-bold border border-transparent peer-checked:border-teal-600 peer-checked:ring-2 peer-checked:ring-teal-500/20">
                            Teal
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-add-goal').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">Simpan Goal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Saving Goal -->
<div id="modal-edit-goal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Edit Target Tabungan</h3>
            <button onclick="document.getElementById('modal-edit-goal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-edit-goal" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Goal Name</label>
                <input type="text" id="edit-g-name" name="name" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Target Amount (Rp)</label>
                    <input type="number" step="0.01" id="edit-g-target_amount" name="target_amount" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Current Saved (Rp)</label>
                    <input type="number" step="0.01" id="edit-g-current_amount" name="current_amount" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Initial Deposit (Rp)</label>
                    <input type="number" step="0.01" id="edit-g-initial_deposit" name="initial_deposit"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Target Date</label>
                    <input type="date" id="edit-g-target_date" name="target_date" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Pilihan Warna Tema Kartu</label>
                <select id="edit-g-color" name="color" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white">
                    <option value="emerald">Emerald</option>
                    <option value="blue">Ocean Blue</option>
                    <option value="purple">Purple</option>
                    <option value="amber">Amber</option>
                    <option value="rose">Rose</option>
                    <option value="cyan">Cyan</option>
                    <option value="indigo">Indigo</option>
                    <option value="teal">Teal</option>
                </select>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-edit-goal').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">Perbarui Goal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Setor Tabungan -->
<div id="modal-deposit" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-6 border border-slate-100">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold font-outfit text-slate-900">Setor Tabungan</h3>
            <button onclick="document.getElementById('modal-deposit').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-deposit" method="POST" class="space-y-4">
            @csrf
            <div>
                <p id="deposit-goal-name" class="text-sm font-bold text-slate-800 mb-2"></p>
                <label class="block text-xs font-bold text-slate-600 mb-1.5">Jumlah Setoran (Rp)</label>
                <input type="number" step="0.01" name="deposit_amount" required placeholder="500000"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-deposit').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">Setor Sekarang</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openEditGoalModal(g) {
        document.getElementById('form-edit-goal').action = `/savings/${g.id}`;
        document.getElementById('edit-g-name').value = g.name;
        document.getElementById('edit-g-target_amount').value = g.target_amount;
        document.getElementById('edit-g-current_amount').value = g.current_amount;
        document.getElementById('edit-g-initial_deposit').value = g.initial_deposit;

        const d = new Date(g.target_date);
        const dateStr = d.toISOString().split('T')[0];
        document.getElementById('edit-g-target_date').value = dateStr;
        document.getElementById('edit-g-color').value = g.color || 'blue';

        document.getElementById('modal-edit-goal').classList.remove('hidden');
    }

    function openDepositModal(goalId, goalName) {
        document.getElementById('form-deposit').action = `/savings/${goalId}/deposit`;
        document.getElementById('deposit-goal-name').innerText = 'Target: ' + goalName;
        document.getElementById('modal-deposit').classList.remove('hidden');
    }
</script>
@endpush
