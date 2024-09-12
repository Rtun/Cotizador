<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
{
    public function show_roles() {
        $roles = Rol::all();

        $informacion = [
            'roles' => $roles
        ];

        return view('admin.listado_roles')->with($informacion);
    }

    public function filter_user($idrol) {
        $usuarios = DB::table('users')
                        ->join('rol', 'rol.idrol', '=', 'users.idrol')
                        ->select(
                            "users.name as usuario",
                            "rol.nombre as rol",
                            "users.status as status"
                        )
                        ->where('users.idrol', $idrol)->get();

        $rol = Rol::find($idrol);

        return response()->json([
            'usuarios' => $usuarios,
            'rol' => $rol->nombre
        ]);
    }

    public function show_form() {

        $informacion = [
            'operacion' => 'Agregar'
        ];
        return view('admin.form_roles')->with($informacion);
    }

    public function save_form(Request $r) {
        $context = $r->all();
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

            $rol = new Rol();
            $rol->nombre = $context['nombre'];
            $rol->save();

            $parametros = [
                'tabla' => 'rol',
                'objeto_modificado' => $rol->idrol,
                'idusuario' => usuario()->id,
                'fecha' => hoy(),
                'accion' => $context['operacion'],
            ];
            save_bitacora($parametros);
        }

        return redirect()->to('/admin/listado/roles');
    }
}
