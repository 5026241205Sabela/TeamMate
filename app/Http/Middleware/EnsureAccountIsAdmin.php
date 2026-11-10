<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsAdmin
{
    /**
     * Allow only accounts with role = 'admin'.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Halaman ini hanya dapat diakses oleh Admin TeamMate.');
        }

        return $next($request);
    }
}
