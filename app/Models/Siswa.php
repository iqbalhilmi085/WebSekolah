<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'nama_orangtua',
        'no_telp',
        'kelas',
        'foto',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function getTotalPembayaranAttribute()
    {
        return $this->pembayaran()->sum('jumlah');
    }

    public function getPembayaranLunasAttribute()
    {
        return $this->pembayaran()->where('status', 'lunas')->count();
    }

    public function getPembayaranBelumLunasAttribute()
    {
        return $this->pembayaran()->where('status', 'belum_lunas')->count();
    }
}