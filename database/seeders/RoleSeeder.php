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
            'id'=>1,
            'name' => 'Admin',
            'is_important' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('roles')->insert([
            'id'=>2,
            'name' => 'User',
            'is_important' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
