<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transaction_types')->truncate();
        $data = array(
            ['code' => '1.1.001', 'name' => 'Death'],
            ['code' => '1.1.101', 'name' => 'Damage'],
            ['code' => '1.1.201', 'name' => 'Sell'],
            ['code' => '1.1.301', 'name' => 'Broken'],
            ['code' => '1.1.401', 'name' => 'Predators'],
            ['code' => '2.2.001', 'name' => 'Hatches'],
            ['code' => '2.2.101', 'name' => 'Spawn'],
            ['code' => '2.2.201', 'name' => 'Buy'],
        );
        DB::table('transaction_types')->insert($data);
    }
}
