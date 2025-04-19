<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Predis\Client as Redis;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Redis $redis): void
    {
        $redis->flushdb();

        DB::table('users')->insert([
            'name' => 'Lam Hoang 1',
            'email' => 'nguyenlamhoang7146@gmail.com',
            'password' => Hash::make('00000000'),
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
            'role_id' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'Lam Hoang 2',
            'email' => 'aztenderio7146@gmail.com',
            'password' => Hash::make('00000000'),
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
            'role_id' => 2,
        ]);


        for ($i = 3; $i <= 12; $i++) {
            $email = "user{$i}@example.com";
            DB::table('users')->insert([
                'name' => "User {$i}",
                'email' => $email,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
                'role_id' => 2,
            ]);
            $redis->set('email:' . crc32($email), $i);
        }

        $redis->set('email:' . crc32('nguyenlamhoang7146@gmail.com'), 1);
        $redis->set('email:' . crc32('aztenderio7146@gmail.com'), 2);

        $redis->save();
    }
}
