<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class RegisterController extends Controller
{
    public function index() {
        return view('Auth.register');
    }

    public function store( Request $r) {
        $context = $r->all();
        // $validatedData = $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email',
        //     'password'=>'required',
        // ]);
        // $user = User::create(request(['name', 'email', 'password', 'telefono']));

        $usuario = new User();
        $usuario->name = $context['name'];
        $usuario->email = $context['email'];
        $usuario->password = $context['password'];
        $usuario->telefono = $context['telefono'];
        $usuario->idrol = 4;
        $usuario->save();

        $parametros = [
            'tabla' => 'users',
            'objeto_modificado' => $usuario->id,
            'idusuario' => $usuario->id,
            'fecha' => hoy(),
            'accion' => 'Agregar/Registrar',
        ];
        save_bitacora($parametros);
        return redirect()->to('/login');
    }
}
