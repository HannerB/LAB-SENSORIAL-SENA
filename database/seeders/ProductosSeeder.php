<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productos')->insert([
            ['nombre' => 'Yogur de Fresa'],
            ['nombre' => 'Jugo Naranja-Mango'],
            ['nombre' => 'Arepa de Choclo'],
        ]);
    }
}
