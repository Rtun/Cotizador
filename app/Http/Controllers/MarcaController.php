<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcaController extends Controller
{
    public function show_marcas() {
        $marcas = Marca::where('m_status', 'AC')->get()->toArray();

        $informacion = [
            'marcas' => $marcas
        ];

        return view('listados.listado_marcas')->with($informacion);
    }

    public function show_form(Request $r) {
        $context = $r->all();

        if($r->isMethod('post') || !$r->has('idmarca')) {
             $marca = new Marca();
             $operacion = 'Agregar';
        }
        else {
            $marca = Marca::find($context['idmarca']);
            $operacion = 'Editar';
        }

        $informacion = [
            'marca' => $marca,
            'operacion' => $operacion
        ];

        return view('catalogos.form_marcas')->with($informacion);
    }

    public function save_form(Request $r) {
        $context = $r->all();
        $mensaje = '';

        if($context['operacion'] == 'Agregar' || $context['operacion'] == 'Editar') {
            $rules = [
                'nombre' => 'required|string|max:50',
            ];
            // Mensajes personalizados (opcional)
            $messages = [
                'nombre.required' => 'No se puede registrar un nombre vacio',
            ];
            // Validación
            $validatedData = $r->validate($rules, $messages);
        }

        switch ($context['operacion']) {
            case 'Agregar':
                $marca = new Marca();
                $marca->m_nombre = $context['nombre'];
                $marca->save();
                $mensaje = 'OK';
                break;
            case 'Editar':
                $marca = Marca::find($context['idmarca']);
                $marca->m_nombre = $context['nombre'];
                $marca->save();
                $mensaje = 'OK';
                break;
            case 'Eliminar':
                $marca = Marca::find($context['idmarca']);
                $marca->m_status = 'BJ';
                $marca->save();
                $mensaje = 'OK';
                break;
        }

        if ($mensaje == 'OK') {
            $parametros = [
                'tabla' => 'prod_marca',
                'objeto_modificado' => $marca->idmarca,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }

        return redirect()->to('/catalogos/listado/marcas');
    }
}
