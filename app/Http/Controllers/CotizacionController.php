<?php

namespace App\Http\Controllers;

use App\Models\Adicionales;
use App\Models\AdicionalxServicio;
use App\Models\Concepto;
use App\Models\Cot_Clientes;
use App\Models\Cot_Detalle;
use App\Models\Cot_Producto;
use App\Models\Cot_Encabezado;
use App\Models\Servicios;
use App\Models\SqlServerModel;
use App\Models\TipoServicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Storage;

class CotizacionController extends Controller
{
    private function obtener_Token_syscom() {
        $client_id = 'aPYL5OpJ1RjJRtkgg7pwFOWUdSa4W0Yd';
        $client_secret = '5a8lDX2zMZ7RUbP7coJPyqJCMkaoTqeYNdQuvz8L';
        $response = Http::post('https://developers.syscom.mx/oauth/token',[
            'grant_type' => 'client_credentials',
            'client_id' => 'aPYL5OpJ1RjJRtkgg7pwFOWUdSa4W0Yd',
            'client_secret' => '5a8lDX2zMZ7RUbP7coJPyqJCMkaoTqeYNdQuvz8L'
        ]);

        return $response->json();

        dd($response, $response['access_token']);
    }

    public function pruebas () {
    //    $dollar = file_get_contents("https://mx.dolarapi.com/v1/cotizaciones/usd");
    //    dd($dollar);
        $token = $this->obtener_Token_syscom();
        // dd($token['access_token']);
        $obtener_productos = Http::withHeaders([
            'Authorization' => 'Bearer '.$token['access_token'],
            'Accept' => 'application/json',
        ])->get('https://developers.syscom.mx/api/v1/categorias/206');
            dd($obtener_productos);
        if ($obtener_productos->successful()) {
            $products = $obtener_productos->json();
            dd($products);
        } else {
            return response()->json(['error' => 'No se pudieron obtener los productos'], $obtener_productos->status());
        }
    }

    private function obtener_Token() {//este metodo obtiene el token de la api por medio del login
        $client = new Client();

        // Paso 1: Obtener el Token
        $response = $client->request('GET', 'https://www.datacrm.la/datacrm/comsitec/webservice.php?', [
            'query' => [
                'operation' => 'getchallenge',//peticion de token
                'username' => 'Ismael Patino',//nombre de usuario de dataCRM
            ],
            'verify' => 'C:\wamp64\www\certificado', //sertificado SSl
        ]);

        $challenge = json_decode($response->getBody()->getContents(), true);
        // Verifica que el Token se haya obtenido correctamente
        if (!isset($challenge['result']['token'])) {
            throw new \Exception('No se pudo obtener el token de desafío.');
        }

        $token = $challenge['result']['token'];
        $accessKey = md5($token . 'dVfN6UTR9K2d9ZvA'); //aca va la access key de la cuenta del dataCRM
        // Paso 2: Hacer la solicitud de inicio de sesión
        $response = $client->request('POST', 'https://www.datacrm.la/datacrm/comsitec/webservice.php?', [
            'form_params' => [
                'operation' => 'login',//tipo de peticion
                'username' => 'Ismael Patino',
                'accessKey' => $accessKey,
            ],
            'verify' => 'C:\wamp64\www\certificado', //Sertificado SSL
        ]);
        $loginResponse = json_decode($response->getBody()->getContents(), true); //devuelve un token de inicio de sesion
        // Verifica que el inicio de sesión se haya realizado correctamente
        if (!isset($loginResponse['result']['sessionName'])) {
            throw new \Exception('No se pudo iniciar sesión.');
        }

        $sessionName = $loginResponse['result']['sessionName'];
        session(['dataCRM_sessionName' => $sessionName]);
        return $sessionName;
    }


