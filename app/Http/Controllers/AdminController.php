<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // OTORISASI KETAT: Jika bukan admin, tampilkan error 403 (Forbidden)
        abort_if(!Auth::user()->is_admin, 403, 'Akses Ditolak: Halaman ini khusus Administrator LFM.');

        // Mengambil semua klaim yang masih "menunggu" verifikasi
        $antreanKlaim = Claim::with(['item', 'user'])
                             ->where('status_klaim', 'menunggu')
                             ->orderBy('created_at', 'asc')
                             ->get();

        // Mengambil ringkasan semua laporan barang di sistem
        $semuaBarang = Item::with('user')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('admin.dashboard', compact('antreanKlaim', 'semuaBarang'));
    }
}
