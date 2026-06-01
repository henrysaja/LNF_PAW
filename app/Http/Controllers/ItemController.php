<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // Fungsi untuk menampilkan daftar barang di halaman utama
    public function index(Request $request)
    {
        $query = Item::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function($q) use ($cari) {
                $q->where('nama_barang', 'like', '%' . $cari . '%')
                  ->orWhere('lokasi_ditemukan_atau_hilang', 'like', '%' . $cari . '%')
                  ->orWhere('deskripsi', 'like', '%' . $cari . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $items = $query->get();

        // JIKA REQUEST BERASAL DARI REALTIME SEARCH (AJAX)
        if ($request->ajax()) {
            return view('items.partials._item_list', compact('items'))->render();
        }

        // JIKA HALAMAN DIMUAT BIASA
        return view('items.index', compact('items'));
    }

    // Fungsi untuk menampilkan form pembuatan laporan
    public function create()
    {
        return view('items.create');
    }

    // Fungsi untuk memproses data dari form ke database
   public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi_ditemukan_atau_hilang' => 'required|string|max:255',
            'status' => 'required|in:hilang,ditemukan',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar maks 2MB
        ]);

        $imagePath = null;
        // Jika pengguna mengunggah file foto
        if ($request->hasFile('foto_barang')) {
            // Simpan ke folder 'items' di dalam storage/app/public
            $imagePath = $request->file('foto_barang')->store('items', 'public');
        }

        Item::create([
            'user_id' => Auth::id(),
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'lokasi_ditemukan_atau_hilang' => $request->lokasi_ditemukan_atau_hilang,
            'status' => $request->status,
            'foto_barang' => $imagePath, // Simpan path gambar ke database
        ]);

        return redirect()->route('items.index')->with('success', 'Laporan berhasil dibuat!');
    }

    // Fungsi untuk melihat detail barang dan pengajuan klaim
    public function show(Item $item)
    {
        // Mengambil data barang, pelapornya, dan semua klaim beserta data pengklaim
        $item->load(['user', 'claims.user']);

        return view('items.show', compact('item'));
    }

    public function markAsFound(Item $item)
    {
        // 1. OTORISASI: Pastikan pengguna yang login adalah pembuat laporan asli
        if ($item->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak: Anda bukan pembuat laporan ini.');
        }

        // 2. VALIDASI LOGIKA: Pastikan status saat ini memang 'hilang'
        if ($item->status !== 'hilang') {
            return redirect()->back()->with('error', 'Gagal: Laporan ini sudah tidak berstatus hilang.');
        }

        // 3. Eksekusi perubahan status menjadi 'ditemukan' melalui Model
        $item->update([
            'status' => 'ditemukan'
        ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui! Sekarang mahasiswa lain dapat melihat bahwa barang telah ditemukan.');
    }
}
