<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
//        // Opción A: Si usas Spatie
//        if (!$request->user() || !$request->user()->hasAnyRole($roles)) {
//            abort(403);
//        }

//         Opción B: Si usas campo 'tipo_usuario'
         if (!in_array($request->user()->tipo_usuario, $roles)) {
             abort(403);
         }

        return $next($request);
    }
}


