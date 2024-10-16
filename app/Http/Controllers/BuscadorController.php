<?php

namespace App\Http\Controllers;

use App\Models\Cot_Encabezado;
use App\Models\Cot_Producto;
use Illuminate\Http\Request;

class BuscadorController extends Controller
{
    //Logica para el buscador
    public function buscador(Request $r) {
        $context = $r->all();

        $productos  = Cot_Producto::join('prod_marca', 'prod_marca.idmarca', '=', 'cot_productos.idmarca')
        ->join('prod_proveedor', 'prod_proveedor.idproveedor', '=', 'cot_productos.idproveedor')
        ->whereRaw(
            "prod_cve like '%".$context['criterio']."%'
             or prod_cve_syscom like '%".$context['criterio']."%'
             or prod_cve_tvc like '%".$context['criterio']."%'
             or prod_nombre like '%".$context['criterio']."%'
             or prod_marca.m_nombre like '%".$context['criterio']."%'
             or prod_proveedor.prv_nombre like '%".$context['criterio']."%'"
             )
        ->where('cot_productos.status', 'AC')
        ->get()->toArray();
         $resultados = [
            'productos' => $productos
         ];

         dd('Resultado de la consulta => ',$resultados);
     return response()->json($resultados);
    }
}
