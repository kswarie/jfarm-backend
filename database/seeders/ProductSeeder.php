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
            ['code' => 'D001', 'name' => 'Starter Crumble 50KG'],
            ['code' => 'D002', 'name' => 'Makan Ayam Penelur 50KG'],
            ['code' => 'D003', 'name' => 'Jagung Halus C 40KG'],
            ['code' => 'D004', 'name' => 'Abu Beras 40KG'],
        );
        DB::table('products')->insert($data);
    }
}
