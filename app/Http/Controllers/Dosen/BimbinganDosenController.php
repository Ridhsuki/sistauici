<?php

namespace App\Http\Controllers\Dosen;

use App\Helpers\NotifyHelper;
use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class BimbinganDosenController extends Controller
{
    public function index()
    {
        $bimbingans = Bimbingan::with('mahasiswa')
            ->where('dosen_id', auth()->id())
            // before
            // ->latest()

            // after
            ->where('status', '!=', 'rejected')
            ->whereDate('tanggal_bimbingan', '>=', \Carbon\Carbon::today())
            ->orderBy('tanggal_bimbingan', 'asc')
            ->get();

        return view('dosen.bimbingan.index', compact('bimbingans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $bimbingan = Bimbingan::where('dosen_id', auth()->id())->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'catatan_dosen' => 'nullable|string',
        ]);

        $bimbingan->update([
            'status' => $request->status,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotifyHelper::send(
                $admin->id,
                'Status Bimbingan Mahasiswa',
                'Jadwal bimbingan ' . $bimbingan->mahasiswa->name . ' telah ' . $bimbingan->status . ' oleh dosen.',
                route('admin.jadwal.index')
            );
        }

        if ($bimbingan->status === 'approved') {
            NotifyHelper::send(
                $bimbingan->mahasiswa_id,
                'Jadwal Bimbingan Terbit',
                'Jadwal bimbingan Anda telah disetujui. Silakan cek jadwal Anda.',
                route('mahasiswa.jadwal-seminar')
            );
        }

        if (!empty($request->catatan_dosen)) {
            NotifyHelper::send(
                $bimbingan->mahasiswa_id,
                'Catatan Baru dari Dosen Pembimbing',
                'Dosen Anda telah menambahkan catatan baru pada bimbingan Anda: "' . $request->catatan_dosen . '"',
                route('mahasiswa.jadwal-seminar', $bimbingan->id)
            );
        }

        return redirect()->route('dosen.bimbingan.index')->with('success', 'Status dan catatan berhasil diperbarui');
    }
    public function indexJadwal()
    {
        $bimbingans = Bimbingan::with('mahasiswa')
            ->where('dosen_id', auth()->id())
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('dosen.jadwal.index', compact('bimbingans'));
    }
    public function updateDetails(Request $request, $id)
    {
        $bimbingan = Bimbingan::where('dosen_id', auth()->id())->findOrFail($id);

        $request->validate([
            'link_meet' => 'nullable|url',
            'file_prosedur' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'link_meet' => $request->link_meet,
        ];

        if ($request->hasFile('file_prosedur')) {
            if ($bimbingan->file_prosedur && Storage::disk('public')->exists($bimbingan->file_prosedur)) {
                Storage::disk('public')->delete($bimbingan->file_prosedur);
            }

            $path = $request->file('file_prosedur')->store('prosedur_bimbingan', 'public');
            $data['file_prosedur'] = $path;
        }

        $bimbingan->update($data);

        NotifyHelper::send(
            $bimbingan->mahasiswa_id,
            'Update Jadwal Bimbingan',
            'Dosen telah menambahkan Link Meeting atau Prosedur Bimbingan. Silakan cek detail jadwal Anda.',
            route('mahasiswa.jadwal-seminar')
        );

        return redirect()->back()->with('success', 'Detail bimbingan berhasil diperbarui.');
    }

    public function history()
    {
        $bimbingans = Bimbingan::with('mahasiswa')
            ->where('dosen_id', auth()->id())
            ->riwayat()
            ->orderBy('tanggal_bimbingan', 'desc')
            ->paginate(10);

        return view('dosen.bimbingan.history', compact('bimbingans'));
    }

    public function exportPdfHistory()
    {
        $bimbingans = Bimbingan::with('mahasiswa')
            ->where('dosen_id', auth()->id())
            ->riwayat()
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        $data = [
            'title' => 'Laporan Riwayat Bimbingan Mahasiswa',
            'user' => auth()->user(),
            'role' => 'dosen',
            'bimbingans' => $bimbingans,
            'total_selesai' => $bimbingans->where('status', 'approved')->count(),
            'total_ditolak' => $bimbingans->where('status', 'rejected')->count(),
        ];

        $pdf = Pdf::loadView('pdf.history_bimbingan', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Riwayat_Bimbingan_Dosen_' . date('Ymd') . '.pdf');
    }
}
