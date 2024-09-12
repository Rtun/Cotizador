<?php

namespace App\Http\Controllers;

use App\Models\Cot_Producto;
use App\Models\Marca;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductosController extends Controller
{
    public function listado() {
        //esta funcion muestra el listado de todos los productos que esten activos
        $productos = Cot_Producto::join('users', 'users.id','=','cot_productos.idusuario')
                                    ->join('prod_marca', 'prod_marca.idmarca', '=', 'cot_productos.idmarca')
                                    ->join('prod_proveedor', 'prod_proveedor.idproveedor', '=', 'cot_productos.idproveedor')
                                    ->select(
                                        "idproductos",
                                        "prod_cve_producto",
                                        "prod_nombre",
                                        "prod_medicion",
                                        "prod_precio_brut",
                                        "prod_marca.m_nombre as marca",
                                        "modelo",
                                        "prod_proveedor.prv_nombre as proveedor",
                                        "users.name as nombre_usuario",
                                        "prod_tipo"
                                        ,DB::Raw("DATE_FORMAT(prod_fecha_creacion,'%Y-%m-%d') as fecha_creacion")
                                        )
                                    ->where('cot_productos.status', 'AC')
                                    ->get()->toArray();
        // dd($productos);
        $informacion = array();
        $informacion['productos'] = $productos;
        return view('listados.listado_productos')->with($informacion);
    }

    public function form_productos(Request $r) {
        $context = $r->all();

        // valida el verbo http que realiza la peticion para determinar que operacion se desea realizar
        if($r->isMethod('post')) {
            $operacion = 'Agregar';
            $producto = new Cot_Producto();
        }
        else {
            $operacion = 'Editar';
            $producto = Cot_Producto::find($context['idproducto']);
        }
        $marcas = Marca::where('m_status', 'AC')->get()->toArray();
        $proveedores = Proveedor::where('prv_status', 'AC')->get()->toArray();
        $informacion = array();
        $informacion['operacion'] = $operacion;
        $informacion['marcas'] = $marcas;
        $informacion['proveedores'] = $proveedores;
        $informacion['producto'] = $producto;

        return view('catalogos.form_productos')->with($informacion);
    }

    public function save_productos(Request $r) {
        $context = $r->all();
        $status = '';
        $ultimoProducto = Cot_Producto::orderBy('prod_cve_producto', 'desc')->first();
        $nuevaClave = $ultimoProducto->prod_cve_producto + 1;

        switch ($context['operacion']) {

            case 'Agregar';
            $producto = new Cot_Producto();
            $producto->prod_cve_producto = $nuevaClave;
            $producto->prod_nombre = $context['nombre_prod'];
            $producto->idmarca = $context['idmarca'];
            $producto->modelo = $context['modelo'];
            $producto->idproveedor = $context['idproveedor'];
            $producto->prod_medicion = $context['medicion_prod'];
            $producto->prod_precio_brut = $context['precio_prod'];
            $producto->idusuario = usuario()->id;
            $producto->prod_fecha_creacion = hoy();
            $producto->prod_tipo = $context['tipo_prod'];
            $producto->status = 'AC';
            $producto->save();
            $status = 'OK';
                break;

            case 'Editar';
            $producto = Cot_Producto::find($context['idproducto']);
            $producto->prod_cve_producto = $nuevaClave;
            $producto->prod_nombre = $context['nombre_prod'];
            $producto->idmarca = $context['idmarca'];
            $producto->modelo = $context['modelo'];
            $producto->idproveedor = $context['idproveedor'];
            $producto->prod_medicion = $context['medicion_prod'];
            $producto->prod_precio_brut = $context['precio_prod'];
            if(isset($context['tipo_prod'])){
                $producto->prod_tipo = $context['tipo_prod'];
            }
            $producto->save();
            $status = 'OK';
                break;

            case 'Eliminar';
            $producto = Cot_Producto::find($context['idproducto']);
            $producto->status = 'BJ';
            $producto->save();
            $status = 'OK';
                break;
        }

        if($status == 'OK') {
            $parametros = [
                'tabla' => 'cot_productos',
                'objeto_modificado' => $producto->idproductos,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }

        return redirect()->to('/catalogos/listado/productos');
    }
}
