<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Account;
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
     * Create the student account in "pending" status for Admin KTM review.
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
                'role' => 'student',
                'verification_status' => 'pending',
                'email_verified_at' => null,
            ]);
        } catch (Throwable $exception) {
            Storage::delete($ktmPath);

            throw $exception;
        }

        Auth::login($account);
        $request->session()->regenerate();

        return to_route('verification.notice')
            ->with(
                'success',
                'Registrasi berhasil dikirim! Data diri dan foto KTM kamu sedang diperiksa oleh Admin. Setelah disetujui, link verifikasi akan dikirim ke email kamu.'
            );
    }
}
