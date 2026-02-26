<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DokumenAkhir extends Model
{
    use HasFactory;

    protected $table = 'dokumen_akhirs';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_pembimbing_id',
        'judul',
        'bab',
        'file',
        'status',
        'deskripsi',
        'catatan_dosen',
    ];


    /**
     * Relasi ke model User (mahasiswa).
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }
    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'dokumen_akhir_id');
    }

    /**
     * Relasi opsional ke Proposal (jika ada).
     */
    public function proposal()
    {
        return $this->hasOne(Proposal::class, 'dokumen_id');
    }

    /**
     * Helper untuk ambil URL file dokumen.
     */
    public function getFileUrlAttribute()
    {
        return $this->file ? asset('storage/' . $this->file) : null;
    }

    /**
     * Scope: hanya ambil dokumen milik mahasiswa yang login.
     */
    public function scopeOwn($query)
    {
        return $query->where('mahasiswa_id', Auth::id());
    }

    /**
     * Scope: filter berdasarkan judul.
     */
    public function scopeSearchJudul($query, $keyword)
    {
        return $query->where('judul', 'like', '%' . $keyword . '%');
    }

    /**
     * Accessor: tampilkan judul dengan format Title Case.
     */
    public function getFormattedJudulAttribute()
    {
        return ucwords(strtolower($this->judul));
    }

    public function getNamaBabAttribute()
    {
        $list = [
            1 => 'Bab 1 - Pendahuluan',
            2 => 'Bab 2 - Tinjauan Pustaka',
            3 => 'Bab 3 - Metodologi Penelitian',
            4 => 'Bab 4 - Hasil dan Pembahasan',
            5 => 'Bab 5 - Penutup',
            6 => 'Daftar Pustaka & Lampiran',
            7 => 'Full Dokumen Akhir (Skripsi Lengkap)'
        ];
        return $list[$this->bab] ?? 'Dokumen Lainnya';
    }
}
