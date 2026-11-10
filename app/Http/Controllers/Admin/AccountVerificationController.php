<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Notifications\AccountRejectedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class AccountVerificationController extends Controller
{
    /**
     * Display the Admin Student Account & KTM Verification portal.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->string('status', 'all')->toString();
        $keyword = trim($request->string('q')->toString());

        $studentsQuery = Account::query()
            ->where('role', '!=', 'admin');

        $pendingCount = (clone $studentsQuery)->where('verification_status', 'pending')->count();
        $approvedCount = (clone $studentsQuery)->where('verification_status', 'approved')->count();
        $rejectedCount = (clone $studentsQuery)->where('verification_status', 'rejected')->count();
        $totalStudents = $pendingCount + $approvedCount + $rejectedCount;

        $accounts = Account::query()
            ->where('role', '!=', 'admin')
            ->when(
                in_array($statusFilter, ['pending', 'approved', 'rejected'], true),
                fn (Builder $query) => $query->where('verification_status', $statusFilter)
            )
            ->when($keyword !== '', function (Builder $query) use ($keyword): void {
                $like = '%'.mb_strtolower($keyword).'%';
                $query->where(function (Builder $nested) use ($like): void {
                    $nested->whereRaw('LOWER(full_name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(COALESCE(nim, \'\')) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(COALESCE(university, \'\')) LIKE ?', [$like]);
                });
            })
            ->orderByRaw("CASE WHEN verification_status = 'pending' THEN 0 WHEN verification_status = 'rejected' THEN 1 ELSE 2 END")
            ->orderByDesc('account_id')
            ->get();

        return view('admin.verifications', [
            'accounts' => $accounts,
            'statusFilter' => $statusFilter,
            'keyword' => $keyword,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'totalStudents' => $totalStudents,
        ]);
    }

    /**
     * Approve a student registration and send the email verification link to their inbox.
     */
    public function approve(Account $account): RedirectResponse
    {
        if ($account->isAdmin()) {
            return back()->with('error', 'Akun Admin tidak memerlukan verifikasi.');
        }

        $account->verification_status = 'approved';
        $account->rejection_reason = null;
        $account->email_verified_at = null;
        $account->save();

        try {
            $account->sendEmailVerificationNotification();
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'info',
                "Data {$account->full_name} telah disetujui, namun pengiriman email verifikasi terkendala koneksi SMTP: {$exception->getMessage()}"
            );
        }

        return back()->with(
            'success',
            "Pendaftaran {$account->full_name} disetujui! Link verifikasi telah dikirim ke {$account->email}."
        );
    }

    /**
     * Reject a student registration and send a rejection email explaining why the data/KTM was invalid.
     */
    public function reject(Request $request, Account $account): RedirectResponse
    {
        if ($account->isAdmin()) {
            return back()->with('error', 'Akun Admin tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi agar mahasiswa mengetahui data mana yang tidak valid.',
        ]);

        $account->verification_status = 'rejected';
        $account->rejection_reason = $validated['rejection_reason'];
        $account->email_verified_at = null;
        $account->save();

        try {
            $account->notify(new AccountRejectedNotification($validated['rejection_reason']));
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'info',
                "Pendaftaran {$account->full_name} telah ditolak, namun pengiriman email penolakan terkendala koneksi SMTP."
            );
        }

        return back()->with(
            'success',
            "Pendaftaran {$account->full_name} ditolak dan email pemberitahuan penolakan telah dikirim ke {$account->email}."
        );
    }

    /**
     * Stream or preview the student's uploaded KTM document for Admin inspection.
     */
    public function ktm(Account $account): BinaryFileResponse|Response
    {
        $ktmPath = (string) $account->ktm;

        if ($ktmPath !== '') {
            foreach (['local', 'public'] as $disk) {
                if (Storage::disk($disk)->exists($ktmPath)) {
                    return response()->file(Storage::disk($disk)->path($ktmPath));
                }
            }
        }

        $name = e($account->full_name);
        $nim = e($account->nim ?? '-');
        $university = e($account->university ?? '-');
        $email = e($account->email);

        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 380" width="640" height="380">
            <rect width="640" height="380" rx="24" fill="#0f1f3d"/>
            <rect x="24" y="24" width="592" height="332" rx="18" fill="#152a52" stroke="#24427a" stroke-width="2"/>
            <rect x="52" y="54" width="120" height="30" rx="8" fill="#2160f5"/>
            <text x="112" y="74" fill="#ffffff" font-family="sans-serif" font-size="13" font-weight="bold" text-anchor="middle">KARTU MAHASISWA</text>
            <text x="588" y="74" fill="#93c5fd" font-family="sans-serif" font-size="13" font-weight="bold" text-anchor="end">{$university}</text>
            <rect x="52" y="112" width="120" height="148" rx="14" fill="#1e3a6d" stroke="#3b82f6" stroke-width="1.5"/>
            <circle cx="112" cy="166" r="26" fill="#3b82f6"/>
            <path d="M74 238c8-22 26-34 38-34s30 12 38 34" fill="#3b82f6"/>
            <text x="200" y="138" fill="#94a3b8" font-family="sans-serif" font-size="12">NAMA LENGKAP</text>
            <text x="200" y="162" fill="#ffffff" font-family="sans-serif" font-size="20" font-weight="bold">{$name}</text>
            <text x="200" y="198" fill="#94a3b8" font-family="sans-serif" font-size="12">NOMOR INDUK MAHASISWA (NIM)</text>
            <text x="200" y="222" fill="#38bdf8" font-family="monospace" font-size="18" font-weight="bold">{$nim}</text>
            <text x="200" y="256" fill="#94a3b8" font-family="sans-serif" font-size="12">EMAIL KAMPUS / KONTAK</text>
            <text x="200" y="278" fill="#e2e8f0" font-family="sans-serif" font-size="14">{$email}</text>
            <text x="52" y="326" fill="#64748b" font-family="sans-serif" font-size="11">Preview KTM Digital TeamMate (File: {$ktmPath})</text>
        </svg>
        SVG;

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }
}
