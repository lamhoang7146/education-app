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
            'name' => 'Add',
            'slug' => 'Add',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit',
            'slug' => 'Edit',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete',
            'slug' => 'Delete',
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
            'name' => 'Add',
            'slug' => 'Add',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit',
            'slug' => 'Edit',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete',
            'slug' => 'Delete',
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
            'name' => 'Add',
            'slug' => 'Add',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit',
            'slug' => 'Edit',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete',
            'slug' => 'Delete',
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
            'name' => 'Add',
            'slug' => 'Add',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit',
            'slug' => 'Edit',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete',
            'slug' => 'Delete',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Video comment',
            'slug' => 'Video comment',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add',
            'slug' => 'Add',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit',
            'slug' => 'Edit',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete',
            'slug' => 'Delete',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Video reply comment',
            'slug' => 'Video reply comment',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add',
            'slug' => 'Add',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit',
            'slug' => 'Edit',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete',
            'slug' => 'Delete',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
