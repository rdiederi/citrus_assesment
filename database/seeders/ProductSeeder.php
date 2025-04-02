<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Citrusdal Oranges',
                'description' => 'Premium oranges from Citrusdal region',
                'price_2023' => 120.00,
                'price_2024' => 135.00,
                'price_2025' => 145.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Limes',
                'description' => 'Fresh South African limes',
                'price_2023' => 95.00,
                'price_2024' => 105.00,
                'price_2025' => 115.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lemons',
                'description' => 'Premium quality lemons',
                'price_2023' => 85.00,
                'price_2024' => 95.00,
                'price_2025' => 100.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
