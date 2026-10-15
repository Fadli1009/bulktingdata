<?php

namespace Database\Seeders;

use App\Enums\BillingPeriod;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin portal — hanya akun ini yang boleh masuk /admin/login
        User::factory()->admin()->create([
            'name' => 'Admin OmniSync',
            'email' => 'admin@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Package::factory()->personal()->create([
            'name' => 'Personal Starter',
            'description' => 'Untuk individu yang baru mulai menertibkan data produk marketplace.',
            'price' => 149_000,
            'billing_period' => BillingPeriod::Monthly,
            'upload_limit' => 5,
            'sort_order' => 1,
            'badge' => null,
            'features' => [
                '5 kali upload',
                '1 pengguna',
                'Pencocokan SKU standar',
            ],
        ]);

        Package::factory()->business()->featured()->create([
            'name' => 'Bisnis Pro',
            'description' => 'Untuk seller multi-channel dengan tim. Upload tanpa batas & kelola banyak pengguna.',
            'price' => 599_000,
            'billing_period' => BillingPeriod::Monthly,
            'seat_limit' => 5,
            'sort_order' => 2,
            'features' => [
                'Upload tanpa batas',
                'Hingga 5 pengguna tim',
                'Pencocokan SKU prioritas',
            ],
        ]);
    }
}
