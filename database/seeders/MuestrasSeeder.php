<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MuestrasSeeder extends Seeder
{
    public function run(): void
    {
        // --- Producto 1: Yogur de Fresa — solo Triangular ---
        DB::table('muestras')->insert([
            ['cod_muestra' => 'YF-101', 'producto_id' => 1, 'prueba' => 1,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'YF-102', 'producto_id' => 1, 'prueba' => 1,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'YF-103', 'producto_id' => 1, 'prueba' => 1,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
        ]);

        // --- Producto 2: Jugo Naranja-Mango — solo Duo-Trío ---
        DB::table('muestras')->insert([
            ['cod_muestra' => 'JM-R',   'producto_id' => 2, 'prueba' => 2,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'JM-201', 'producto_id' => 2, 'prueba' => 2,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'JM-202', 'producto_id' => 2, 'prueba' => 2,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
        ]);

        // Muestras de producto 3 (Arepa de Choclo): los 3 tipos de prueba para que
        // el panel de resultados los muestre juntos al filtrar por ese producto.

        // --- Prueba Triangular (prueba=1) ---
        // AC-T02 es la muestra diferente (fórmula con menos sal)
        DB::table('muestras')->insert([
            ['cod_muestra' => 'AC-T01', 'producto_id' => 3, 'prueba' => 1,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'AC-T02', 'producto_id' => 3, 'prueba' => 1,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'AC-T03', 'producto_id' => 3, 'prueba' => 1,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
        ]);

        // --- Prueba Duo-Trío (prueba=2) ---
        // AC-DR es la referencia; AC-D01 es igual a la referencia; AC-D02 es diferente
        DB::table('muestras')->insert([
            ['cod_muestra' => 'AC-DR',  'producto_id' => 3, 'prueba' => 2,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'AC-D01', 'producto_id' => 3, 'prueba' => 2,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
            ['cod_muestra' => 'AC-D02', 'producto_id' => 3, 'prueba' => 2,
             'tiene_sabor' => false, 'tiene_olor' => false, 'tiene_color' => false,
             'tiene_textura' => false, 'tiene_apariencia' => false],
        ]);

        // --- Prueba de Ordenamiento (prueba=3) ---
        // Atributos activos: sabor, olor, textura, apariencia (color no aplica)
        DB::table('muestras')->insert([
            ['cod_muestra' => 'AC-301', 'producto_id' => 3, 'prueba' => 3,
             'tiene_sabor' => true, 'tiene_olor' => true, 'tiene_color' => false,
             'tiene_textura' => true, 'tiene_apariencia' => true],
            ['cod_muestra' => 'AC-302', 'producto_id' => 3, 'prueba' => 3,
             'tiene_sabor' => true, 'tiene_olor' => true, 'tiene_color' => false,
             'tiene_textura' => true, 'tiene_apariencia' => true],
            ['cod_muestra' => 'AC-303', 'producto_id' => 3, 'prueba' => 3,
             'tiene_sabor' => true, 'tiene_olor' => true, 'tiene_color' => false,
             'tiene_textura' => true, 'tiene_apariencia' => true],
        ]);
    }
}
