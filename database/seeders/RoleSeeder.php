<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'System Administrator', 'slug' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Purchasing Manager', 'slug' => 'purchasing-manager', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Field Sales Associate', 'slug' => 'field-sales', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
