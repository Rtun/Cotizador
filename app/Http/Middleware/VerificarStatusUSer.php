<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarStatusUSer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->status == 'BJ') {
            $mensaje = 'Lo sentimos, tu usuario fue dado de baja. Por favor, contacta al administrador.';
            $estatus = '403Baja';
            return response()->view('403Permisos', compact('mensaje', 'estatus'));
        }
        return $next($request);
    }
}
