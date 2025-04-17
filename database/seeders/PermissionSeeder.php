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
            'name' => 'Add user',
            'slug' => 'Add user',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit user',
            'slug' => 'Edit user',
            'groupBy'=> 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete user',
            'slug' => 'Delete user',
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
            'name' => 'Add role',
            'slug' => 'Add role',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit role',
            'slug' => 'Edit role',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Update role',
            'slug' => 'Update role',
            'groupBy'=> 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Courses management',
            'slug' => 'Courses management',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add Courses management',
            'slug' => 'Add Courses management',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit Courses management',
            'slug' => 'Edit Courses management',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete Courses management',
            'slug' => 'Delete Courses management',
            'groupBy'=> 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Courses category',
            'slug' => 'Courses category',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add course category',
            'slug' => 'Add course category',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit course category',
            'slug' => 'Edit course category',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete course category',
            'slug' => 'Delete course category',
            'groupBy'=> 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Courses',
            'slug' => 'Courses',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add courses',
            'slug' => 'Add courses',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit courses',
            'slug' => 'Edit courses',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete courses',
            'slug' => 'Delete courses',
            'groupBy'=> 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Courses module',
            'slug' => 'Courses module',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add courses module',
            'slug' => 'Add courses module',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit courses module',
            'slug' => 'Edit courses module',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete courses module',
            'slug' => 'Delete courses module',
            'groupBy'=> 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Courses content item',
            'slug' => 'Courses content item',
            'groupBy'=> 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add courses content item',
            'slug' => 'Add courses content item',
            'groupBy'=> 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit courses content item',
            'slug' => 'Edit courses content item',
            'groupBy'=> 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete courses content item',
            'slug' => 'Delete courses content item',
            'groupBy'=> 6,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Quiz',
            'slug' => 'Quiz',
            'groupBy'=> 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add quiz',
            'slug' => 'Add quiz',
            'groupBy'=> 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit quiz',
            'slug' => 'Edit quiz',
            'groupBy'=> 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete quiz',
            'slug' => 'Delete quiz',
            'groupBy'=> 7,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Video',
            'slug' => 'Video',
            'groupBy'=> 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add video',
            'slug' => 'Add video',
            'groupBy'=> 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit video',
            'slug' => 'Edit video',
            'groupBy'=> 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete video',
            'slug' => 'Delete video',
            'groupBy'=> 8,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Analytics',
            'slug' => 'Analytics',
            'groupBy'=> 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add analytics',
            'slug' => 'Add analytics',
            'groupBy'=> 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit analytics',
            'slug' => 'Edit analytics',
            'groupBy'=> 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete analytics',
            'slug' => 'Delete analytics',
            'groupBy'=> 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'AI Analytics',
            'slug' => 'AI Analytics',
            'groupBy'=> 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Add AI Analytics',
            'slug' => 'Add AI Analytics',
            'groupBy'=> 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Edit AI Analytics',
            'slug' => 'Edit AI Analytics',
            'groupBy'=> 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('permissions')->insert([
            'name' => 'Delete AI Analytics',
            'slug' => 'Delete AI Analytics',
            'groupBy'=> 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
