<?php

namespace Database\Seeders;

use App\Models\Meja;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            ['name' => 'Admin', 'username' => 'admin', 'password' => Hash::make('admin123'), 'role' => 'administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kasir', 'username' => 'kasir', 'password' => Hash::make('kasir123'), 'role' => 'kasir', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Waiter', 'username' => 'waiter', 'password' => Hash::make('waiter123'), 'role' => 'waiter', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Owner', 'username' => 'owner', 'password' => Hash::make('owner123'), 'role' => 'owner', 'created_at' => now(), 'updated_at' => now()],
        ]);

        for ($i = 1; $i <= 10; $i++) {
            Meja::create(['kode' => 'M' . str_pad($i, 3, '0', STR_PAD_LEFT), 'status' => 'tersedia']);
        }
    }
}
