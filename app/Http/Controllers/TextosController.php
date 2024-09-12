<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use Illuminate\Http\Request;

class TextosController extends Controller
{
    public function listado () {
        $conceptos = Concepto::where('con_status', 'AC')->get()->toArray();


        if( count($conceptos) == 0 ) {

            $conceptos = 'Error';
        }

        $informacion = array();
        $informacion['conceptos'] = $conceptos;

        return view('listados.listado_textos')->with($informacion);
    }


    public function showForm (Request $r){
        $context = $r->all();

        if($r->isMethod('post') || !$r->has('idconcepto')) {
            $conceptos = new Concepto();
            $operacion = 'Agregar';
        }
        else {
            $conceptos = Concepto::find($context['idconcepto']);
            $operacion = 'Editar';
        }

        $informacion = array();
        $informacion['operacion'] = $operacion;
        $informacion['conceptos'] = $conceptos;

        return view('catalogos.form_conceptos')->with($informacion);
    }

    public function saveForm(Request $r) {
        $context = $r->all();
        $status = '';
        // Reglas de validación
       if($context['operacion'] == 'Agregar' || $context['operacion'] == 'Editar') {
            $rules = [
                'con_clave' => 'required|string|max:20',
                'con_texto' => 'required|string|max:150',
            ];
            // Mensajes personalizados (opcional)
            $messages = [
                'con_clave.required' => 'La palabra clave es obligatoria.',
                'con_texto.required' => 'El texto es obligatorio.',
            ];
            // Validación
            $validatedData = $r->validate($rules, $messages);
        }

        switch ($context['operacion']) {
            case 'Agregar':
                $validacion = Concepto::where('con_clave', $context['con_clave'])->first();
                if ($validacion) {
                    return redirect()->back()->withErrors([
                        'message' => 'La palabra clave ' .'"'. $context['con_clave'] .'"'. ', ya ha sido definida, por favor coloca una diferente.'
                    ])->withInput();
                }
                $conceptos = new Concepto();
                $conceptos->con_clave = $context['con_clave'];
                $conceptos->con_texto = $context['con_texto'];
                $conceptos->save();
                $status = 'OK';
                break;
            case 'Editar':
                $conceptos = Concepto::find($context['idconcepto']);
                $conceptos->con_clave = $context['con_clave'];
                $conceptos->con_texto = $context['con_texto'];
                $conceptos->save();
                $status = 'OK';
                break;
            case 'Eliminar':
                $conceptos = Concepto::where('idconcepto',$context['idconcepto'])
                ->update(['con_status' => 'BJ']);
                $status = 'OK';
                break;
        }

        if($status == 'OK') {
            $parametros = [
                'tabla' => 'conceptos',
                'objeto_modificado' => $conceptos->idconcepto,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }

        return redirect()->to('/catalogos/listado/conceptos');
    }
}
