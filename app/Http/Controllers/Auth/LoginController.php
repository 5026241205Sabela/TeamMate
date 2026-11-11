<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
     * Authenticate the account against the accounts table.
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = [
            'email' => mb_strtolower(trim($request->string('email')->toString())),
            'password' => $request->string('password')->toString(),
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah. Silakan coba lagi.',
            ]);
        }

        $request->session()->regenerate();

        /** @var Account $account */
        $account = Auth::user();

        if ($account->isAdmin()) {
            return redirect()->intended(route('admin.verifications.index'));
        }

        if (! $account->isApprovedByAdmin() || ! $account->hasVerifiedEmail()) {
            return to_route('verification.notice');
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Log the account out and invalidate the session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('landing');
    }

    /**
     * Show the forgot password page.
     */
    public function forgotPassword(): View
    {
        return view('auth.forgot-password');
    }
}
