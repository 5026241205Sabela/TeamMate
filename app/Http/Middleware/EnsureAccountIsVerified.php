<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsVerified
{
    /**
     * Allow only logged-in accounts that were approved by Admin AND clicked the email verification link.
     * Admins are exempt. Everyone else is sent to the verification status page.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->user();

        if (! $account) {
            return redirect()->route('login');
        }

        if ($account->isAdmin()) {
            return $next($request);
        }

        if (! $account->isApprovedByAdmin() || ! $account->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