    public function showForm() {
        // Esta funcion envia al formulario de cotizaciones con todos los datos para proceder
        // realiza la consulta al sql server para obtener los productos
        // $consulta_sqlserver = SqlServerModel::where('Es_Cve_Estado', 'AC')->get();
        $productosSql = array();
        // foreach( $consulta_sqlserver as $prod_sqlserver) {
        //     $producto = array(0);
        //     $producto ['cve_producto'] = $prod_sqlserver->Pr_Cve_Producto;
        //     $producto ['nombre'] = $prod_sqlserver->Pr_Descripcion;
        //     $producto ['unit_med'] = $prod_sqlserver->Pr_Unidad_Venta;
        //     $producto ['costo_unidad'] = $prod_sqlserver->Pr_Costo_Promedio;

        //     $productosSql [] = $producto;
        // }
        // fin de la consulta a sql server

        //consulta  en mySQL
        $cons_cli_mysql = Cot_Clientes::all()->toArray();
        $cons_prod_mysql = DB::table('cot_productos')
                                ->join('prod_marca', 'prod_marca.idmarca', '=', 'cot_productos.idmarca') // Cambiado idmarca
                                ->join('prod_proveedor', 'prod_proveedor.idproveedor', '=', 'cot_productos.idproveedor')
                                ->select(
                                    "cot_productos.idproductos", // Asegúrate de que este es el campo correcto de la tabla cot_productos
                                    "cot_productos.prod_cve_producto as cve_producto",
                                    "cot_productos.prod_nombre as nombre",
                                    "cot_productos.prod_medicion as unit_med",
                                    "cot_productos.prod_precio_brut as costo_unidad",
                                    "prod_marca.m_nombre as marca",
                                    "cot_productos.modelo as modelo",
                                    "prod_proveedor.prv_nombre as proveedor"
                                )
                                ->where('cot_productos.status', 'AC')
                                ->get()->toArray();
        $productos = array_merge($productosSql, $cons_prod_mysql);

        // peticion a la api de dataCRM para obtener a los clientes
        // $client = new Client();
        // $sessionName = session('dataCRM_sessionName');
        // if(!$sessionName) {
        //     $sessionName = $this->obtener_Token();
        // }

        // Solicitud para obtener los clientes
        $clientes_CRM = [];
        // $page = 0;
        // $limit = 100; // Límite de registros por página
        // do {
        //     $response = $client->request('GET', 'https://www.datacrm.la/datacrm/comsitec/webservice.php?', [
        //         'query' => [
        //             'operation' => 'query',
        //             'sessionName' => $sessionName,//la obtiene de la funcion obtener_Token
        //             'query' => "SELECT account_no, accountname FROM Accounts LIMIT $page, $limit;", // Consulta con paginación
        //         ],
        //         'verify' => 'C:\wamp64\www\certificado', // certificado SSL
        //     ]);

        //     $result = json_decode($response->getBody()->getContents(), true);

        //     if (isset($result['result'])) {
        //         $clientes_CRM = array_merge($clientes_CRM, $result['result']);
        //     }

        //     $page += $limit;

        // } while (count($result['result']) == $limit);
        $clientes = [];
        $clientes = array_merge($clientes_CRM, $cons_cli_mysql);
        // fin de la peticion a la api dataCRM

        //informacion del asesor
        $asesor = Auth::user();
        $utilidad = TipoServicio::where('status','AC')->get()->toArray();
        $adicional = Adicionales::where('status', 'AC')->get()->toArray();
        $textos = Concepto::where('con_status', 'AC')->get()->toArray();
        // $dollar = file_get_contents("https://mx.dolarapi.com/v1/cotizaciones/usd");

        // fin de la informacion del asesor
        $informacion = array();
        $informacion['productos'] = $productos;
        $informacion['clientes'] = $clientes;
        $informacion['asesor'] = $asesor;
        $informacion['utilidad'] = $utilidad;
        $informacion['adicional'] = $adicional;
        $informacion['conceptos'] = $textos;

        return view('cotizaciones.prev_cotizacion')->with($informacion);
    }


