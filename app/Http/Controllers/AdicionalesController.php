<?php

namespace App\Http\Controllers;

use App\Models\Adicionales;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdicionalesController extends Controller
{
    public function listado() {
        $adicionales = Adicionales::where('status', 'AC')->get()->toArray();

        $informacion = array();
        $informacion['adicionales'] = $adicionales;

        return view('listados.listado_adicionales')->with($informacion);
    }

    public function form_adicionales(Request $r) {
        $context = $r->all();

        if($r->isMethod('post')){
            $operacion = 'Agregar';
            $adicionales = new Adicionales();
        }
        else {
            $operacion = 'Editar';
            $adicionales = Adicionales::find($context['idadicional']);
        }

        $informacion = array();
        $informacion['operacion'] = $operacion;
        $informacion['adicionales'] = $adicionales;

        return view('catalogos.form_adicionales')->with($informacion);
    }

    public function save_adicionales(Request $r) {
        $context = $r->all();
        $fecha = hoy();
        $status = '';

        switch ( $context['operacion'] ) {
            case 'Agregar';
            $adicional = new Adicionales();
            $adicional->cotad_nombre = $context['ad_nombre'];
            $adicional->cotad_precio = $context['ad_precio'];
            $adicional->fecha_creacion = $fecha;
            $adicional->fecha_modificacion = $fecha;
            $adicional->save();
            $status = 'OK';
                break;
            case 'Editar';
            $adicional = Adicionales::find($context['idadicional']);
            $adicional->cotad_nombre = $context['ad_nombre'];
            $adicional->cotad_precio = $context['ad_precio'];
            $adicional->fecha_modificacion = $fecha;
            $adicional->save();
            $status = 'OK';
                break;
            case 'Eliminar';
            $adicional = Adicionales::find($context['idadicional']);
            $adicional->status = 'BJ';
            $adicional->save();
            $status = 'OK';
                break;
        }

        if($status == 'OK') {
            $parametros = [
                'tabla' => 'cot_adicionales',
                'objeto_modificado' => $adicional->idcotadicionales,
                'idusuario' => usuario()->id,
                'fecha' => $fecha,
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }
        return redirect()->to('/catalogos/listado/adicionales');
    }
}
