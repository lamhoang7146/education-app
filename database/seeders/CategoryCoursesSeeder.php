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
        DB::table('category_courses')->insert([
            'name' => 'Artificial Intelligence',
            'description' => 'Explore the world of AI including machine learning, deep learning, neural networks and AI applications.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category_courses')->insert([
            'name' => 'Cloud Computing',
            'description' => 'Learn about cloud platforms like AWS, Azure, and Google Cloud for deploying and managing applications.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category_courses')->insert([
            'name' => 'Cybersecurity',
            'description' => 'Master security principles, ethical hacking, penetration testing and system protection techniques.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category_courses')->insert([
            'name' => 'Game Development',
            'description' => 'Create interactive games using Unity, Unreal Engine and other game development frameworks.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('category_courses')->insert([
            'name' => 'DevOps',
            'description' => 'Learn about CI/CD pipelines, containerization with Docker, Kubernetes and infrastructure as code.',
            'status'=> true,
            'user_id'=>1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
