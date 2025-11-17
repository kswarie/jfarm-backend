<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->truncate();
        $data = array(
            ['code' => 'A001', 'name' => 'Ayam Jantan Kampung'],
            ['code' => 'A002', 'name' => 'Ayam Betina Kampung'],
            ['code' => 'B001', 'name' => 'Anakan Ayam Jantan Kampung'],
            ['code' => 'B002', 'name' => 'Anakan Ayam Betina Kampung'],
            ['code' => 'C001', 'name' => 'Telur Ayam Kampung'],
            ['code' => 'D001', 'name' => 'Pakan'],
        );
        DB::table('products')->insert($data);
    }
}
