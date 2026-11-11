<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class EmailVerificationController extends Controller
{
    /**
     * Show the verification status page (pending admin review, rejected, or awaiting email click).
     */
    public function notice(Request $request): View|RedirectResponse
    {
        $account = $request->user();

        if ($account->isAdmin()) {
            return to_route('admin.verifications.index');
        }

        if ($account->isApprovedByAdmin() && $account->hasVerifiedEmail()) {
            return to_route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Mark the account email as verified from the signed link and log the user into the homepage.
     */
    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        if (! $request->hasValidSignature() && ! $request->hasValidSignature(false)) {
            abort(403, 'Link verifikasi tidak valid atau sudah kedaluwarsa.');
        }

        $account = Account::findOrFail($id);

        if (! hash_equals(sha1($account->getEmailForVerification()), $hash)) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        if (! $account->isApprovedByAdmin()) {
            abort(403, 'Akun kamu belum disetujui oleh Admin.');
        }

        if (! $account->hasVerifiedEmail()) {
            $account->markEmailAsVerified();
        }

        if (Auth::id() !== $account->getKey()) {
            Auth::login($account);
            $request->session()->regenerate();
        }

        return to_route('home')->with('success', 'Email berhasil diverifikasi. Selamat datang di TeamMate!');
    }

    /**
     * Send a fresh verification link once the account has been approved by Admin.
     */
    public function send(Request $request): RedirectResponse
    {
        $account = $request->user();

        if (! $account->isApprovedByAdmin()) {
            return back()->with('info', 'Link verifikasi hanya dapat dikirim setelah Admin menyetujui data diri dan KTM kamu.');
        }

        if ($account->hasVerifiedEmail()) {
            return to_route('home');
        }

        if (config('mail.default') === 'log') {
            return back()->with('info', 'Email tidak dapat dikirim karena MAIL_MAILER di file .env server ini masih "'.config('mail.default').'". Ubah ke MAIL_MAILER=smtp lalu isi MAIL_USERNAME dan MAIL_PASSWORD (Gmail App Password).');
        }

        try {
            $account->sendEmailVerificationNotification();
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('info', 'Link verifikasi gagal dikirim: '.$exception->getMessage());
        }

        return back()->with('success', 'Link verifikasi baru sudah dikirim. Periksa inbox atau folder spam kamu.');
    }
}
