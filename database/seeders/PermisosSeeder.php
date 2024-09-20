<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permisos')->insert([
            ['idpermiso' => 1, 'nombre' => 'Listado Cotizaciones', 'clave' => 'COTIZACIONES'],
            ['idpermiso' => 2, 'nombre' => 'Crear Cotizacion', 'clave' => 'FORMCOTIZA'],
            ['idpermiso' => 3, 'nombre' => 'Cotizacion Listado X Crm', 'clave' => 'COTIZACRM'],
            ['idpermiso' => 4, 'nombre' => 'Cotizacion Ver Detalles', 'clave' => 'COTDETALLE'],
            ['idpermiso' => 5, 'nombre' => 'Cotizacion Editar', 'clave' => 'COTEDITAR'],
            ['idpermiso' => 6, 'nombre' => 'Finalizar Cotizacion', 'clave' => 'COTFINALIZAR'],
            ['idpermiso' => 7, 'nombre' => 'Listado Clientes', 'clave' => 'CLIENTES'],
            ['idpermiso' => 8, 'nombre' => 'Agregar Clientes', 'clave' => 'FORMCLIENTE'],
            ['idpermiso' => 9, 'nombre' => 'Listado Productos', 'clave' => 'PRODUCTOS'],
            ['idpermiso' => 10, 'nombre' => 'Agregar Productos', 'clave' => 'FORMPRODUCTO'],
            ['idpermiso' => 11, 'nombre' => 'Listado Adicionales', 'clave' => 'ADICIONALES'],
            ['idpermiso' => 12, 'nombre' => 'Agregar Adicionales', 'clave' => 'FORMADICIONALES'],
            ['idpermiso' => 13, 'nombre' => 'Listado Conceptos', 'clave' => 'CONCEPTOS'],
            ['idpermiso' => 14, 'nombre' => 'Agregar Conceptos', 'clave' => 'FORMCONCEPTOS'],
            ['idpermiso' => 15, 'nombre' => 'Listado Marcas', 'clave' => 'MARCAS'],
            ['idpermiso' => 16, 'nombre' => 'Agregar Marcas', 'clave' => 'FORMMARCAS'],
            ['idpermiso' => 17, 'nombre' => 'Listado Proveedores', 'clave' => 'PROVEEDORES'],
            ['idpermiso' => 18, 'nombre' => 'Agregar Proveedores', 'clave' => 'FORMPROVEEDORES'],
            ['idpermiso' => 19, 'nombre' => 'Listado Salas', 'clave' => 'SALAS'],
            ['idpermiso' => 20, 'nombre' => 'Agregar Salas', 'clave' => 'FORMSALAS'],
            ['idpermiso' => 21, 'nombre' => 'Calendario', 'clave' => 'CALENDARIO'],
            ['idpermiso' => 22, 'nombre' => 'Reservar Reunion', 'clave' => 'FORMCALENDARIO'],
            ['idpermiso' => 23, 'nombre' => 'Actualizar Tipo Cambio', 'clave' => 'COTACTPRECIOS'],
        ]);
    }
}
