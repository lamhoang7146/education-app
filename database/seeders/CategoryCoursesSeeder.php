<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_courses')->insert([
            'name' => 'Web Development',
            'description' => 'Learn various web development technologies including HTML, CSS, JavaScript, and more.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('category_courses')->insert([
            'name' => 'Mobile Development',
            'description' => 'Develop applications for iOS and Android platforms using React Native, Flutter, and native approaches.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('category_courses')->insert([
            'name' => 'Data Science',
            'description' => 'Learn data analysis, machine learning, and statistical methods to extract insights from data.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
