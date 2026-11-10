<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Account;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

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
     * Create the account, store the KTM privately, send the verification email and enter the homepage.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $ktmPath = $request->file('ktm')->store('ktm');

        try {
            $account = Account::create([
                'full_name' => $request->string('name')->trim()->toString(),
                'email' => $request->string('email')->toString(),
                'phone_number' => $request->string('phone')->toString(),
                'university' => $request->string('university')->trim()->toString(),
                'nim' => $request->string('student_id')->toString(),
                'ktm' => $ktmPath,
                'password' => $request->string('password')->toString(),
            ]);
        } catch (Throwable $exception) {
            Storage::delete($ktmPath);

            throw $exception;
        }

        Auth::login($account);
        $request->session()->regenerate();

        try {
            event(new Registered($account));
        } catch (Throwable $exception) {
            report($exception);

            return to_route('home')
                ->with('info', 'Akun berhasil dibuat, tetapi email verifikasi belum terkirim. Kamu bisa mengirim ulang link verifikasi dari banner di atas.');
        }

        return to_route('home')
            ->with('success', 'Registrasi berhasil! Kami telah mengirim link verifikasi ke email kamu.');
    }
}
