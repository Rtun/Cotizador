<?php

namespace App\Http\Controllers;

use App\Models\Adicionales;
use App\Models\Cot_Encabezado;
use App\Models\Cot_Producto;
use App\Models\Reuniones;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index(){
        return view('Auth.login');
    }

    public function store( ) {
        if (auth()->attempt(request(['email', 'password'])) == false){
            return redirect()->back()->withErrors([
                'message' => 'La contraseña o el correo son incorrectos, porfavor verica los datos :)'
            ]);

        }
        return redirect()->to('/');
    }

    public function destroy() {
        // $usernameDataCrm = session('dataCRM_sessionName');

        // if($usernameDataCrm) {
        //     $client = new Client();

        //     // Paso 1: Obtener el Token
        //     $response = $client->request('POST', 'https://www.datacrm.la/datacrm/comsitec/webservice.php?', [
        //         'form_params' => [
        //             'operation' => 'logout',//peticion
        //             'sessionName' => $usernameDataCrm,//nombre de sesion
        //         ],
        //         'verify' => 'C:\wamp64\www\certificado', //sertificado SSl
        //     ]);

        //     $challenge = json_decode($response->getBody()->getContents(), true);
        //     if($challenge['success']) {
        //         session()->forget('dataCRM_sessionName');
        //     }
        // }
        auth()->logout();
        return redirect()->to('/login');
    }

    public function inicio() {
        $productos = Cot_Producto::where('status', 'AC')->count();
        $adicionales = Adicionales::where('status', 'AC')->count();
        $cotizaciones = Cot_Encabezado::where('status', 'AC')
                                        ->where('idusuario', usuario()->id)->count();
        $reuniones = Reuniones::where('sare_fecha_inicio','>=', hoy())
                                    ->where('sare_status', 'AC')
                                    ->count();

        $fechaLimite = Carbon::now()->subMonth(); //calcula la fecha de hace un mes
        $pendientes = DB::table('cot_encabezado')
                        ->join('cot_clientes', 'cot_clientes.idclientes', '=', 'cot_encabezado.idcliente')
                        ->select(
                            "idcotizacion",
                            "cot_num_crm",
                            "cot_clientes.cli_nombre as cliente",
                            "cot_prod_cantidad",
                            "estado_cot",
                            DB::Raw("DATE_FORMAT(cot_fecha_creacion,'%d-%m-%Y') as fecha_creacion"),
                            DB::Raw("DATE_FORMAT(cot_fecha_modificacion,'%d-%m-%Y') as fecha_modificacion")
                        )
                        ->where(function($query) use ($fechaLimite) {
                            $query->where('cot_fecha_modificacion', '<=', $fechaLimite);
                                // ->orWhere('cot_fecha_modificacion', '<=', $fechaLimite);
                        })
                        ->where('cot_encabezado.idusuario', usuario()->id)
                        ->whereNotIn('estado_cot', ['Finalizado', 'Rechazado']) //verifica que el estado no este en ninguno de los dos
                        ->get()->toArray();

        $informacion = [
            'pendientes' => $pendientes,
            'N_productos' => $productos,
            'N_adicionales' => $adicionales,
            'N_cotizaciones' => $cotizaciones,
            'N_reuniones' =>$reuniones
        ];
        return view('home')->with($informacion);
    }

    public function perfilData() {
        $usuario = DB::table('users')
                    ->join('rol', 'rol.idrol', '=', 'users.idrol')
                    ->select(
                        "id",
                        "name",
                        "email",
                        "password",
                        "telefono",
                        "empresa",
                        "web",
                        "rol.nombre as rol",
                        "users.idrol"
                    )->where('users.id', usuario()->id)->first();

        // Verificar si el usuario es administrador
        $esAdmin = $usuario->rol === 'Administrador';

        // Consultar cotizaciones activas
        $queryCotizaciones = Cot_Encabezado::where('status', 'AC');
        if (!$esAdmin) {
            $queryCotizaciones->where('idusuario', usuario()->id);
        }
        $getcotizaciones = $queryCotizaciones->get();
        $cotizaciones = count($getcotizaciones);

        $fechaLimite = Carbon::now()->subMonth(); //calcula la fecha de hace un mes
        $queryPendientes = DB::table('cot_encabezado')
                            ->join('cot_clientes', 'cot_clientes.idclientes', '=', 'cot_encabezado.idcliente')
                            ->join('users', 'users.id', '=', 'cot_encabezado.idusuario')
                            ->select(
                                "idcotizacion",
                                "cot_num_crm",
                                "users.name as usuario",
                                "cot_clientes.cli_nombre as cliente",
                                "cot_prod_cantidad",
                                "estado_cot",
                                DB::raw("DATE_FORMAT(cot_fecha_creacion, '%d-%m-%Y') as fecha_creacion"),
                                DB::raw("DATE_FORMAT(cot_fecha_creacion, '%H:%i:%s') as hora_creacion"), // Obtener hora
                                DB::raw("DATE_FORMAT(cot_fecha_modificacion, '%d-%m-%Y') as fecha_modificacion"),
                                DB::raw("DATE_FORMAT(cot_fecha_modificacion, '%H:%i:%s') as hora_modificacion") // Obtener hora
                            )
                            ->where(function ($query) use ($fechaLimite) {
                                $query->where('cot_fecha_modificacion', '<=', $fechaLimite);
                            })
                            ->whereNotIn('estado_cot', ['Finalizado', 'Rechazado']);
        if (!$esAdmin) {
            $queryPendientes->where('cot_encabezado.idusuario', usuario()->id);
        }
        $pendientes = $queryPendientes->get()->toArray();

        $queryActividad = DB::table('cot_encabezado')
        ->join('cot_clientes', 'cot_clientes.idclientes', '=', 'cot_encabezado.idcliente')
        ->join('users', 'users.id', '=', 'cot_encabezado.idusuario')
        ->select(
            "idcotizacion",
            "cot_num_crm",
            "users.name as usuario",
            "cot_clientes.cli_nombre as cliente",
            "cot_encabezado as encabezado",
            "cot_prod_cantidad",
            "estado_cot",
            DB::raw("DATE_FORMAT(cot_fecha_creacion, '%d-%m-%Y') as fecha_creacion"),
            DB::raw("DATE_FORMAT(cot_fecha_creacion, '%H:%i:%s') as hora_creacion"), // Obtener hora
            DB::raw("DATE_FORMAT(cot_fecha_modificacion, '%d-%m-%Y') as fecha_modificacion"),
            DB::raw("DATE_FORMAT(cot_fecha_modificacion, '%H:%i:%s') as hora_modificacion") // Obtener hora
        );

        if (!$esAdmin) {
            $queryActividad->where('cot_encabezado.idusuario', usuario()->id);
        }
        $actividad = $queryActividad->orderBy('cot_fecha_modificacion','desc')->get()->toArray();

        $queryGanandas = Cot_Encabezado::where('cot_fecha_cierre', '!=', null);
        if (!$esAdmin) {
            $queryGanandas->where('cot_encabezado.idusuario', usuario()->id);
        }
        $ganadas = $queryGanandas->count();
        $informacion = [
            'usuario' => $usuario,
            'cotizaciones' => $cotizaciones,
            'ganadas' => $ganadas,
            'pendientes' => $pendientes,
            'actividad' => $actividad
        ];

        return view('perfil')->with($informacion);
    }
}
