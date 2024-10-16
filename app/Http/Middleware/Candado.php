<?php

namespace App\Http\Middleware;

use App\Models\Permiso;
use App\Models\RolxPermiso;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Candado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permiso)
    {
        // Obtener el idrol del usuario
        $idrol = Auth()->user()->idrol;

        // Si el idrol es 1 (administrador), dar acceso completo
        if ($idrol == 1) {
            return $next($request);
        }

        // Verificar si los permisos ya están almacenados en la sesión
        if (!session()->has('permisosUsuario')) {
            // Si no están, cargar los permisos desde la base de datos y guardarlos en la sesión
            $permisosUsuario = RolxPermiso::where('idrol', $idrol)->pluck('idpermiso')->toArray();
            session()->put('permisosUsuario', $permisosUsuario);
        } else {
            // Si ya están en la sesión, obtén los permisos desde la sesión
            $permisosUsuario = session()->get('permisosUsuario');
        }

        // Obtener el permiso que se va a validar
        $permiso_db = Permiso::where('clave', $permiso)->first();

        if (!$permiso_db) {
            // Si el permiso no existe, retornar un error 403
            return abort(403, 'Permiso no encontrado');
        }

        // Verificar si el permiso está en los permisos almacenados en la sesión
        if (in_array($permiso_db->idpermiso, $permisosUsuario)) {
            // Si el permiso está en la lista, continuar con la solicitud
            return $next($request);
        }

        // Si no tiene permiso, retornar una vista personalizada de 403
        $mensaje = 'Lo sentimos, No tienes Permisos Para Acceder al modulo '.$permiso;
        $estatus = '403 Sin Permiso';
        return response()->view('403Permisos', compact('mensaje', 'estatus'));
    }

}
