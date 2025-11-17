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
        DB::table('users')->truncate();
        $data = array(
            ['id' => 1, 'name' => 'admin', 'email' => 'admin@admin.com', 'password' => Hash::make('password')],
            ['id' => 2, 'name' => 'owner', 'email' => 'owner@admin.com', 'password' => Hash::make('password')],
            ['id' => 3, 'name' => 'operator', 'email' => 'operator@admin.com', 'password' => Hash::make('password')],
        );
        DB::table('users')->insert($data);
    }
}
