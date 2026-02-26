<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use App\Models\Proposal;
use App\Models\DokumenAkhir;
use App\Models\Bimbingan;
use App\Models\Nilai;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalDosen = User::where('role', 'dosen')->count();

        $totalProposal = Proposal::count();
        $proposalPending = Proposal::where('status', 'pending')->count();
        $proposalAccepted = Proposal::where('status', 'diterima')->count();
        $proposalRejected = Proposal::where('status', 'ditolak')->count();

        $totalDokumen = DokumenAkhir::count();
        $dokumenPending = DokumenAkhir::where('status', 'pending')->count();
        $dokumenApproved = DokumenAkhir::where('status', 'approved')->count();
        $dokumenRejected = DokumenAkhir::where('status', 'rejected')->count();

        $sidangPending = DokumenAkhir::where('status', 'pending')->count();

        $recentProposals = Proposal::with('mahasiswa')->latest()->limit(5)->get();

        $recentPengumuman = Pengumuman::latest()->limit(3)->get();

        return view('dashboard.admin', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalProposal',
            'proposalPending',
            'proposalAccepted',
            'proposalRejected',
            'totalDokumen',
            'dokumenPending',
            'dokumenApproved',
            'dokumenRejected',
            'sidangPending',
            'recentProposals',
            'recentPengumuman'
        ));
    }

    public function dosen()
    {
        $dosenId = Auth::id();
        $mahasiswaBimbingan = Proposal::where('dosen_pembimbing_id', $dosenId)
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');

        $proposalToReview = Proposal::where('dosen_pembimbing_id', $dosenId)
            ->where('status', 'pending')
            ->count();

        $proposalAccepted = Proposal::where('dosen_pembimbing_id', $dosenId)
            ->where('status', 'diterima')
            ->count();

        $proposalRejected = Proposal::where('dosen_pembimbing_id', $dosenId)
            ->where('status', 'ditolak')
            ->count();

        $dokumenToApprove = DokumenAkhir::where('dosen_pembimbing_id', $dosenId)
            ->where('status', 'pending')
            ->count();

        $dokumenApproved = DokumenAkhir::where('dosen_pembimbing_id', $dosenId)
            ->where('status', 'approved')
            ->count();

        $dokumenRejected = DokumenAkhir::where('dosen_pembimbing_id', $dosenId)
            ->where('status', 'rejected')
            ->count();

        $nilaiProposal = Nilai::where('dosen_id', $dosenId)
            ->whereNotNull('proposal_id')
            ->count();

        $nilaiDokumenAkhir = Nilai::where('dosen_id', $dosenId)
            ->whereNotNull('dokumen_akhir_id')
            ->count();

        $bimbinganCount = Bimbingan::where('dosen_id', $dosenId)
            ->count();

        return view('dashboard.dosen', compact(
            'mahasiswaBimbingan',
            'proposalToReview',
            'proposalAccepted',
            'proposalRejected',
            'dokumenToApprove',
            'dokumenApproved',
            'dokumenRejected',
            'nilaiProposal',
            'nilaiDokumenAkhir',
            'bimbinganCount'
        ));
    }

    public function mahasiswa()
    {
        $user = Auth::user();

        $proposal = Proposal::where('mahasiswa_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $proposalStatus = $proposal ? $proposal->status : 'belum_pengajuan';

        $dokumenAkhirTerakhir = DokumenAkhir::where('mahasiswa_id', $user->id)
            ->where('bab', 6)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($dokumenAkhirTerakhir && $dokumenAkhirTerakhir->status === 'approved') {
            $dokumenStatus = 'approved';
        } else {
            $dokumenAktif = DokumenAkhir::where('mahasiswa_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($dokumenAktif) {
                $dokumenStatus = 'in_progress';
            } else {
                $dokumenStatus = 'belum_pengajuan';
            }
        }

        $bimbinganDone = Bimbingan::where('mahasiswa_id', $user->id)
            ->where('status', 'approved')->count();
        $bimbinganPending = Bimbingan::where('mahasiswa_id', $user->id)
            ->where('status', 'pending')->count();

        $pengumumans = Pengumuman::orderBy('tanggal', 'desc')->take(4)->get();

        return view('dashboard.mahasiswa', compact(
            'proposalStatus',
            'dokumenStatus',
            'bimbinganDone',
            'bimbinganPending',
            'pengumumans'
        ));
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);

        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Notifikasi ini bukan milik Anda.');
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);

            Cache::forget('user_notifications_' . Auth::id());
        }

        return redirect($notification->link ?? '/');
    }

    public function clearAllNotifications()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
