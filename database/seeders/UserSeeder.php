<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'phone' => '1234567890',
                'description' => 'A sample admin description.',
                'facebook' => 'https://facebook.com/johndoe',
                'twitter' => 'https://twitter.com/johndoe',
                'instagram' => 'https://instagram.com/johndoe',
                'linkedin' => 'https://linkedin.com/in/johndoe',
                'telegram' => '@johndoe',
                'password' => Hash::make('12345678'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
