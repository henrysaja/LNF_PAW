<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item; // Tambahkan Item untuk melakukan pengecekan validasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'bukti_klaim' => 'required|string',
        ]);

        $item = Item::findOrFail($itemId);

        // 1. BLOKIR JIKA BARANG BUKAN BERSTATUS "DITEMUKAN"
        if ($item->status !== 'ditemukan') {
            return redirect()->back()->with('error', 'Gagal: Klaim hanya dapat diajukan untuk barang yang berstatus "Ditemukan".');
        }

        // 2. Cegah mahasiswa mengklaim barang laporannya sendiri
        if ($item->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'Gagal: Anda tidak dapat mengklaim barang yang Anda laporkan sendiri.');
        }

        // 3. Cegah klaim ganda (spam)
        $sudahKlaim = Claim::where('item_id', $itemId)
                           ->where('user_id', Auth::id())
                           ->exists();

        if ($sudahKlaim) {
            return redirect()->back()->with('error', 'Gagal: Anda sudah mengajukan klaim untuk barang ini. Harap tunggu verifikasi Admin.');
        }

        Claim::create([
            'item_id' => $itemId,
            'user_id' => Auth::id(),
            'bukti_klaim' => $request->bukti_klaim,
            'status_klaim' => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Klaim berhasil diajukan!');
    }

    public function updateStatus(Request $request, $claimId)
    {
        // OTORISASI: Tolak jika yang menekan tombol bukan Admin
        if (!Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'Akses ditolak: Hanya Admin yang berhak memverifikasi klaim barang.');
        }

        // Validasi input status sebelum dilempar ke Stored Procedure
        $request->validate([
            'status_klaim' => 'required|in:diterima,ditolak',
        ]);

        $statusBaru = $request->input('status_klaim');

        // PANGGIL STORED PROCEDURE MYSQL (Sesuai aturan CASE...END)
        // Penulisan DB:: sudah cukup karena sudah di-import di atas
        DB::statement("CALL ProsesPembaruanStatusKlaim(?, ?, @kode, @pesan)", [$claimId, $statusBaru]);

        $hasil = DB::select("SELECT @kode AS kode, @pesan AS pesan")[0];

        // Evaluasi respon dari MySQL untuk mencegah hard-stop
        if ($hasil->kode == 0) {
            return redirect()->back()->with('error', $hasil->pesan);
        }

        return redirect()->back()->with('success', $hasil->pesan);
    }
}
