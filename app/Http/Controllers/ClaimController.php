<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Tambahkan baris ini

class ClaimController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'bukti_klaim' => 'required|string',
        ]);

        Claim::create([
            'item_id' => $itemId,
            'user_id' => Auth::id(), // Mengambil ID dari session login
            'bukti_klaim' => $request->bukti_klaim,
            'status_klaim' => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Klaim berhasil diajukan!');
    }
    public function updateStatus(Request $request, $claimId)
    {
        $statusBaru = $request->input('status_klaim'); // 'diterima' atau 'ditolak'

        // PANGGIL STORED PROCEDURE MYSQL
        // Menggunakan bind parameter agar aman dari SQL Injection
        // Parameter OUT ditangkap menggunakan variabel session MySQL (@kode, @pesan)
        DB::statement("CALL ProsesPembaruanStatusKlaim(?, ?, @kode, @pesan)", [$claimId, $statusBaru]);

        // Membaca hasil evaluasi CASE...END dari MySQL
        $hasil = DB::select("SELECT @kode AS kode, @pesan AS pesan")[0];

        // Mencegah hard-stop: Laravel menangani respon dengan gracefully
        if ($hasil->kode == 0) {
            return redirect()->back()->with('error', $hasil->pesan);
        }

        return redirect()->back()->with('success', $hasil->pesan);
    }
}
