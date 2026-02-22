<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Notification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'role',
        'NIM',
        'NIDN',
        'prodi',
        'bidang_keahlian',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship - Mahasiswa mengajukan banyak proposal
     */
    public function proposalsAsMahasiswa()
    {
        return $this->hasMany(Proposal::class, 'mahasiswa_id');
    }

    /**
     * Relationship - Dosen membimbing banyak proposal
     */
    public function proposalsAsDosen()
    {
        return $this->hasMany(Proposal::class, 'dosen_pembimbing_id');
    }

    /**
     * Relationship - Mahasiswa booking banyak bimbingan
     */
    public function bimbingansAsMahasiswa()
    {
        return $this->hasMany(Bimbingan::class, 'mahasiswa_id');
    }

    /**
     * Relationship - Dosen menerima banyak bimbingan
     */
    public function bimbingansAsDosen()
    {
        return $this->hasMany(Bimbingan::class, 'dosen_id');
    }

    public function mahasiswaBimbinganProposal()
    {
        return $this->hasMany(Proposal::class, 'dosen_pembimbing_id')->with('mahasiswa');
    }

    // Nilai yang diberikan oleh dosen ini
    public function nilaiDiberikan()
    {
        return $this->hasMany(Nilai::class, 'dosen_id');
    }

    // Nilai yang diterima oleh mahasiswa ini (melalui proposal)
    public function nilaiDiterima()
    {
        return $this->hasManyThrough(
            Nilai::class,
            Proposal::class,
            'mahasiswa_id', // Foreign key di Proposal
            'proposal_id',  // Foreign key di Nilai
            'id',           // Local key di User
            'id'            // Local key di Proposal
        );
    }
    public function dokumenAkhirsAsMahasiswa()
    {
        return $this->hasMany(DokumenAkhir::class, 'mahasiswa_id');
    }

    public function dokumenAkhirsAsDosen()
    {
        return $this->hasMany(DokumenAkhir::class, 'dosen_pembimbing_id');
    }

    /**
     * Relasi ke Dokumen Akhir (Skripsi Per Bab)
     * Satu mahasiswa bisa memiliki banyak dokumen (Bab 1, Bab 2, dst)
     */
    public function dokumenAkhir()
    {
        // Parameter kedua 'mahasiswa_id' adalah foreign key di tabel dokumen_akhirs
        return $this->hasMany(DokumenAkhir::class, 'mahasiswa_id');
    }
    /**
     * [FIX] OVERRIDE RELASI NOTIFICATIONS
     * Mengubah standar Laravel (Polymorphic) menjadi Custom (HasMany user_id)
     * agar sesuai dengan tabel 'notifications' yang Anda buat.
     */
    public function notifications()
    {
        // Menggunakan 'user_id' sesuai tabel database Anda
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    /**
     * Scope untuk melakukan pencarian user berdasarkan nama atau email
     */
    public function scopeSearchUser($query, $term)
    {
        $term = "%{$term}%";
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', $term)
                ->orWhere('email', 'like', $term);
        });
    }
}
