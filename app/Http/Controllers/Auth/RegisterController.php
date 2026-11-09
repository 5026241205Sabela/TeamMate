<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Validate the registration data.
     *
     * Account persistence and KTM storage are intentionally not implemented yet
     * until the team agrees on the database structure.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        return to_route('login')
            ->with('success', 'Registration successful! Please log in to continue.');
    }
}
