<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function show_proveedores() {
        $proveedor = Proveedor::join('users', 'users.id', '=', 'prod_proveedor.idusuario')
                                ->select(
                                    "idproveedor",
                                    "prv_nombre",
                                    "users.name as usuario",
                                    DB::Raw("DATE_FORMAT(prv_fecha_creacion,'%d-%m-%Y') as fecha_creacion")
                                )
                                ->where('prv_status', 'AC')
                                ->get()->toArray();
        $informacion = [
            'proveedores' => $proveedor
        ];

        return view('listados.listado_proveedores')->with($informacion);
    }

    public function show_form(Request $r) {
        $context = $r->all();

        if($r->isMethod('post') || !$r->has('idproveedor')) {
            $proveedor = new Proveedor();
            $operacion = 'Agregar';
       }
       else {
           $proveedor = Proveedor::find($context['idproveedor']);
           $operacion = 'Editar';
       }

       $informacion = [
           'proveedor' => $proveedor,
           'operacion' => $operacion
       ];

       return view('catalogos.form_proveedores')->with($informacion);
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
                $proveedor = new Proveedor();
                $proveedor->prv_nombre = $context['nombre'];
                $proveedor->idusuario = usuario()->id;
                $proveedor->prv_fecha_creacion = hoy();
                $proveedor->save();
                $mensaje = 'OK';
                break;
            case 'Editar':
                $proveedor = Proveedor::find($context['idproveedor']);
                $proveedor->prv_nombre = $context['nombre'];
                $proveedor->idusuario = usuario()->id;
                $proveedor->prv_fecha_creacion = hoy();
                $proveedor->save();
                $mensaje = 'OK';
                break;
            case 'Eliminar':
                $proveedor = Proveedor::find($context['idproveedor']);
                $proveedor->prv_status = 'BJ';
                $proveedor->save();
                $mensaje = 'OK';
                break;
        }

        if ($mensaje == 'OK') {
            $parametros = [
                'tabla' => 'prod_proveedor',
                'objeto_modificado' => $proveedor->idproveedor,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }
        return redirect()->to('/catalogos/listado/proveedores');
    }
}
