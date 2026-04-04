<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!Auth::check() || !$user->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        return $next($request);
    }
}
