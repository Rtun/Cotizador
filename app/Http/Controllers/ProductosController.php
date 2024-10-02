<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\Cot_Producto;
use App\Models\Marca;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProductosController extends Controller
{
    public function listado() {
        //esta funcion muestra el listado de todos los productos que esten activos
        $productos = Cot_Producto::join('prod_marca', 'prod_marca.idmarca', '=', 'cot_productos.idmarca')
                                    ->join('prod_proveedor', 'prod_proveedor.idproveedor', '=', 'cot_productos.idproveedor')
                                    ->select(
                                        "idproductos",
                                        "prod_cve",
                                        "prod_cve_syscom",
                                        "prod_nombre",
                                        "prod_medicion",
                                        "prod_precio_brut",
                                        "prod_marca.m_nombre as marca",
                                        "modelo",
                                        "prod_proveedor.prv_nombre as proveedor",
                                        "prod_tipo"
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
        $ultimoProducto = Cot_Producto::orderBy('prod_cve', 'desc')->first();
        $nuevaClave = $ultimoProducto->prod_cve_producto + 1;

        switch ($context['operacion']) {

            case 'Agregar';
            $producto = new Cot_Producto();
            $producto->prod_cve = $nuevaClave;
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


    public function api_save_producto(Request $r) {
        $context = $r->all();

        if($context['operacion'] == 'Agregar'){
            switch ($context['key']) {
            case 'syscom':
                $marca = Marca::where('m_nombre', $context['idmarca'])->first();
                $proveedor = Proveedor::where('prv_nombre', $context['idproveedor'])->first();
                if(!$marca) {
                    $marca = new Marca();
                    $marca->m_nombre = $context['idmarca'];
                    $marca->idusuario = usuario()->id;
                    $marca->m_fecha_creacion = hoy();
                    $marca->save();
                }

                if(!$proveedor) {
                    $proveedor = new Proveedor();
                    $proveedor->prv_nombre = $context['idproveedor'];
                    $proveedor->idusuario = usuario()->id;
                    $proveedor->prv_fecha_creacion = hoy();
                    $proveedor->save();
                }
                $buscarProd = Cot_Producto::where('prod_cve_syscom', $context['cve_prod'])->first();

                if(!$buscarProd) {
                    $token = '43695a51107d9fabe57589a32c7498776c286be5954b5031b06989acf74c173c';
                    $response = Http::withHeaders([
                        'Bmx-Token' => $token,
                    ])->get('https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno');

                    $decodificacion = json_decode($response, true);
                    $dolar = $decodificacion['bmx']['series'][0]['datos'][0]['dato'] + .60;
                    $precio = $context['precio_prod'] * $dolar;


                    $producto = new Cot_Producto();
                    $producto->prod_cve_syscom = $context['cve_prod'];
                    $producto->prod_nombre = $context['nombre_prod'];
                    $producto->idmarca = $marca->idmarca;
                    $producto->modelo = $context['modelo'];
                    $producto->idproveedor = $proveedor->idproveedor;
                    $producto->prod_medicion = $context['medicion_prod'];
                    $producto->prod_precio_brut = $precio;
                    $producto->prod_tipo = $context['tipo_prod'];
                    $producto->status = 'AC';
                    $producto->save();

                    $parametros = [
                        'tabla' => 'cot_productos',
                        'objeto_modificado' => $producto->idproductos,
                        'idusuario' => usuario()->id,
                        'fecha' => hoy(),
                        'accion' => 'Agregar/Registrar',
                    ];
                    save_bitacora($parametros);
                }
                break;

            case 'tvc':
                dd('todo esto viene de tvc');
                break;
        }
    }

        return redirect()->to('/catalogos/listado/productos');
    }


    public function buscarProductos(Request $r){
        $context = $r->all();
        // Verificar si el token ha expirado
        if (ApiToken::isTokenExpired()) {
            // Solicitar un nuevo token (esto depende de cómo la API maneje la renovación)
            $newToken = $this->renovarToken();

            // Actualizar el token en la base de datos
            ApiToken::updateToken($context['key'], $newToken, Carbon::now()->addDays(365));
        }

        // Usar el token actualizado
        $token = ApiToken::getToken($context['key']);

        // Hacer la petición a la API externa
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get('https://developers.syscom.mx/api/v1/productos', [
            'busqueda' => $context['texto']
        ]);
        // Devolver los resultados
        return response()->json($response->json());
    }


    public function renovarToken()
    {
        // Aquí va la lógica para solicitar un nuevo token
        $response = Http::post('https://developers.syscom.mx/oauth/token', [
            'client_id' => env('CLIENT_ID_SYSCOM'),
            'client_secret' => env('CLIENT_SECRET_SYSCOM'),
            'grant_type' => 'client_credentials'
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Error al renovar el token');
    }
}
