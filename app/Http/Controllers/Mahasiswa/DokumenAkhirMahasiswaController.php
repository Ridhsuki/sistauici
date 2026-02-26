<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Helpers\NotifyHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DokumenAkhir;
use App\Models\User;

class DokumenAkhirMahasiswaController extends Controller
{
    public function index()
    {
        $allUploads = DokumenAkhir::own()->orderBy('created_at', 'desc')->get();

        $uploadsHistory = $allUploads->groupBy('bab');

        $uploads = $uploadsHistory->map->first();

        $chapters = [
            1 => 'Bab 1 - Pendahuluan',
            2 => 'Bab 2 - Tinjauan Pustaka',
            3 => 'Bab 3 - Metodologi Penelitian',
            4 => 'Bab 4 - Hasil dan Pembahasan',
            5 => 'Bab 5 - Penutup',
            6 => 'Daftar Pustaka & Lampiran'
        ];

        $bab1 = $uploads->get(1);
        $defaultDosenId = $bab1 ? $bab1->dosen_pembimbing_id : null;

        $dosens = User::where('role', 'dosen')->orderBy('name')->get();

        return view('mahasiswa.dokumen.index', compact('uploads', 'uploadsHistory', 'chapters', 'dosens', 'defaultDosenId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'bab' => 'required|integer|min:1|max:6',
            'file' => 'required|mimes:pdf,doc,docx|max:10240',
            'dosen_pembimbing_id' => 'required|exists:users,id',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->bab > 1) {
            $prevBab = $request->bab - 1;
            $prevDoc = DokumenAkhir::where('mahasiswa_id', Auth::id())
                ->where('bab', $prevBab)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$prevDoc || $prevDoc->status !== 'approved') {
                return redirect()->back()
                    ->with('error', "Anda tidak dapat mengunggah Bab {$request->bab} sebelum Bab {$prevBab} disetujui (Approved).")
                    ->withInput();
            }
        }

        $currentDoc = DokumenAkhir::where('mahasiswa_id', Auth::id())
            ->where('bab', $request->bab)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($currentDoc && in_array($currentDoc->status, ['pending', 'approved'])) {
            return redirect()->back()
                ->with('error', "Dokumen Bab {$request->bab} saat ini sedang diproses atau sudah disetujui. Anda hanya bisa mengunggah ulang jika statusnya Ditolak/Revisi.");
        }

        $path = $request->file('file')->store('dokumen_akhir', 'public');

        $dokumen = DokumenAkhir::create([
            'mahasiswa_id' => Auth::id(),
            'bab' => $request->bab,
            'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
            'judul' => $request->judul,
            'file' => $path,
            'status' => 'pending',
            'deskripsi' => $request->deskripsi,
            'catatan_dosen' => null,
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotifyHelper::send(
                $admin->id,
                'Update Skripsi: ' . Auth::user()->name,
                Auth::user()->name . " mengunggah dokumen untuk Bab " . $request->bab,
                route('admin.dokumen-akhir.index')
            );
        }

        if ($dokumen->dosen_pembimbing_id) {
            NotifyHelper::send(
                $dokumen->dosen_pembimbing_id,
                'Bimbingan Baru: Bab ' . $request->bab,
                Auth::user()->name . ' menunggu review untuk Bab ' . $request->bab,
                route('dosen.dokumen-akhir.show-mahasiswa', Auth::id())
            );
        }

        return redirect()->back()->with('success', 'Dokumen Bab ' . $request->bab . ' berhasil diunggah.');
    }
}
