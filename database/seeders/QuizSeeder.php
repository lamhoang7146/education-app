<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quizzes')->insert([
            'name' => 'Quiz 1: Python cơ bản',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quizzes')->insert([
            'name' => 'Quiz 2: Cấu trúc dữ liệu trong Python',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quizzes')->insert([
            'name' => 'Quiz 3: Hàm và vòng lặp trong Python',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quizzes')->insert([
            'name' => 'Quiz 1: ReactJs và NodeJs cơ bản',
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
