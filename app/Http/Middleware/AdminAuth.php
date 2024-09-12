<?php

namespace App\Http\Middleware;

use App\Models\Rol;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check()) {
          $rol = auth()->user()->idrol;
          $find_rol = Rol::find($rol);
          if($find_rol->nombre === 'Administrador'){
            return $next($request);
          }
          else{
            $mensaje = 'Esta Ruta Es Solo Para Administradores.';
            $estatus = '403 Sin Permisos';
            return response()->view('403Permisos', compact('mensaje', 'estatus'));
          }
        }
    }
}
