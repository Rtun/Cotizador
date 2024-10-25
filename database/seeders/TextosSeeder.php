<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TextosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conceptos')->insert([
            ['idconcepto' => 1, 'con_clave' => 'Cotizacion', 'con_texto' => 'Atendiendo su amable solicitud estamos enviando cotización de los productos y/o servicios requeridos.'],
        ]);
    }
}
