# 🌟 Finrai - Financial Cashflow & Umrah Savings Management

Finrai adalah aplikasi web manajemen keuangan berbasis Laravel yang dirancang untuk membantu pencatatan arus kas (pemasukan & pengeluaran), perencanaan batas anggaran (*budgeting*), dan target tabungan ibadah Umrah & Haji secara terstruktur dan transparan.

---

## 🏛️ Arsitektur & Struktur Standar MVC Laravel

Proyek ini menerapkan konsep **MVC (Model - View - Controller)** murni sesuai kaidah standar Laravel dengan pemisahan tanggung jawab (*Separation of Concerns*):

```
Cashflow/
├── app/
│   ├── Http/
│   │   ├── Controllers/           ← [CONTROLLER] Menangani logika aplikasi & flow
│   │   │   ├── AuthController.php
│   │   │   ├── BudgetController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ExpenseController.php
│   │   │   ├── IncomeController.php
│   │   │   ├── SavingGoalController.php
│   │   │   └── TransactionController.php
│   │   └── Requests/              ← [FORM REQUESTS] Validasi input request terisolasi
│   │       ├── Auth/
│   │       │   ├── LoginRequest.php
│   │       │   └── RegisterRequest.php
│   │       ├── Budget/
│   │       │   └── StoreBudgetRequest.php
│   │       ├── Savings/
│   │       │   ├── DepositSavingGoalRequest.php
│   │       │   └── StoreSavingGoalRequest.php
│   │       └── Transaction/
│   │           ├── StoreTransactionRequest.php
│   │           └── UpdateTransactionRequest.php
│   └── Models/                    ← [MODEL] Interaksi database, relasi & query scopes
│       ├── Budget.php
│       ├── SavingGoal.php
│       ├── Transaction.php
│       └── User.php
├── resources/
│   └── views/                     ← [VIEW] Interface pengguna berbasis Blade
│       ├── auth/                  ← Halaman Login & Registrasi
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── budget/                ← Halaman Anggaran (index.blade.php)
│       ├── expenses/              ← Halaman Pengeluaran (index.blade.php)
│       ├── income/                ← Halaman Pemasukan (index.blade.php)
│       ├── layouts/               ← Template induk (app.blade.php & auth.blade.php)
│       ├── savings/               ← Halaman Target Tabungan (index.blade.php)
│       ├── transactions/          ← Halaman Riwayat Transaksi (index.blade.php)
│       ├── dashboard.blade.php    ← Halaman Dashboard Finansial
│       └── home.blade.php         ← Halaman Landing Page Publik
└── routes/
    └── web.php                    ← [ROUTES] Menghubungkan URL endpoint ke Controller
```

---

## 🧩 Pembagian Tanggung Jawab (Separation of Concerns)

### 1. MODEL (`app/Models/`)
* **Tanggung jawab:** Mengelola struktur data, tipe casting, relasi Eloquent antar entitas, serta *Query Scopes*.
* **Contoh implementasi:**
  - `User.php`: Relasi `hasMany` ke `transactions`, `budgets`, dan `savingGoals`.
  - `Transaction.php`: Memiliki scopes `scopeIncome()`, `scopeExpense()`, `scopeInMonth()`, dan accessors `formatted_amount`.
  - `SavingGoal.php`: Menghitung persentase progres (`progress_percent`) dan sisa target (`remaining_amount`).
  - `Budget.php`: Menghubungkan batas nominal dengan kategori transaksi.

### 2. FORM REQUESTS (`app/Http/Requests/`)
* **Tanggung jawab:** Memeriksa dan memvalidasi data request HTTP sebelum dieksekusi controller, memastikan controller tetap ramping (*Thin Controller*).
* **Contoh:** `LoginRequest`, `RegisterRequest`, `StoreTransactionRequest`, `StoreBudgetRequest`, `StoreSavingGoalRequest`.

### 3. CONTROLLER (`app/Http/Controllers/`)
* **Tanggung jawab:** Menerima request HTTP, memanggil Model untuk mengambil/memanipulasi data, dan mengarahkan ke View dengan membawa data (*view model*).
* **Contoh:**
  - `AuthController`: Mengatur alur login, pendaftaran akun baru, dan logout.
  - `DashboardController`: Menghitung ringkasan statistik kas, grafik tren, dan agregasi data bulanan.
  - `TransactionController`, `IncomeController`, `ExpenseController`, `BudgetController`, `SavingGoalController`: CRUD lengkap.

### 4. VIEW (`resources/views/`)
* **Tanggung jawab:** Merender tampilan antarmuka (UI) menggunakan Blade template engine.
* **Layouts:**
  - `layouts/auth.blade.php`: Layout 2 kolom (Kolom Form + Kolom Visual Showcase) yang responsif dan elegan.
  - `layouts/app.blade.php`: Layout utama aplikasi dashboard dengan sidebar navigasi, header status kas, dan modal interaktif.

### 5. ROUTES (`routes/web.php`)
* **Tanggung jawab:** Mengatur rute URL aplikasi dan menghubungkan URL ke method Controller yang bersangkutan. Terbagi secara rapi ke dalam:
  - **Guest Routes:** Menggunakan middleware `guest` (`/login`, `/register`).
  - **Authenticated Routes:** Menggunakan middleware `auth` (`/dashboard`, `/transactions`, `/income`, `/expenses`, `/budget`, `/savings`).

---

## 🎨 Tampilan Login & Daftar Akun

Halaman Login dan Registrasi mengadopsi struktur 2 kolom:
1. **Kolom Kiri (Form Panel):**
   - Jarak antar elemen proporsional (*generous vertical rhythm*), tidak berdempetan.
   - Kolom password & konfirmasi password pada formulir daftar akun ditata dalam 2 kolom grid yang seimbang.
   - Dilengkapi tombol toggle mata (lihat/sembunyikan kata sandi) yang terpasang rapi di dalam kolom input.
   - Tombol utama berbentuk *pill* yang tegas dan nyaman diklik.
2. **Kolom Kanan (Visual Showcase):**
   - Menampilkan slide berlatar Masjidil Haram (Makkah) dan Masjid Nabawi (Madinah) dengan teks nilai syariah dan *feature pills*.
