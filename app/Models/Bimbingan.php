<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bimbingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal_bimbingan',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'catatan_dosen',
        'link_meet',
        'file_prosedur',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function scopeRiwayat($query)
    {
        return $query->where(function ($q) {
            $q->where('tanggal_bimbingan', '<', Carbon::today())
                ->orWhere('status', 'rejected');
        });
    }
}
