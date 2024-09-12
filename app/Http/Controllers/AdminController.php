<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index() {
        dd('Este es un administrador');
    }

    public function show_usuarios () {
        $usuarios = DB::table('users')
                        ->join('rol', 'rol.idrol', '=', 'users.idrol')
                        ->select(
                            "users.id as idusuario",
                            "users.name as N_usuario",
                            "users.telefono as telefono",
                            "users.email as email",
                            "users.empresa as empresa",
                            "rol.nombre as rol",
                            "users.status as status"
                         )->get();

        $informacion = [
            'usuarios' => $usuarios
        ];

        return view('admin.listado_usuarios')->with($informacion);
    }

    public function consultar_usuario($idusuario) {
        $usuario = User::find($idusuario);
        $roles = Rol::all();
        return response()->json([
            'usuario' => $usuario,
            'roles' => $roles,
        ],202);
    }


    public function update_usuario(Request $r) {
        $context = $r->all();
        $usuario = User::find($context['idusuario']);
        $mensaje = '';
        switch ($context['operacion']) {
            case 'Editar':
                $usuario->name = $context['nombre'];
                $usuario->email = $context['email'];
                $usuario->telefono = $context['telefono'];
                $usuario->empresa = $context['empresa'];
                if(isset($context['password'])) {
                    $usuario->password = $context['password'];
                }
                $usuario->updated_at = hoy();
                $usuario->idrol = $context['rol'];
                $usuario->save();
                $mensaje = 'Los datos del usuario '. $context['nombre']. ' fueron actualizados correctamente';
                break;

            case 'Eliminar':
                $usuario->status = 'BJ';
                $usuario->save();
                $mensaje = 'El usuario '.$usuario->name. ' fue dado de baja del sistema';
                break;
            case 'Reactivar':
                $usuario->status = 'AC';
                $usuario->save();
                $mensaje = 'El usuario '.$usuario->name. ' Fue reactivado con exito';
                break;
        }

        $parametros = [
            'tabla' => 'users',
            'objeto_modificado' => $usuario->id,
            'idusuario' => usuario()->id,
            'fecha' => hoy(),
            'accion' => $context['operacion'],
        ];
        save_bitacora($parametros);

        return response()->json([
            'mesagge' => $mensaje,
        ]);
    }
}
