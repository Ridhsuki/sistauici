<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class JadwalSeminarMahasiswaController extends Controller
{
    /**
     * Menampilkan daftar jadwal seminar (bimbingan yang sudah disetujui) untuk mahasiswa.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bimbingans = Bimbingan::with('dosen')
            ->where('mahasiswa_id', Auth::id())
            ->where('status', 'approved')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->orderBy('waktu_mulai', 'desc')
            ->get();

        return view('mahasiswa.jadwal_seminar.index', compact('bimbingans'));
    }

    public function historyBimbingan()
    {
        $bimbingans = Bimbingan::with('dosen')
            ->where('mahasiswa_id', auth()->id())
            ->riwayat()
            ->orderBy('tanggal_bimbingan', 'desc')
            ->paginate(10);

        return view('mahasiswa.jadwal_seminar.history', compact('bimbingans'));
    }
    public function exportPdfHistory()
    {
        $bimbingans = Bimbingan::with('dosen')
            ->where('mahasiswa_id', auth()->id())
            ->riwayat()
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        $data = [
            'title' => 'Laporan Riwayat Pelaksanaan Bimbingan',
            'user' => auth()->user(),
            'role' => 'mahasiswa',
            'bimbingans' => $bimbingans,
            'total_selesai' => $bimbingans->where('status', 'approved')->count(),
            'total_ditolak' => $bimbingans->where('status', 'rejected')->count(),
        ];

        $pdf = Pdf::loadView('pdf.history_bimbingan', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Riwayat_Bimbingan_Saya_' . date('Ymd') . '.pdf');
    }
}