    public function showForm_editar($idcotizacion) {
        // dd($context);
        //Esta funcion envia al formulario de edicion decotizaciones
        //amigo programador fue realizado de esta manera porq no encontre tra forma de realizarlo
        $cotizacionesRaw = DB::table('cot_encabezado')
                            ->join('users', 'users.id', '=', 'cot_encabezado.idusuario')
                            ->join('conceptos', 'conceptos.idconcepto', '=', 'cot_encabezado.cot_concepto')
                            ->join('cot_clientes', 'cot_clientes.idclientes', '=', 'cot_encabezado.idcliente')
                            ->join('cot_detalle', 'cot_detalle.idcotizacion', '=', 'cot_encabezado.idcotizacion')
                            ->join('cot_productos', 'cot_productos.idproductos', '=', 'cot_detalle.cotdet_id_producto')
                            ->join('prod_marca', 'prod_marca.idmarca', '=', 'cot_productos.idmarca')
                            ->join('prod_proveedor', 'prod_proveedor.idproveedor', '=', 'cot_productos.idproveedor')
                            ->leftJoin('cot_impuestos', 'cot_impuestos.idcotimpuestos', '=', 'cot_detalle.cotdet_iva')
                            ->leftJoin('adicionalxservicio', 'adicionalxservicio.id_cot_detalle', '=', 'cot_detalle.idcot_detalle')
                            ->leftJoin('cot_adicionales', 'cot_adicionales.idcotadicionales', '=', 'adicionalxservicio.idadicional')
                            ->select(
                                'cot_encabezado.idcotizacion as idcotizacion',
                                'cot_encabezado.cot_num_crm as crm',
                                'cot_encabezado.cot_encabezado as encabezado',
                                'cot_encabezado.idcliente as idcliente',
                                'cot_encabezado.estado_cot as estado_cot',
                                'cot_encabezado.cot_concepto as idtexto',
                                'conceptos.con_texto as concepto',
                                'cot_clientes.cli_nombre as cli_nombre',
                                'cot_clientes.cli_telefono as cli_telefono',
                                'cot_clientes.cli_correo as cli_correo',
                                'cot_clientes.cli_empresa as cli_empresa',
                                'cot_clientes.cli_puesto as cli_puesto',
                                'cot_detalle.cotdet_id_producto as id',
                                'cot_detalle.cotdet_moneda as cotdet_moneda',
                                'cot_detalle.cotdet_utilidad as utilidad',
                                'cot_detalle.cotdet_cantidad as cantidad',
                                'cot_detalle.cotdet_precio_brut as prod_precio',
                                'cot_detalle.cotdet_precio_desperdicio as prod_precio_desperdicio',
                                'cot_detalle.cotdet_precio_adicionales as prod_precio_adicionales',
                                'cot_detalle.cotdet_descripcion as descripcion',
                                'cot_detalle.cotdet_subtotal as total',
                                'cot_productos.prod_nombre as prod_nombre',
                                'cot_productos.prod_medicion as prod_medicion',
                                'cot_productos.modelo as modelo',
                                'prod_marca.m_nombre as marca',
                                'prod_proveedor.prv_nombre as proveedor',
                                'cot_detalle.cotdet_tipo_cot as tipo_cot',
                                'cot_impuestos.valor as iva',
                                DB::raw('GROUP_CONCAT(cot_adicionales.idcotadicionales SEPARATOR ", ") as idadicional'),
                                DB::raw('GROUP_CONCAT(cot_adicionales.cotad_nombre SEPARATOR ", ") as nombre_adicional'),
                                DB::raw('GROUP_CONCAT(adicionalxservicio.cantidad SEPARATOR ", ") as cantidad_adicional'),
                                DB::raw('GROUP_CONCAT(adicionalxservicio.precio_bruto SEPARATOR ", ") as precio_adicional'),
                                DB::raw('GROUP_CONCAT(adicionalxservicio.subtotal SEPARATOR ", ") as total_adicional')
                            )
                            ->groupBy(
                                'cot_encabezado.idcotizacion',
                                'cot_encabezado.cot_num_crm',
                                'cot_encabezado.cot_encabezado',
                                'cot_encabezado.estado_cot',
                                'cot_encabezado.cot_concepto',
                                'cot_encabezado.idcliente',
                                'conceptos.con_texto',
                                'cot_clientes.cli_nombre',
                                'cot_clientes.cli_telefono',
                                'cot_clientes.cli_correo',
                                'cot_clientes.cli_empresa',
                                'cot_clientes.cli_puesto',
                                'cot_detalle.cotdet_moneda',
                                'cot_detalle.cotdet_utilidad',
                                'cot_detalle.cotdet_cantidad',
                                'cot_detalle.cotdet_precio_brut',
                                'cot_detalle.cotdet_precio_desperdicio',
                                'cot_detalle.cotdet_precio_adicionales',
                                'cot_detalle.cotdet_descripcion',
                                'cot_detalle.cotdet_id_producto',
                                'cot_productos.prod_nombre',
                                'cot_productos.prod_medicion',
                                'cot_productos.modelo',
                                'prod_proveedor.prv_nombre',
                                'prod_marca.m_nombre',
                                'cot_detalle.cotdet_tipo_cot',
                                'cot_impuestos.valor',
                                'cot_detalle.cotdet_subtotal',
                            )
                            ->where('cot_encabezado.idcotizacion', $idcotizacion)
                            ->whereRaw("LOWER(cot_encabezado.status) = 'ac'")
                            ->get()
                            ->map(function ($cotizacion) {
                                $adicionales = [];
                                $id = explode(', ',$cotizacion->idadicional);
                                $nombres = explode(', ', $cotizacion->nombre_adicional);
                                $cantidades = explode(', ', $cotizacion->cantidad_adicional);
                                $precios = explode(', ', $cotizacion->precio_adicional);
                                $totales = explode(', ', $cotizacion->total_adicional);

                                foreach ($id as $index => $ids) {
                                    $adicionales[] = [
                                        'idcotadicionales' =>$ids,
                                        'cotad_nombre' => $nombres[$index],
                                        'cotad_cantidad' => $cantidades[$index],
                                        'cotad_precio' => $precios[$index],
                                        'cotad_total' => $totales[$index],
                                    ];
                                }

                                $cotizacion->adicionales = $adicionales;
                                unset($cotizacion->nombre_adicional, $cotizacion->cantidad_adicional, $cotizacion->precio_adicional, $cotizacion->total_adicional);

                                return $cotizacion;
                            });

        $cotizacionesAgrupadas = [];
        // Transforma los datos obtenidos para la lectura y evitar duplicar campos antes de enviarselo al front
        foreach ($cotizacionesRaw as $item) {
            $cotizacionId = $item->idcotizacion;

            if (!isset($cotizacionesAgrupadas[$cotizacionId])) {
                $cotizacionesAgrupadas[$cotizacionId] = [
                    'idcotizacion' => $item->idcotizacion,
                    'crm' => $item->crm,
                    'encabezado' => $item->encabezado,
                    'concepto' => $item->concepto,
                    'idcliente' => $item->idcliente,
                    'idtexto' => $item->idtexto,
                    'cli_nombre' => $item->cli_nombre,
                    'cli_telefono' => $item->cli_telefono,
                    'cli_correo' => $item->cli_correo,
                    'cli_empresa' => $item->cli_empresa,
                    'cli_puesto' => $item->cli_puesto,
                    'estado_cot' => $item->estado_cot,
                    'detalles' => [],
                ];
            }

            $detalle = [
                'id' => $item->id,
                'descripcion' => $item->descripcion,
                'costo_unidad' => $item->prod_precio,
                'moneda' => $item->cotdet_moneda,
                'cantidad' => $item->cantidad,
                'nombre' => $item->prod_nombre,
                'marca' => $item->marca,
                'modelo' => $item->modelo,
                'proveedor' => $item->proveedor,
                'unit_med' => $item->prod_medicion,
                'costo_u_document' => $item->prod_precio,
                'utilidad' => $item->utilidad,
                'costo_desperdicio' => $item->prod_precio_desperdicio,
                'costo_adicionales' => $item->prod_precio_adicionales,
                'precioTotal' => $item->total,
                'tipo' => $item->tipo_cot,
                'iva' => $item->iva,
                'adicionales' => $item->adicionales
            ];

            $cotizacionesAgrupadas[$cotizacionId]['detalles'][] = $detalle;
        }
        // dd($cotizacionesAgrupadas);
        //consulta  en mySQL
        $cons_cli_mysql = Cot_Clientes::all()->toArray();
        $cons_prod_mysql = DB::table('cot_productos')
                                ->join('prod_marca', 'prod_marca.idmarca', '=', 'cot_productos.idmarca') // Cambiado idmarca
                                ->join('prod_proveedor', 'prod_proveedor.idproveedor', '=', 'cot_productos.idproveedor')
                                ->select(
                                    "cot_productos.idproductos", // Asegúrate de que este es el campo correcto de la tabla cot_productos
                                    "cot_productos.prod_cve_producto as cve_producto",
                                    "cot_productos.prod_nombre as nombre",
                                    "cot_productos.prod_medicion as unit_med",
                                    "cot_productos.prod_precio_brut as costo_unidad",
                                    "prod_marca.m_nombre as marca",
                                    "cot_productos.modelo as modelo",
                                    "prod_proveedor.prv_nombre as proveedor"
                                )
                                ->where('cot_productos.status', 'AC')
                                ->get()->toArray();

        //informacion del asesor
        $asesor = Auth::user();
        $utilidad = TipoServicio::where('status','AC')->get()->toArray();
        $adicional = Adicionales::where('status', 'AC')->get()->toArray();
        $textos = Concepto::where('con_status', 'AC')->get()->toArray();

        // fin de la informacion del asesor
        $informacion = array();
        $informacion['productos'] = $cons_prod_mysql;
        $informacion['clientes'] = $cons_cli_mysql;
        $informacion['asesor'] = $asesor;
        $informacion['utilidad'] = $utilidad;
        $informacion['adicional'] = $adicional;
        $informacion['conceptos'] = $textos;
        $informacion['cotizacionEdit'] = $cotizacionesAgrupadas;
        // dd($informacion);
        return view('cotizaciones.prev_cotizacion_editar')->with($informacion);
    }


