<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();
        $data = array(
            ['id' => 1, 'name' => 'ADMIN'],
            ['id' => 2, 'name' => 'OWNER'],
            ['id' => 3, 'name' => 'TENANT'],
            ['id' => 4, 'name' => 'OPERATOR'],
            ['id' => 5, 'name' => 'SUPERVISOR'],
        );
        DB::table('roles')->insert($data);

    }
}
