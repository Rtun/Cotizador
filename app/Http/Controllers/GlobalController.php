<?php

namespace App\Http\Controllers;

use App\Models\Cot_Producto;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\SqlServerModel;
use App\Models\Tipo_Cambio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalController extends Controller
{
    public function valor_dolar() {
        $dolar = Tipo_Cambio::all()->first();

        return response()->json([
            'dolar' => $dolar->valor,
            'porcentaje' => $dolar->porcentaje,
            'dolar_api' => $dolar->valor_api,
            'estatus' => 'OK'
        ],202);
    }

    public function valor_dolar_save(Request $r) {
        $context = $r->all();

        $dolar = Tipo_Cambio::all()->first();

        if($dolar) {
            $dolar->valor = $context['tipo_cambio'];
            $dolar->porcentaje = $context['porcentaje'];
            $dolar->valor_api = $context['valorDolar'];
            $dolar->save();
        }
        else {
            $dolar = new Tipo_Cambio();
            $dolar->valor = $context['tipo_cambio'];
            $dolar->porcentaje = $context['porcentaje'];
            $dolar->valor_api = $context['valorDolar'];
            $dolar->save();
        }
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

    public function guardar_prods(){
        $consulta_sqlserver = DB::connection('sqlsrv')
        ->table('Producto')
        ->join('Marca', 'Producto.Mr_Cve_Marca', '=', 'Marca.Mr_Cve_Marca')
        ->join('Proveedor', 'Producto.Pv_Cve_Proveedor', '=', 'Proveedor.Pv_Cve_Proveedor')
        ->select(
            "Producto.Pr_Cve_Producto as clave",
            "Producto.Pr_Descripcion as nombre",
            "Producto.Pr_Descripcion_Corta as modelo",
            "Marca.Mr_Descripcion as marca",
            "Proveedor.Pv_Descripcion as proveedor",
            "Proveedor.Pv_Razon_Social as razon",
            "Proveedor.Pv_R_F_C as rfc",
            "Proveedor.Pv_Direccion_1 as direccion",
            "Proveedor.Pv_Direccion_2 as calle",
            "Proveedor.Pv_Telefono_1 as telefono",
            "Proveedor.Pv_ciudad as ciudad",
            "Proveedor.Pv_Estado as estado",
            "Proveedor.Pv_Pais as pais",
            "Proveedor.Pv_Contacto_1 as contacto",
            "Proveedor.Pv_email_contacto_1 as mail",
            "Producto.Pr_Unidad_Control_1 as unidad",
            "Producto.Pr_Costo_Promedio as precio",
            "Producto.Es_Cve_Estado as status"

        )
        ->where('Producto.Es_Cve_Estado', 'AC')
        ->get();
        $usuario = usuario()->id;

        $fecha = hoy();
        foreach( $consulta_sqlserver as $prod) {
            $findmarca = Marca::where('m_nombre', $prod->marca)->first();
            if($findmarca) {
                $marca = $findmarca->idmarca;
            }
            else {
                $newmarca = new Marca();
                $newmarca->m_nombre = $prod->marca;
                $newmarca->save();
                $marca = $newmarca->idmarca;
            }
            $findproveedor = Proveedor::where('prv_nombre', $prod->proveedor)->first();
            if($findproveedor) {
                $proveedor = $findproveedor->idproveedor;
            }
            else {
                $newproveedor = new Proveedor();
                $newproveedor->prv_nombre = $prod->proveedor;
                $newproveedor->idusuario = $usuario;
                $newproveedor->prv_fecha_creacion = $fecha;
                $newproveedor->prv_rfc = $prod->rfc;
                $newproveedor->prv_razon_social = $prod->razon;
                $newproveedor->prv_direccion = $prod->direccion.' '.$prod->calle;
                $newproveedor->prv_telefono = $prod->telefono;
                $newproveedor->prv_ciudad = $prod->ciudad;
                $newproveedor->prv_estado = $prod->estado;
                $newproveedor->prv_pais = $prod->pais;
                $newproveedor->prv_contacto = $prod->contacto;
                $newproveedor->prv_email = $prod->mail;
                $newproveedor->save();
                $proveedor = $newproveedor->idproveedor;
            }
            $findprod = Cot_Producto::where('prod_cve', $prod->clave)->first();
            if(!$findprod && is_int($prod->clave)) {
                $producto = new Cot_Producto();
                $producto->prod_cve = $prod->clave;
                $producto->prod_nombre = $prod->nombre;
                $producto->idmarca = $marca;
                $producto->modelo = $prod->modelo;
                $producto->idproveedor = $proveedor;
                $producto->prod_medicion = $prod->unidad;
                $producto->prod_precio_brut = $prod->precio;
                $producto->prod_tipo = 'PRODUCTO';
                $producto->save();
            }
        }

        dd($consulta_sqlserver);
    }
}