    public function save_form(Request $r) {
        //Esta funcion guarda los datos que se envian del formulario de cotizaciones
        $context = $r->all();
        $user = Auth::user();
        $fecha = hoy();
        $tablas_afectadas = [];//se guardan todas las tablas a las q el usuario afectara
        $existEmp = Cot_Encabezado::where('cot_num_crm', $context['crm'])->first();

        if($existEmp) {
            $validacion = Cot_Clientes::where('idclientes', $existEmp->idcliente)->first();
            if($validacion->cli_empresa != $context['cot_empresa_cli']) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'La empresa no coincide con el numero de crm anteriormente asociada, la empresa asociada es: '.'"'.$validacion->cli_empresa.'"',
                ], 201);
            }
        }

        $imprimir = $this->descargar_cotizacion($context);
        $totalProds = count($context['productos']) + count($context['servicios']);
        // guarda la cotizacion
        $tabla_cot = new Cot_Encabezado();
        $tabla_cot->idusuario = $user->id;
        $tabla_cot->idcliente = $context['idcliente'];
        $tabla_cot->cot_num_crm = $context['crm'];
        $tabla_cot->cot_encabezado = $context['cot_encabezado'];
        $tabla_cot->cot_concepto = $context['idconcepto'];
        $tabla_cot->cot_prod_cantidad = $totalProds;
        $tabla_cot->cot_fecha_creacion = $fecha;
        $tabla_cot->cot_fecha_modificacion = $fecha;
        $tabla_cot->cot_documento = $imprimir->name;
        $tabla_cot->status = 'AC';
        $tabla_cot->save();

        $idcotizacion = $tabla_cot->idcotizacion;
        // guarda los productos de la cotizacion
        if(count($context['productos']) > 0){
            $detalles = array_merge($context['productos'], $context['servicios']);
            foreach ($detalles as $producto) {
                $tabla_cot_prod = new Cot_Detalle();
                $tabla_cot_prod->idcotizacion = $idcotizacion;
                $tabla_cot_prod->cotdet_id_producto = $producto['id'];
                $tabla_cot_prod->cotdet_cantidad = $producto['cantidad'];
                $tabla_cot_prod->cotdet_precio_brut = $producto['costo_unidad'];
                $tabla_cot_prod->cotdet_precio_desperdicio = $producto['costo_desperdicio'];
                $tabla_cot_prod->cotdet_precio_adicionales = $producto['costo_adicionales'];
                $tabla_cot_prod->cotdet_utilidad = $producto['utilidad'];
                $tabla_cot_prod->cotdet_tipo_cot = $producto['tipo'];
                if($producto['tipo'] === 'SR') {
                    $tabla_cot_prod->cotdet_descripcion = $producto['nombre'];
                }
                $tabla_cot_prod->cotdet_subtotal =$producto['precioTotal'];
                $tabla_cot_prod->cotdet_moneda = $producto['moneda'];
                $tabla_cot_prod->cotdet_iva = 1;
                $tabla_cot_prod->save();

                if(isset($producto['adicionales']) && count($producto['adicionales']) > 0 && $producto['tipo'] == 'SR') {
                    foreach ( $producto['adicionales'] as $adic) {
                        $subtotal = 0;
                        $adicionales = new AdicionalxServicio();
                        $adicionales->idcotizacion = $idcotizacion;
                        $adicionales->id_cot_detalle = $tabla_cot_prod->idcot_detalle;
                        $adicionales->idadicional = $adic['idcotadicionales'];
                        $adicionales->cantidad = $adic['cotad_cantidad'];
                        $adicionales->precio_bruto = $adic['cotad_precio'];
                        $subtotal = $adic['cotad_precio'] * $adic['cotad_cantidad']; //cambiar el uno por la cantidad q envie el usuario
                        $adicionales->subtotal = $subtotal;
                        $adicionales->total = ($subtotal * .16) + $subtotal;
                        $adicionales->save();
                    }
                    $tablas_afectadas[] = 'AdicionalxServicio';
                }
            }
            $tablas_afectadas[] = 'cot_detalle';
        }
