<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            'name' => 'User',
            'slug' => 'User',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add User',
            'slug' => 'Add User',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit User',
            'slug' => 'Edit User',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete User',
            'slug' => 'Delete User',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Role',
            'slug' => 'Role',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add Role',
            'slug' => 'Add Role',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit Role',
            'slug' => 'Edit Role',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete Role',
            'slug' => 'Delete Role',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Course',
            'slug' => 'Course',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add Course',
            'slug' => 'Add Course',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit Course',
            'slug' => 'Edit Course',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete Course',
            'slug' => 'Delete Course',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Video',
            'slug' => 'Video',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add Video',
            'slug' => 'Add Video',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit Video',
            'slug' => 'Edit Video',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete Video',
            'slug' => 'Delete Video',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
