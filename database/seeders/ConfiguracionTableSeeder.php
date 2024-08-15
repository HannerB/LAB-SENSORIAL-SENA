<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ConfiguracionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuracion')->insert([
            [
                'num_cabina' => 1,
                'producto_habilitado' => null,
                'clave_acceso' => '123'
            ]
        ]);
    }
}
