<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultadosSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================================
        // PRODUCTO 3 — Arepa de Choclo | Triangular | 2024-11-16
        // resultado = número de votos que recibió esa muestra como "diferente"
        // AC-T02=5 votos (correcta), AC-T01=1 voto, AC-T03=1 voto
        // =====================================================================
        DB::table('resultados')->insert([
            // Cabina 1: AC-T02=3 votos, AC-T01=1 voto
            ['producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T02',
             'resultado' => '3', 'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => null],
            ['producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T01',
             'resultado' => '1', 'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => null],
            // Cabina 2: AC-T02=2 votos, AC-T03=1 voto
            ['producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T02',
             'resultado' => '2', 'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => null],
            ['producto' => 3, 'prueba' => 1, 'cod_muestra' => 'AC-T03',
             'resultado' => '1', 'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => null],
        ]);

        // =====================================================================
        // PRODUCTO 3 — Arepa de Choclo | Duo-Trío | 2024-11-16
        // resultado = número de votos que recibió esa muestra como "igual a referencia"
        // AC-D01=5 votos (correcta), AC-D02=2 votos
        // =====================================================================
        DB::table('resultados')->insert([
            // Cabina 1: AC-D01=3 votos, AC-D02=1 voto
            ['producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D01',
             'resultado' => '3', 'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => null],
            ['producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D02',
             'resultado' => '1', 'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => null],
            // Cabina 2: AC-D01=2 votos, AC-D02=1 voto
            ['producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D01',
             'resultado' => '2', 'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => null],
            ['producto' => 3, 'prueba' => 2, 'cod_muestra' => 'AC-D02',
             'resultado' => '1', 'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => null],
        ]);

        // =====================================================================
        // PRODUCTO 3 — Arepa de Choclo | Ordenamiento | 2024-11-16
        // resultado = promedio de puntajes del atributo para esa muestra
        // Cabina 1 (4 panelistas), Cabina 2 (3 panelistas), Cabina 3 (3 panelistas)
        // =====================================================================
        DB::table('resultados')->insert([
            // --- Cabina 1 --- (promedios de 4 panelistas)
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '8.00',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'sabor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.75',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'sabor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '3.50',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'sabor'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '7.50',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'olor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.50',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'olor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '4.00',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'olor'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '7.75',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'textura'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.75',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'textura'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '4.00',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'textura'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '8.00',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'apariencia'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.75',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'apariencia'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '4.00',
             'fecha' => '2024-11-16', 'cabina' => 1, 'atributo_evaluado' => 'apariencia'],

            // --- Cabina 2 --- (promedios de 3 panelistas)
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '8.00',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'sabor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.67',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'sabor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '4.00',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'sabor'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '7.33',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'olor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '6.00',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'olor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '3.67',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'olor'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '8.00',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'textura'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '6.00',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'textura'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '4.00',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'textura'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '7.67',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'apariencia'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.67',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'apariencia'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '4.33',
             'fecha' => '2024-11-16', 'cabina' => 2, 'atributo_evaluado' => 'apariencia'],

            // --- Cabina 3 --- (promedios de 3 panelistas)
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '8.00',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'sabor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.67',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'sabor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '3.67',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'sabor'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '7.67',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'olor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '6.00',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'olor'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '4.00',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'olor'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '8.00',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'textura'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '5.67',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'textura'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '3.67',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'textura'],

            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-301', 'resultado' => '8.33',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'apariencia'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-302', 'resultado' => '6.00',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'apariencia'],
            ['producto' => 3, 'prueba' => 3, 'cod_muestra' => 'AC-303', 'resultado' => '3.67',
             'fecha' => '2024-11-16', 'cabina' => 3, 'atributo_evaluado' => 'apariencia'],
        ]);
    }
}
