<?php

use App\Models\Bitacora;
use App\Models\Permiso;
use App\Models\RolxPermiso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


if (!function_exists('save_bitacora')) {
    function save_bitacora($parametros) {
        DB::table('sist_bitacora')->insert([
            'tabla_modificada' => json_encode($parametros['tabla']),
            'objeto_modificado' => $parametros['objeto_modificado'],
            'idusuario' => $parametros['idusuario'],
            'fecha' => $parametros['fecha'],
            'accion' => $parametros['accion'],
        ]);
    }
}

if (!function_exists('usuario')) {
    function usuario(){
        return Auth::user();
    }
}

if(!function_exists('hoy')) {
    function hoy($formato='Y-m-d H:i:s'){
        date_default_timezone_set("America/Merida");
        return date($formato);
    }
}

if(!function_exists('validacion_rol')) {
    function validacion_rol($idrol, $permiso) {
        if($idrol == 1){
            return true;
        }
        // Verifica si ya existen los permisos en la sesión
        if (!session()->has('permisosUsuario')) {
            // Si no están, carga los permisos desde la base de datos
            $permisosUsuario = RolxPermiso::where('idrol', $idrol)->pluck('idpermiso')->toArray();
            // Almacena los permisos en la sesión
            session()->put('permisosUsuario', $permisosUsuario);
        } else {
            // Si ya existen en la sesión, los obtenemos directamente
            $permisosUsuario = session()->get('permisosUsuario');
        }
        // dd($permisosUsuario);
        // Obtener el permiso que se va a validar
        $permiso_db = Permiso::where('clave', $permiso)->first();

        // Validar si el permiso existe
        if (!$permiso_db) {
            return false;  // Si el permiso no existe, devolver false
        }

        // Verificar si el permiso está en los permisos del usuario
        return in_array($permiso_db->idpermiso, $permisosUsuario);
    }
}

