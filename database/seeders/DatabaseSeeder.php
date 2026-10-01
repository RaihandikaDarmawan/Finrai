<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\SavingGoal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create or update primary user Raihan
        $user = User::updateOrCreate(
            ['email' => 'raihan@example.com'],
            [
                'name' => 'Raihan',
                'password' => Hash::make('password'),
            ]
        );

        // Also ensure any existing first user is synced or password set
        $firstUser = User::first();
        if ($firstUser && $firstUser->id !== $user->id) {
            $user = $firstUser;
        }

        // Clear existing user data for fresh seed
        Transaction::where('user_id', $user->id)->delete();
        Budget::where('user_id', $user->id)->delete();
        SavingGoal::where('user_id', $user->id)->delete();

        // 2. Seed Transactions matching exact screenshots
        $transactions = [
            [
                'type' => 'income',
                'description' => 'Quarterly performance bonus',
                'amount' => 500000,
                'category' => 'Bonus',
                'date' => '2026-09-15',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'expense',
                'description' => 'Concert ticket — Musik Nusantara',
                'amount' => 100000,
                'category' => 'Entertainment',
                'date' => '2026-09-14',
                'payment_method' => 'GoPay',
            ],
            [
                'type' => 'expense',
                'description' => 'Snacks & coffee run',
                'amount' => 105000,
                'category' => 'Food',
                'date' => '2026-09-12',
                'payment_method' => 'GoPay',
            ],
            [
                'type' => 'expense',
                'description' => 'Gift — friend\'s birthday',
                'amount' => 100000,
                'category' => 'Other',
                'date' => '2026-09-12',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'expense',
                'description' => 'Steam — game festival sale',
                'amount' => 130000,
                'category' => 'Entertainment',
                'date' => '2026-09-10',
                'payment_method' => 'GoPay',
            ],
            [
                'type' => 'income',
                'description' => 'Online shop payout — Toko Rai',
                'amount' => 1000000,
                'category' => 'Business',
                'date' => '2026-09-10',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'expense',
                'description' => 'Lunch — Warkop Bahari',
                'amount' => 100000,
                'category' => 'Food',
                'date' => '2026-09-09',
                'payment_method' => 'Cash',
            ],
            [
                'type' => 'expense',
                'description' => 'Shopee — home supplies',
                'amount' => 240000,
                'category' => 'Shopping',
                'date' => '2026-09-09',
                'payment_method' => 'GoPay',
            ],
            // Major monthly income
            [
                'type' => 'income',
                'description' => 'Gaji Bulanan Utama',
                'amount' => 8500000,
                'category' => 'Salary',
                'date' => '2026-09-01',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'income',
                'description' => 'Project Freelance Design System',
                'amount' => 2500000,
                'category' => 'Freelance',
                'date' => '2026-09-05',
                'payment_method' => 'Mandiri',
            ],
            // Additional monthly expenses
            [
                'type' => 'expense',
                'description' => 'Tagihan Listrik & Wifi',
                'amount' => 560000,
                'category' => 'Bills',
                'date' => '2026-09-03',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'expense',
                'description' => 'Bensin & E-Toll Bulanan',
                'amount' => 750000,
                'category' => 'Transportation',
                'date' => '2026-09-04',
                'payment_method' => 'GoPay',
            ],
            [
                'type' => 'expense',
                'description' => 'Belanja Supermarket Resto',
                'amount' => 1095000,
                'category' => 'Food',
                'date' => '2026-09-06',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'expense',
                'description' => 'Beli Baju & Accessories',
                'amount' => 320000,
                'category' => 'Shopping',
                'date' => '2026-09-07',
                'payment_method' => 'Credit Card',
            ],
            // Past months data for trend charts
            [
                'type' => 'income',
                'description' => 'Gaji Agustus',
                'amount' => 8500000,
                'category' => 'Salary',
                'date' => '2026-08-01',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'income',
                'description' => 'Freelance Agustus',
                'amount' => 3013547,
                'category' => 'Freelance',
                'date' => '2026-08-15',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'income',
                'description' => 'Gaji Juli',
                'amount' => 8500000,
                'category' => 'Salary',
                'date' => '2026-07-01',
                'payment_method' => 'BCA',
            ],
            [
                'type' => 'income',
                'description' => 'Bonus Juli',
                'amount' => 3200000,
                'category' => 'Bonus',
                'date' => '2026-07-15',
                'payment_method' => 'BCA',
            ],
        ];

        foreach ($transactions as $tx) {
            Transaction::create(array_merge($tx, ['user_id' => $user->id]));
        }

        // 3. Seed Budgets matching screenshot 5
        $budgets = [
            ['category' => 'Bills', 'amount' => 800000],
            ['category' => 'Entertainment', 'amount' => 400000],
            ['category' => 'Food', 'amount' => 1500000],
            ['category' => 'Shopping', 'amount' => 500000],
            ['category' => 'Transportation', 'amount' => 1000000],
        ];

        foreach ($budgets as $b) {
            Budget::create(array_merge($b, ['user_id' => $user->id]));
        }

        // 4. Seed Saving Goals matching screenshot 1
        $savingGoals = [
            [
                'name' => 'Emergency Fund',
                'target_amount' => 10000000,
                'initial_deposit' => 2000000,
                'current_amount' => 6000000,
                'target_date' => '2026-12-31',
                'color' => 'emerald',
            ],
            [
                'name' => 'New Laptop',
                'target_amount' => 15000000,
                'initial_deposit' => 3000000,
                'current_amount' => 9000000,
                'target_date' => '2027-03-31',
                'color' => 'blue',
            ],
            [
                'name' => 'Vacation to Bali',
                'target_amount' => 25000000,
                'initial_deposit' => 2500000,
                'current_amount' => 7500000,
                'target_date' => '2027-06-30',
                'color' => 'amber',
            ],
        ];

        foreach ($savingGoals as $g) {
            SavingGoal::create(array_merge($g, ['user_id' => $user->id]));
        }
    }
}