// dd('si termine');
        $tablas_afectadas[] = 'cotizacion';
        $parametros = [
            'tabla' => $tablas_afectadas,
            'objeto_modificado' => $idcotizacion,
            'idusuario' => $user->id,
            'fecha' => $fecha,
            'accion' => 'Agregar/Create',
        ];

        save_bitacora($parametros);//save bitacora es una funcion de un helper, ubicado en app/models/helpers.php
        $safeFilename = urlencode($imprimir->name);
        return response()->json([
            'status' => 'OK',
            'message' => 'Cotización guardada con éxito',
            // 'data' => $context,
            'file' => url('/descargar-cotizacion/'.$safeFilename)

        ], 201);
    }

    public function descargar_cotizacion($context) {
        // Esta funcion crea el documento excel con los datos que recibe el metodo saveForm
        $datos = $context;
        $user = Auth::user();
        $nombre_doc = 'cotizacion.xlsx';
        $spreadsheet = IOFactory::load(storage_path('app/formatos/'.$nombre_doc));
        $cp = 18;
        $in_fil = 19;
        $suma=0;
        // Configurar la localización a español
        setlocale(LC_TIME, 'es_ES.UTF-8');
        Carbon::setLocale('es');

        // Obtener y formatear la fecha actual
        $fecha = Carbon::now();
        $crm = 'Cotización: CRM-'.$datos['crm'];
        $fechaFormateada = "Mérida, Yucatán a " . $fecha->isoFormat('dddd D \d\e MMMM \d\e\l Y');
        $elementos = array_merge($datos['productos'], $datos['servicios']);
        $spreadsheet->getActiveSheet()->getCell('A8')->setValue($fechaFormateada);
        $spreadsheet->getActiveSheet()->getCell('A16')->setValue($datos['cot_encabezado']);
        $spreadsheet->getActiveSheet()->getCell('B10')->setValue($datos['cot_nombre_cli']);
        $spreadsheet->getActiveSheet()->getCell('B11')->setValue($datos['cot_empresa_cli']);
        $spreadsheet->getActiveSheet()->getCell('B14')->setValue($datos['cot_concepto']);
        $spreadsheet->getActiveSheet()->getCell('D10')->setValue($crm);


        //inserta filas necesarias de acuerdo al numero de productos que se envio para cotizar
        $sheet = $spreadsheet->getActiveSheet();
        $numProductos = count($elementos);
        if( $numProductos > 1) {
            $sheet->insertNewRowBefore($in_fil,( $numProductos - 1));
        }

        // Estilo de bordes
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        foreach ($elementos as $lista) {
            $nombreProducto = $lista['nombre'];
            $precio = 0;
            $precio = ($lista['costo_u_document'] + $lista['costo_adicionales'] + $lista['costo_desperdicio']) / (1 - $lista['utilidad']);
            if($lista['tipo'] == 'SR') {
                $cantidad = 1;
            }
            else {
                $cantidad = $lista['cantidad'];
            }
            if($context['show_detalle'] && $lista['tipo'] != 'SR') {
                // Concatenar nombre y detalles en una sola celda
                $detalles = $lista['marca'].' | '.$lista['modelo'].' | '.$lista['proveedor'];
                $nombreProducto .= "\n" . $detalles;
            }
            $sheet->setCellValue("A$cp", $lista['unit_med']);
            $sheet->setCellValue("B$cp", $cantidad);
            $sheet->setCellValue("C$cp", $nombreProducto);
            $sheet->setCellValue("D$cp", $precio);
            $sheet->setCellValue("E$cp", ($cantidad * $precio));


            $sheet->getStyle("A$cp:E$cp")->applyFromArray($styleArray);
            $suma += $precio * $cantidad;
            $cp++;
        }

        $finProductos = $cp;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E$finProductos", $suma);
        $iva = $suma * .16;
        $total = $suma + $iva;
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E".($finProductos + 1), $iva);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("E".($finProductos + 2), $total);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B".($finProductos + 10), $user->name);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B".($finProductos + 11), 'Ing. Preventa');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("B".($finProductos + 14), $user->email);

        $name='CRM_'.$context['crm'].'_'.str_replace(' ', '_',$context['cot_encabezado']).'_'.str_replace(' ', '_', $context['cot_nombre_cli']).'_'.str_replace(' ', '_', $context['cot_empresa_cli']).'_'.time();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save(storage_path('app/documentos/'.$name.'.xlsx'));

        return (object) ['name' => $name];
    }

    public function listado_cotizaciones () {
        //Este metodo muestra y agrupa las consultas por numero de crm
        if(usuario()->idrol === 1) {
            $cotizaciones = DB::table('cot_encabezado')
                            ->join('users', 'id','=','cot_encabezado.idusuario')
                            ->join('cot_clientes', 'idclientes','=','cot_encabezado.idcliente')
                            ->select(
                                "cot_num_crm as crm",
                                DB::raw('GROUP_CONCAT(DISTINCT cot_clientes.cli_nombre SEPARATOR ", ") as cliente'),
                                DB::raw('GROUP_CONCAT(DISTINCT cot_clientes.cli_empresa SEPARATOR ", ") as empresa')
                                )
                                ->where('cot_encabezado.status', 'AC')
                                ->groupBy('cot_encabezado.cot_num_crm')
                                ->get()->toArray();
        }
        else{
            $cotizaciones = DB::table('cot_encabezado')
                            ->join('users', 'id','=','cot_encabezado.idusuario')
                            ->join('cot_clientes', 'idclientes','=','cot_encabezado.idcliente')
                            ->select(
                                "cot_num_crm as crm",
                                DB::raw('GROUP_CONCAT(DISTINCT cot_clientes.cli_nombre SEPARATOR ", ") as cliente'),
                                DB::raw('GROUP_CONCAT(DISTINCT cot_clientes.cli_empresa SEPARATOR ", ") as empresa')
                                )
                                ->where('cot_encabezado.idusuario', usuario()->id)
                                ->where('cot_encabezado.status', 'AC')
                                ->groupBy('cot_encabezado.cot_num_crm')
                                ->get()->toArray();
        }
        $informacion = array();
        $informacion['cotizaciones'] = $cotizaciones;
        $informacion['usuario'] = usuario();
        return view('cotizaciones.listado_cotizaciones')->with($informacion);
    }

    public function cotizacion_x_crm ($crm) {
        //Este metodo realiza la consulta con el numero de crm seleccionado desde el front y devuelve una segunda vista con el resultados
        // $documento = storage_path('app/documentos/');
        if (usuario()->idrol === 1) {
            $cotizaciones = DB::table('cot_encabezado')
                            ->join('users', 'id','=','cot_encabezado.idusuario')
                            ->join('cot_clientes', 'idclientes','=','cot_encabezado.idcliente')
                            ->select(
                                "idcotizacion",
                               "users.name as user_nombre",
                               "cot_clientes.cli_nombre as cliente",
                               "cot_num_crm as crm",
                               "cot_encabezado as encabezado",
                               "cot_prod_cantidad as conteo",
                               "cot_documento as documento",
                               "estado_cot",
                                DB::Raw("DATE_FORMAT(cot_fecha_creacion,'%d-%m-%Y') as fecha_creacion"),
                                DB::Raw("DATE_FORMAT(cot_fecha_modificacion,'%d-%m-%Y') as fecha_modificacion"),
                                DB::Raw("DATE_FORMAT(cot_fecha_cierre,'%d-%m-%Y') as fecha_cierre"))
                            ->where('cot_num_crm', $crm)
                            ->where('cot_encabezado.status', 'AC')
                            ->get()
                            ->toArray();
        }
        else{
            $cotizaciones = DB::table('cot_encabezado')
                            ->join('users', 'id','=','cot_encabezado.idusuario')
                            ->join('cot_clientes', 'idclientes','=','cot_encabezado.idcliente')
                            ->select(
                                "idcotizacion",
                               "users.name as user_nombre",
                               "cot_clientes.cli_nombre as cliente",
                               "cot_num_crm as crm",
                               "cot_encabezado as encabezado",
                               "cot_prod_cantidad as conteo",
                               "cot_documento as documento",
                               "estado_cot",
                                DB::Raw("DATE_FORMAT(cot_fecha_creacion,'%d-%m-%Y') as fecha_creacion"),
                                DB::Raw("DATE_FORMAT(cot_fecha_modificacion,'%d-%m-%Y') as fecha_modificacion"),
                                DB::Raw("DATE_FORMAT(cot_fecha_cierre,'%d-%m-%Y') as fecha_cierre"))
                            ->where('cot_num_crm', $crm)
                            ->where('cot_encabezado.idusuario', usuario()->id)
                            ->where('cot_encabezado.status', 'AC')
                            ->get()
                            ->toArray();
        }
        // dd($cotizaciones, 'este es mi hello world');
        $informacion = array();
        $informacion['cotizaciones'] = $cotizaciones;
        $informacion['crm'] = $crm;
        return view('cotizaciones.listadoxcrm')->with($informacion);
    }



    public function cotizacion_detalle($idcotizacion) {
        // Muestra detalles de la cotizacion que el usuario solicite
        $cotizacionesRaw = DB::table('cot_encabezado')
                            ->join('users', 'users.id', '=', 'cot_encabezado.idusuario')
                            ->join('conceptos', 'conceptos.idconcepto', '=', 'cot_encabezado.cot_concepto')
                            ->join('cot_clientes', 'cot_clientes.idclientes', '=', 'cot_encabezado.idcliente')
                            ->join('cot_detalle', 'cot_detalle.idcotizacion', '=', 'cot_encabezado.idcotizacion')
                            ->join('cot_productos', 'cot_productos.idproductos', '=', 'cot_detalle.cotdet_id_producto')
                            ->leftJoin('cot_impuestos', 'cot_impuestos.idcotimpuestos', '=', 'cot_detalle.cotdet_iva')
                            ->leftJoin('adicionalxservicio', 'adicionalxservicio.id_cot_detalle', '=', 'cot_detalle.idcot_detalle')
                            ->leftJoin('cot_adicionales', 'cot_adicionales.idcotadicionales', '=', 'adicionalxservicio.idadicional')
                            ->select(
                                'cot_encabezado.idcotizacion as idcotizacion',
                                'cot_encabezado.cot_num_crm as crm',
                                'cot_encabezado.cot_encabezado as encabezado',
                                'cot_encabezado.estado_cot as estado_cot',
                                'conceptos.con_texto as concepto',
                                'cot_clientes.cli_nombre as cli_nombre',
                                'cot_clientes.cli_telefono as cli_telefono',
                                'cot_clientes.cli_correo as cli_correo',
                                'cot_clientes.cli_empresa as cli_empresa',
                                'cot_clientes.cli_puesto as cli_puesto',
                                'cot_detalle.idcot_detalle as idcot_detalle',
                                'cot_detalle.cotdet_moneda as cotdet_moneda',
                                'cot_detalle.cotdet_cantidad as cantidad',
                                'cot_detalle.cotdet_precio_brut as prod_precio',
                                'cot_detalle.cotdet_precio_desperdicio as prod_precio_desperdicio',
                                'cot_detalle.cotdet_precio_adicionales as prod_precio_adicionales',
                                'cot_detalle.cotdet_descripcion as descripcion',
                                'cot_detalle.cotdet_subtotal as total',
                                'cot_productos.prod_nombre as prod_nombre',
                                'cot_productos.prod_medicion as prod_medicion',
                                'cot_detalle.cotdet_tipo_cot as tipo_cot',
                                'cot_impuestos.valor as iva',
                                DB::Raw("DATE_FORMAT(cot_fecha_creacion,'%d-%m-%Y') as fecha_creacion"),
                                DB::Raw("DATE_FORMAT(cot_fecha_modificacion,'%d-%m-%Y') as fecha_modificacion"),
                                DB::Raw("DATE_FORMAT(cot_fecha_cierre,'%d-%m-%Y') as fecha_cierre"),
                                DB::raw('GROUP_CONCAT(cot_adicionales.cotad_nombre SEPARATOR ", ") as nombre_adicional'),
                                DB::raw('GROUP_CONCAT(adicionalxservicio.cantidad SEPARATOR ", ") as cantidad_adicional'),
                                DB::raw('GROUP_CONCAT(adicionalxservicio.precio_bruto SEPARATOR ", ") as precio_adicional'),
                                DB::raw('GROUP_CONCAT(adicionalxservicio.subtotal SEPARATOR ", ") as total_adicional')
                            )
                            ->groupBy(
                                'cot_encabezado.idcotizacion',
                                'cot_encabezado.cot_num_crm',
                                'cot_encabezado.cot_encabezado',
                                'cot_encabezado.estado_cot',
                                'cot_encabezado.cot_fecha_creacion',
                                'cot_encabezado.cot_fecha_modificacion',
                                'cot_encabezado.cot_fecha_cierre',
                                'conceptos.con_texto',
                                'cot_clientes.cli_nombre',
                                'cot_clientes.cli_telefono',
                                'cot_clientes.cli_correo',
                                'cot_clientes.cli_empresa',
                                'cot_clientes.cli_puesto',
                                'cot_detalle.idcot_detalle',
                                'cot_detalle.cotdet_moneda',
                                'cot_detalle.cotdet_cantidad',
                                'cot_detalle.cotdet_precio_brut',
                                'cot_detalle.cotdet_precio_desperdicio',
                                'cot_detalle.cotdet_precio_adicionales',
                                'cot_detalle.cotdet_descripcion',
                                'cot_productos.prod_nombre',
                                'cot_productos.prod_medicion',
                                'cot_detalle.cotdet_tipo_cot',
                                'cot_impuestos.valor',
                                'cot_detalle.cotdet_subtotal',
                            )
                            ->where('cot_encabezado.idcotizacion', $idcotizacion)
                            ->whereRaw("LOWER(cot_encabezado.status) = 'ac'")
                            ->get()
                            ->map(function ($cotizacion) {
                                $adicionales = [];
                                $nombres = explode(', ', $cotizacion->nombre_adicional);
                                $cantidades = explode(', ', $cotizacion->cantidad_adicional);
                                $precios = explode(', ', $cotizacion->precio_adicional);
                                $totales = explode(', ', $cotizacion->total_adicional);

                                foreach ($nombres as $index => $nombre) {
                                    $adicionales[] = [
                                        'adic_nombre' => $nombre,
                                        'adic_cantidad' => $cantidades[$index],
                                        'adic_precio' => $precios[$index],
                                        'adic_total' => $totales[$index],
                                    ];
                                }

                                $cotizacion->adicionales = $adicionales;
                                unset($cotizacion->nombre_adicional, $cotizacion->cantidad_adicional, $cotizacion->precio_adicional, $cotizacion->total_adicional);

                                return $cotizacion;
                            })
                            ->toArray();


        $cotizacionesAgrupadas = [];
        // Transforma los datos obtenidos para la lectura y evitar duplicar campos antes de enviarselo al front
        foreach ($cotizacionesRaw as $item) {
            $cotizacionId = $item->idcotizacion;

            if (!isset($cotizacionesAgrupadas[$cotizacionId])) {
                $cotizacionesAgrupadas[$cotizacionId] = [
                    'idcotizacion' => $item->idcotizacion,
                    'crm' => $item->crm,
                    'encabezado' => $item->encabezado,
                    'concepto' => $item->concepto,
                    'cli_nombre' => $item->cli_nombre,
                    'cli_telefono' => $item->cli_telefono,
                    'cli_correo' => $item->cli_correo,
                    'cli_empresa' => $item->cli_empresa,
                    'cli_puesto' => $item->cli_puesto,
                    'estado_cot' => $item->estado_cot,
                    'fecha_creacion' => $item->fecha_creacion,
                    'fecha_modificacion' => $item->fecha_modificacion,
                    'fecha_cierre' => $item->fecha_cierre,
                    'detalles' => [],
                ];
            }

            $detalle = [
                'idcot_detalle' => $item->idcot_detalle,
                'descripcion' => $item->descripcion,
                'prod_precio' => $item->prod_precio,
                'moneda' => $item->cotdet_moneda,
                // 'cantidad' => count($cotizacionesAgrupadas[$cotizacionId]['detalles']),
                'cantidad' => $item->cantidad,
                'prod_nombre' => $item->prod_nombre,
                'prod_medicion' => $item->prod_medicion,
                'prod_precio_desperdicio' => $item->prod_precio_desperdicio,
                'prod_precio_adicionales' => $item->prod_precio_adicionales,
                'total' => $item->total,
                'tipo_cot' => $item->tipo_cot,
                'iva' => $item->iva,
                'adicionales' => $item->adicionales
            ];

            $cotizacionesAgrupadas[$cotizacionId]['detalles'][] = $detalle;
        }


        $cotizaciones = array_values($cotizacionesAgrupadas);
        $informacion = array();
        $informacion['cotizaciones'] = $cotizaciones;
        $informacion['message'] = 'Ok';
        return response()->json($informacion);
        }



        public function cerrar_cotizacion (Request $r) {
            $context = $r->all();

            $cotizacion = Cot_Encabezado::find($context['id']);
            $cotizacion->cot_fecha_cierre = hoy();
            $cotizacion->estado_cot = 'Finalizado';
            $cotizacion->save();

            $cotizaciones = Cot_Encabezado::where('cot_num_crm', $cotizacion->cot_num_crm)
                                            ->where('idcotizacion', '!=', $context['id'])
                                            ->get();

            foreach($cotizaciones as $item) {
                if($item->cot_fecha_cierre == null && $item->estado_cot == 'En curso') {
                    $item->estado_cot = 'Rechazado';
                    $item->save();
                }
            }

            $parametros = [
                'tabla' => 'cot_encabezado',
                'objeto_modificado' => $cotizacion->idcotizacion,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => 'Update/Actualizar',
            ];
            save_bitacora($parametros);
            return response()->json([
                'message' => 'OK'
            ], 201);
        }

}
