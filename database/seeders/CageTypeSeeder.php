<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {DB::table('cage_types')->truncate();
        $data = array(
            ['name' => 'Umbaran'],
            ['name' => 'Eram'],
            ['name' => 'Bertelur'],
        );
        DB::table('cage_types')->insert($data);
    }
}
