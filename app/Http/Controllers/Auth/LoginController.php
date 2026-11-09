<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Validate the login credentials.
     *
     * Full authentication is intentionally not implemented yet until the team
     * agrees on the database structure.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        return back()
            ->withInput($request->only('email', 'remember'))
            ->with('info', 'Login is not available yet. Authentication will be enabled once the account system is ready.');
    }

    /**
     * Show the forgot password page.
     */
    public function forgotPassword(): View
    {
        return view('auth.forgot-password');
    }
}
