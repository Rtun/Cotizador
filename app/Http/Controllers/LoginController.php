<?php

namespace App\Http\Controllers;

use App\Models\Adicionales;
use App\Models\Cot_Encabezado;
use App\Models\Cot_Producto;
use App\Models\Reuniones;
use Carbon\Carbon;
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
        // return view('home', ['user' => $user]);
        return redirect()->to('/');
    }

    public function destroy() {
        auth()->logout();
        return redirect()->to('/login');
    }

    public function inicio() {
        $getproductos = Cot_Producto::where('status', 'AC')->get();
        $getadicionales = Adicionales::where('status', 'AC')->get();
        $getcotizaciones = Cot_Encabezado::where('status', 'AC')
                                        ->where('idusuario', usuario()->id)->get();
        $getreuniones = Reuniones::where('sare_fecha_inicio','>=', hoy())
                                    ->get();

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

        $productos = count($getproductos);
        $adicionales = count($getadicionales);
        $cotizaciones = count($getcotizaciones);
        $reuniones = count($getreuniones);
        $informacion = [
            'pendientes' => $pendientes,
            'N_productos' => $productos,
            'N_adicionales' => $adicionales,
            'N_cotizaciones' => $cotizaciones,
            'N_reuniones' =>$reuniones
        ];
        return view('home')->with($informacion);
    }
}
