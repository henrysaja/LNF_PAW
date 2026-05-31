<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'user_id', 'bukti_klaim', 'status_klaim'];

    // Relasi ke barang yang diklaim
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke User (Siapa yang mengklaim)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
