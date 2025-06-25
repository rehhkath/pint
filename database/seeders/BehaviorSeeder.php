<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BehaviorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('behaviors')->insert([
            ['id' => 1, 'name' => 'Comprou'],
            ['id' => 2, 'name' => 'Devolveu'],
            ['id' => 3, 'name' => 'Resgatou cashback'],
            ['id' => 4, 'name' => 'Comprou a mais de 2 meses'],
        ]);
    }
}