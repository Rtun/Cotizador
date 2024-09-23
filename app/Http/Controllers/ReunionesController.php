<?php

namespace App\Http\Controllers;

use App\Models\Reuniones;
use App\Models\Salas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReunionesController extends Controller
{
    public function listado_salas () {
        $salas = Salas::all();

        $informacion = [
            'sala' => $salas,
        ];

        return view('listados.listado_salas')->with($informacion);
    }


    public function show_form_salas (Request $r) {
        $context = $r->all();

        if($r->isMethod('post')|| !$r->has('idsala')) {
            $sala = new Salas();
            $operacion = 'Agregar';
        }
        else {
            $sala = Salas::find($context['idsala']);
            $operacion = 'Editar';

        }
        $informacion = [
            'sala' => $sala,
            'operacion' => $operacion
        ];
        return view('catalogos.form_salas')->with($informacion);
    }


    public function save_salas(Request $r) {
        $context = $r->all();
        $status = '';

        if($context['operacion'] == 'Agregar' || $context['operacion'] == 'Editar') {
            $validatedData = $r->validate([
                    'nombre_sala' => 'required'
                ], [
                    'nombre_sala.required' => 'El nombre no puede estar vacío'
                ]);
        }

        switch ($context['operacion']) {
            case 'Agregar':
                $validacion = Salas::where('sa_nombre', $context['nombre_sala'])->first();
                if ($validacion) {
                    return redirect()->back()->withErrors([
                        'nombre_sala' => 'Ya hay una sala con este nombre ' .'"'. $context['nombre_sala'] .'"'. ', por lo que no se puede registrar de nuevo.'
                    ])->withInput();
                }

                $sala = new Salas();
                $sala->sa_nombre = $context['nombre_sala'];
                $sala->save();
                $status = 'OK';
                break;
            case 'Editar':
                $sala = Salas::find($context['idsala']);
                $sala->sa_nombre = $context['nombre_sala'];
                $sala->save();
                $status = 'OK';
                break;
            case 'Eliminar':
                $sala = Salas::find($context['idsala']);
                $sala->sa_status = 'BJ';
                $sala->save();
                $status = 'OK';
                break;
        }

        if($status == 'OK') {
            $parametros = [
                'tabla' => 'salas',
                'objeto_modificado' => $sala->idsala,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }

        return redirect()->to('/catalogos/listado/salas');
    }

    public function show_calendario () {
        $salas = Salas::where('sa_status', 'AC')->get()->toArray();
        $usuario = Auth::user();

        $informacion = [
            'sala' => $salas,
            'usuario' => $usuario
        ];
        return view('reuniones.calendario')->with($informacion);
    }

    public function get_evento() {
        $eventos = Reuniones::all();

        foreach ($eventos as $even) {
            if($even->sare_status != 'CE') {
                if($even->sare_fecha_final < hoy() && $even->sare_status != 'BJ' ) {
                    $even->sare_status = 'CE';
                    $even->save();
                }
            }
        }

        $reuniones = DB::table('sa_reunion')
                        ->join('users', 'users.id', '=', 'sa_reunion.idusuario')
                        ->join('salas', 'salas.idsala', '=', 'sa_reunion.idsala')
                        ->select(
                            "idreunion",
                            "salas.sa_nombre as sala",
                            "users.name as usuario",
                            "sa_reunion.idusuario as idusuario",
                            "sare_tema",
                            "sare_descripcion",
                            DB::raw("DATE_FORMAT(sare_fecha_inicio, '%Y-%m-%dT%H:%i:%s') as sare_fecha_inicio"),
                            DB::raw("DATE_FORMAT(sare_fecha_fin, '%Y-%m-%dT%H:%i:%s') as sare_fecha_fin"),
                            "sare_color_fondo",
                            "sare_color_borde",
                            "sare_color_texto"
                        )->where('sare_status','!=', 'BJ')->get()->toArray();

        return response()->json([
            'eventos' => $reuniones
        ]);
    }


    function valida_horario($objeto){
        $bandera=true;
        $servicios=Reuniones::where('idsala',$objeto['idsala'])
                            ->where('sare_status', 'AC')
                            ->whereRaw("sare_fecha_inicio<'".$objeto['end']."'and sare_fecha_fin>'".$objeto['start']."'")
                            ->get();

        if(count($servicios) != 0){
            $bandera=false;
        }
        return $bandera;
    }

    public function save_reunion(Request $r) {
        $context = $r->all();
        $status = '';
        $alert = '';
        $mesagge = '';
        $bandera = $this->valida_horario($context);
        $hoy = explode(" ", hoy());
        $start = explode("T", $context['start']);
        $end = explode("T", $context['end']);
        if($bandera) {
            if($start[0] < $hoy[0]) {
                $status = 'Error';
                $alert = 'error';
                $mesagge = 'Hubo un problema, Verifica que la fecha que quieres agendar sea no sea anterior a la fecha y hora de hoy o que la fecha final sea mayor a la de inicio';
            }
            else {
                $reuniones = new Reuniones();
                $reuniones->idsala = $context['idsala'];
                $reuniones->idusuario = usuario()->id;
                $reuniones->sare_tema = $context['title'];
                $reuniones->sare_descripcion = $context['description'];
                $reuniones->sare_fecha_inicio = $context['start'];
                $reuniones->sare_fecha_fin = $context['end'];
                $reuniones->sare_color_fondo = $context['backgroundColor'];
                $reuniones->sare_color_borde = $context['borderColor'];
                $reuniones->sare_color_texto = $context['textColor'];
                $reuniones->save();
                $status = 'OK';
                $alert = 'success';
                $mesagge = 'Sala de reunion Agendada correctamente';
            }
        }
        else {
            $status = 'Error';
            $alert = 'error';
            $mesagge = 'Hubo un problema, hay una reunion agendada para esa fecha o hora';
        }

        if($status == 'OK'){
            $parametros = [
                'tabla' => 'sa_reunion',
                'objeto_modificado' => $reuniones->idreunion,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => 'Guardar/Create',
            ];
            save_bitacora($parametros);
        }

        return response()->json([
            'status' => $status,
            'alert' => $alert,
            'mesagge' => $mesagge
        ]);
    }

    public function delete_event(Request $r) {
        $context = $r->all();

        $reunion = Reuniones::find($context['idevento']);
        $reunion->sare_status = 'BJ';
        $reunion->save();

        $parametros = [
            'tabla' => 'sa_reunion',
            'objeto_modificado' => $reunion->idreunion,
            'idusuario' => usuario()->id,
            'fecha' => hoy(),
            'accion' => 'Eliminar/Delete',
        ];
        save_bitacora($parametros);

        return response()->json([
            'mesagge' => 'La reunion se a elimnado con exito',
            'estatus' => 'OK'
        ]);
    }
}
