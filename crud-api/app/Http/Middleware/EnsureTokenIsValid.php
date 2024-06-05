<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Esta clase es un middleware que se encarga de comprobar si el token de la solicitud es válido.
 *
 * Actualmente este middleware no hace nada, simplemente pasa la solicitud al siguiente middleware o controlador.
 */
class EnsureTokenIsValid
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pasamos la solicitud al siguiente middleware o controlador
        return $next($request);
    }
}

