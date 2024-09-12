<?php

namespace App\Http\Controllers;

use App\Models\Tipo_Cambio;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    public function valor_dolar() {
        $dolar = Tipo_Cambio::all()->first();

        return response()->json([
            'valorDolar' => $dolar->valor,
            'estatus' => 'OK'
        ],202);
    }

    public function valor_dolar_save(Request $r) {
        $context = $r->all();

        $dolar = Tipo_Cambio::all()->first();
        $dolar->valor = $context['tipo_cambio'];
        $dolar->save();
        $parametros = [
            'tabla' => 'tipo_cambio',
            'objeto_modificado' => $dolar->idtipocambio,
            'idusuario' => usuario()->id,
            'fecha' => hoy(),
            'accion' => 'Agregar/Create',
        ];

        save_bitacora($parametros);
        return response()->json([
            'estatus' => 'OK',
            'valor' => $context['tipo_cambio']
        ], 202);
    }
}
