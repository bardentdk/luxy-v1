<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommercialMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Vous devez être connecté pour accéder à l\'espace commercial.');
        }

        $user = auth()->user();

        // Admin et super-admin ont aussi accès à l'espace commercial
        if (
            $user->hasRole('commercial') ||
            $user->hasRole('admin') ||
            $user->hasRole('super-admin')
        ) {
            return $next($request);
        }

        abort(403, 'Accès réservé à l\'équipe commerciale.');
    }
}