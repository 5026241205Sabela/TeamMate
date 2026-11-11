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
        $email = $request->string('email')->toString();
        $nim = $request->string('student_id')->toString();

        try {
            $rejectedAccounts = Account::query()
                ->where('verification_status', 'rejected')
                ->where(function ($query) use ($email, $nim) {
                    $query->where('email', $email)
                        ->orWhere('nim', $nim);
                })
                ->orderBy('account_id')
                ->get();

            $attributes = [
                'full_name' => $request->string('name')->trim()->toString(),
                'email' => $email,
                'phone_number' => $request->string('phone')->toString(),
                'university' => $request->string('university')->trim()->toString(),
                'nim' => $nim,
                'ktm' => $ktmPath,
                'password' => $request->string('password')->toString(),
                'role' => 'student',
                'verification_status' => 'pending',
                'rejection_reason' => null,
                'email_verified_at' => null,
            ];

            if ($rejectedAccounts->isNotEmpty()) {
                foreach ($rejectedAccounts as $oldAccount) {
                    if ($oldAccount->ktm && str_starts_with($oldAccount->ktm, 'ktm/')) {
                        Storage::delete($oldAccount->ktm);
                    }
                }

                /** @var Account $account */
                $account = $rejectedAccounts->shift();

                foreach ($rejectedAccounts as $extraRejected) {
                    $extraRejected->delete();
                }

                $account->fill($attributes);
                $account->save();
            } else {
                $account = Account::create($attributes);
            }
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
