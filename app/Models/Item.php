<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Claim;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama_barang', 'deskripsi', 'lokasi_ditemukan_atau_hilang', 'status'];

    // Relasi ke User (Siapa yang melapor)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke klaim-klaim yang masuk untuk barang ini
    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
